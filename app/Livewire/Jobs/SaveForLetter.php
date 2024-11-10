<?php

namespace App\Livewire\Jobs;

use App\Models\JobListing;
use App\Models\JobUser;
use Livewire\Component;

class SaveForLetter extends Component
{
    public JobListing $job;

    public function mount($job): void
    {
        $this->job = $job;
    }

    public function saveForLetter(): void
    {
        JobUser::updateOrCreate([
            'job_id' => $this->job->id,
            'user_id' => auth()->id(),
        ]);

        $this->dispatch('notify', [
            'message' => 'Job Saved Successfully',
            'type' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.jobs.save-for-letter');
    }
}
