<?php

namespace App\Livewire;

use App\Models\JobListing;
use App\Services\AIService;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

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
            !empty($user->experience) &&
            !empty($user->occupation);
    }

    public function startGeneration(): void
    {
        if (!auth()->check()) {
            $this->dispatch('notify', [
                'message' => 'Please login to generate a cover letter!',
                'type' => 'error'
            ]);
            return;
        }

        $user = auth()->user();

        if (!$this->hasCompleteProfile($user)) {
            $this->dispatch('notify', [
                'message' => 'Please complete your profile with skills and experience before generating a cover letter',
                'type' => 'error'
            ]);
            return;
        }

        $this->isGenerating = true;
        $this->answer = '';
        $this->feedback = '';
        $this->dispatch('open-chat');

        $this->generateCoverLetter();
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

        $this->isGenerating = true;
        $this->answer = '';
        $this->generateCoverLetter(true);
    }

    private function generateCoverLetter(bool $isRegeneration = false): void
    {
        try {
            $aiService = app(AIService::class);

            $this->answer = $aiService->getChatResponse(
                auth()->user(),
                $this->jobListing->toArray(),
                function($partial) {
                    $this->stream('answer', $partial);
                },
                $isRegeneration ? $this->feedback : null
            );

            $this->isGenerating = false;

        } catch (\Exception $e) {
            logger()->error('Cover Letter Generation Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->answer = 'Sorry, there was an error generating your cover letter. Please try again.';
            $this->isGenerating = false;
        }
    }

    public function copyToClipboard(): void
    {
        $this->dispatch('copy-to-clipboard', [
            'text' => $this->answer
        ]);

        $this->dispatch('notify', [
            'message' => 'Cover letter copied to clipboard!',
            'type' => 'success'
        ]);
    }

    public function downloadPDF(): void
    {
        try {
            $pdf = Pdf::loadView('pdfs.cover-letter', [
                'content' => $this->answer,
                'user' => auth()->user(),
                'job' => $this->jobListing
            ]);

            $filename = 'cover-letter-' . now()->format('Y-m-d-His') . '.pdf';
            Storage::put('public/cover-letters/' . $filename, $pdf->output());

            $this->dispatch('notify', [
                'message' => 'Cover letter downloaded successfully!',
                'type' => 'success'
            ]);

            $this->dispatch('download-file', [
                'url' => Storage::url('cover-letters/' . $filename)
            ]);

        } catch (\Exception $e) {
            logger()->error('PDF Generation Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->dispatch('notify', [
                'message' => 'Error generating PDF. Please try again.',
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.generate-cover-letter');
    }
}
