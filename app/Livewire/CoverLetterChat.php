<?php

namespace App\Livewire;

use App\Exceptions\DailyChatLimitExceededException;
use App\Exceptions\IncompleteProfileException;
use App\Exceptions\NonAuthenticatedUser;
use App\Exceptions\OpenAIApiKeyInvalidException;
use App\Models\JobListing;
use App\Services\AIService;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class CoverLetterChat extends Component
{
    public JobListing $job;
    public bool $isOpen = false;
    public bool $isGenerating = false;
    public string $currentLetter = '';
    public string $feedback = '';
    public array $chatHistory = [];

    protected $listeners = ['openCoverLetterChat' => 'openChat'];

    public function mount(JobListing $job): void
    {
        $this->job = $job;
    }

    private function hasCompleteProfile($user): bool
    {
        return !empty($user->name) &&
               !empty($user->email) &&
               !empty($user->skills) &&
               !empty($user->experience);
    }

    public function openChat(): void
    {
        try {
            throw_if(!auth()->check(), new NonAuthenticatedUser());

            $user = auth()->user();
            throw_if(!$this->hasCompleteProfile($user), new IncompleteProfileException());

            $this->isOpen = true;

        } catch (NonAuthenticatedUser|IncompleteProfileException $e) {
            $this->dispatch('notify', [
                'message' => $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function closeChat(): void
    {
        $this->isOpen = false;
        // Only reset feedback and isGenerating, keep chatHistory and currentLetter
        //$this->reset(['feedback', 'isGenerating']);
    }

    public function generateInitialLetter(): void
    {
        try {
            throw_if(!auth()->check(), new NonAuthenticatedUser());

            $user = auth()->user();
            throw_if(!$this->hasCompleteProfile($user), new IncompleteProfileException());

            $this->isGenerating = true;
            $this->currentLetter = '';

            // Dispatch generation started event
            $this->dispatch('generation-started');

            // Force UI update to show loading state
            $this->dispatch('$refresh');

            // Only add user message if this is truly the first generation
            if (empty($this->chatHistory)) {
                $this->chatHistory[] = [
                    'type' => 'user',
                    'message' => 'Generate a cover letter for this position',
                    'timestamp' => now()
                ];
            } else {
                // Add a new user message for additional generation request
                $this->chatHistory[] = [
                    'type' => 'user',
                    'message' => 'Generate another cover letter for this position',
                    'timestamp' => now()
                ];
            }

            $this->addAssistantMessage();

            // Force another UI update after adding messages
            $this->dispatch('$refresh');

            // Show notification
            $this->dispatch('notify', [
                'message' => 'Generating your personalized cover letter...',
                'type' => 'info'
            ]);

            $this->streamCoverLetter();

        } catch (NonAuthenticatedUser|IncompleteProfileException $e) {
            $this->isGenerating = false;
            $this->dispatch('notify', [
                'message' => $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function submitFeedback(): void
    {
        $feedbackText = trim($this->feedback);

        if (empty($feedbackText)) {
            $this->dispatch('notify', [
                'message' => 'Please provide feedback on how to improve the cover letter',
                'type' => 'error'
            ]);
            return;
        }

        try {
            $this->isGenerating = true;
            $this->dispatch('generation-started');
            $this->dispatch('$refresh');

            // Add user feedback to chat history
            $this->chatHistory[] = [
                'type' => 'user',
                'message' => $feedbackText,
                'timestamp' => now()
            ];

            $previousLetter = $this->currentLetter;
            $this->currentLetter = '';

            $this->addAssistantMessage();
            $this->dispatch('$refresh');

            $this->dispatch('notify', [
                'message' => 'Regenerating cover letter with your feedback...',
                'type' => 'info'
            ]);

            $this->streamCoverLetter($feedbackText, $previousLetter);

            $this->feedback = '';

        } catch (\Exception $e) {
            Log::error('Failed to submit feedback: ' . $e->getMessage());
            $this->dispatch('notify', [
                'message' => 'Failed to submit feedback. Please try again.',
                'type' => 'error'
            ]);
            $this->isGenerating = false;
            $this->dispatch('$refresh');
        }
    }

    private function addAssistantMessage(): void
    {
        $this->chatHistory[] = [
            'type' => 'assistant',
            'message' => 'Starting to generate your personalized cover letter...',
            'timestamp' => now(),
            'isStreaming' => true
        ];
    }

    private function streamCoverLetter(?string $feedback = null, ?string $previousLetter = null): void
    {
        try {
            $aiService = app(AIService::class);
            $jobData = $this->job->toArray();

            $generator = $aiService->generateCoverLetter(
                auth()->user(),
                $jobData,
                $feedback,
                $previousLetter
            );

            $chunkCount = 0;
            foreach ($generator as $chunk) {
                $this->currentLetter .= $chunk;
                $chunkCount++;

                // Update the last message in chat history
                $lastIndex = count($this->chatHistory) - 1;
                if ($lastIndex >= 0 && isset($this->chatHistory[$lastIndex]['isStreaming'])) {
                    $this->chatHistory[$lastIndex]['message'] = $this->currentLetter;
                }

                // Dispatch refresh more frequently to show real-time progress
                if ($chunkCount % 3 === 0 || strlen($this->currentLetter) % 50 === 0) {
                    $this->dispatch('$refresh');
                }
            }

            // Final update
            $lastIndex = count($this->chatHistory) - 1;
            if ($lastIndex >= 0 && isset($this->chatHistory[$lastIndex]['isStreaming'])) {
                $this->chatHistory[$lastIndex]['message'] = $this->currentLetter;
                unset($this->chatHistory[$lastIndex]['isStreaming']);
            }

            $this->isGenerating = false;

            // Show completion notification
            $this->dispatch('notify', [
                'message' => 'Your cover letter has been generated successfully!',
                'type' => 'success'
            ]);

            $this->dispatch('$refresh');

        } catch (OpenAIApiKeyInvalidException $e) {
            // Ensure we show the error immediately after the loading state
            sleep(1); // Brief delay to show loading state
            $this->handleError('⚠️ API Configuration Error: ' . $e->getMessage());
        } catch (DailyChatLimitExceededException $e) {
            sleep(1);
            $this->handleError($e->getMessage());
        } catch (\Exception $e) {
            Log::error('Cover letter generation failed: ' . $e->getMessage());
            sleep(1);
            $this->handleError('Sorry, there was an error generating your cover letter. Please try again.');
        }
    }

    private function handleError(string $message): void
    {
        $this->isGenerating = false;

        // Update or add error message
        $lastIndex = count($this->chatHistory) - 1;
        if ($lastIndex >= 0 && isset($this->chatHistory[$lastIndex]['isStreaming'])) {
            $this->chatHistory[$lastIndex]['message'] = $message;
            $this->chatHistory[$lastIndex]['isError'] = true;
            unset($this->chatHistory[$lastIndex]['isStreaming']);
        } else {
            $this->chatHistory[] = [
                'type' => 'assistant',
                'message' => $message,
                'timestamp' => now(),
                'isError' => true
            ];
        }

        // Force UI update to show error
        $this->dispatch('$refresh');

        // Show error notification
        $this->dispatch('notify', [
            'message' => $message,
            'type' => 'error'
        ]);
    }

    public function copyCoverLetter(): void
    {
        $this->dispatch('copy-to-clipboard', ['text' => $this->currentLetter]);
        $this->dispatch('notify', [
            'message' => 'Cover letter copied to clipboard!',
            'type' => 'success'
        ]);
    }

    public function copyMessage($messageText): void
    {
        $this->dispatch('copy-to-clipboard', ['text' => $messageText]);
        $this->dispatch('notify', [
            'message' => 'Message copied to clipboard!',
            'type' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.cover-letter-chat');
    }
}
