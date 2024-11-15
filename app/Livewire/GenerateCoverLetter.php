<?php

namespace App\Livewire;

use App\Jobs\GenerateCoverLetterJob;
use App\Models\JobListing;
use Livewire\Component;

class GenerateCoverLetter extends Component
{
    public JobListing $jobListing;
    public $coverLetter;

    public function mount(JobListing $job)
    {
        $this->jobListing = $job;
    }

    public function generateCoverLetter()
    {
        GenerateCoverLetterJob::dispatch(auth()->user(), $this->jobListing->toArray());
    }

    public function render()
    {
        return view('livewire.generate-cover-letter');
    }
}

