<?php

namespace App\Livewire;

use App\Events\ExceptionHappenEvent;
use App\Exceptions\AIServiceAPIKeyNotFound;
use App\Exceptions\DailyChatLimitExceededException;
use App\Exceptions\IncompleteProfileException;
use App\Exceptions\NonAuthenticatedUser;
use App\Exceptions\OpenAPICreditExceedException;
use App\Models\Airesponse;
use App\Models\JobListing;
use App\Services\AI\BaseAIService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class GenerateCoverLetter extends Component
{
    public JobListing $jobListing;
    public $isGenerating = false;
    public $answer = '';
    public $feedback = '';

    public function mount(JobListing $job): void
    {
        $this->jobListing = $job;
    }

    private function hasCompleteProfile(mixed $user): bool
    {
        return !empty($user->name) &&
            !empty($user->email) &&
            !empty($user->skills) &&
            !empty($user->experience);
    }

    public function startGeneration(): void
    {
        try {
            throw_if(!auth()->check(), new NonAuthenticatedUser());

            $user = auth()->user();

            throw_if(!$this->hasCompleteProfile($user), new IncompleteProfileException());
            throw_if(!config('ai.chat_gpt_api_key'), new AIServiceAPIKeyNotFound());

            $this->isGenerating = true;
            $this->answer = '';
            $this->feedback = '';
            $this->dispatch('open-chat');

            $this->generateCoverLetter();
        } catch (NonAuthenticatedUser|IncompleteProfileException | AIServiceAPIKeyNotFound $e) {
            $this->handleError($e->getMessage());
        } catch (\Throwable $e) {
        }
    }

    public function regenerateWithFeedback(): void
    {
        if (empty($this->feedback)) {
            $this->dispatch('notify', [
                'message' => 'Please provide feedback on how to improve the cover letter',
                'type' => 'error'
            ]);
            return;
        }

        $originalAnswer = $this->answer;
        $this->isGenerating = true;
        $this->answer = '';
        $this->generateCoverLetter(true, $originalAnswer);
    }

    private function generateCoverLetter(bool $isRegeneration = false, ?string $previousAnswer = null): void
    {
        try {
            $aiService = app(BaseAIService::class);
            try {
                $response = $aiService->getChatResponse(
                    auth()->user(),
                    $this->jobListing->toArray(),
                    function($partial) {
                        Illuminate\Support\Facades\Log::info('Streaming partial response', ['length' => strlen($partial)]);
                        $this->stream('answer', $partial);
                    },
                    $isRegeneration ? $this->feedback : null,
                    $previousAnswer
                );
            } catch (\Throwable $e) {
                Log::error('Error in getChatResponse', [
                    'message' => $e->getMessage(),
                    'class' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

            Airesponse::query()
                ->create([
                    'user_id' => auth()->id(),
                    'job_id' => $this->jobListing->id,
                    'response' => $response
                ]);

            $this->answer = $response;
            $this->isGenerating = false;

        } catch (DailyChatLimitExceededException $e) {
            $this->handleError('Sorry, you have exceeded the daily limit for chat requests. Please try again later.');
        } catch (OpenAPICreditExceedException $e) {
            Log::error('OpenAI credits exceeded');
            $this->handleError('Our AI service is currently unavailable. Please try again later.');
        } catch (\Exception $e) {
            Log::error('Cover Letter Generation Error', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            $this->handleError('An error occurred while generating your cover letter. Please try again.');
        } finally {
            $this->isGenerating = false;
        }
    }

    private function handleError(string $message): void
    {
        $this->answer = $message;
    }
    public function render()
    {
        return view('livewire.generate-cover-letter');
    }
}
