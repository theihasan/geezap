<?php

namespace App\Livewire;

use App\Jobs\GenerateCoverLetterJob;
use App\Models\JobListing;
use Livewire\Component;

class GenerateCoverLetter extends Component
{
    public JobListing $jobListing;
    public $coverLetter;
    public $isGenerating = false;

    public function getListeners(): array
    {
        return [
            "echo-private:cover-letter.".auth()->id().",CoverLetterGenerated" => 'coverLetterGenerated'
        ];
    }
    public function mount(JobListing $job): void
    {
        $this->jobListing = $job;
    }


    public function generateCoverLetter(): void
    {
        $this->isGenerating = true;
        GenerateCoverLetterJob::dispatch(auth()->user(), $this->jobListing->toArray());
    }

    public function coverLetterGenerated($data): void
    {
        $this->coverLetter = $data['response'] ?? null;
        $this->dispatch('refreshComponent');
        $this->isGenerating = false;
    }

    public function render()
    {
        return view('livewire.generate-cover-letter');
    }
}
