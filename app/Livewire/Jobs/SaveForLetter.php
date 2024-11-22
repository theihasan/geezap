<?php

namespace App\Livewire\Jobs;

use App\Enums\JobSavedStatus;
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
        if(! auth()->check()){
            $this->dispatch('notify', [
                'message' => 'You need to login for this action',
                'type' => 'error'
            ]);
            //return user whose are non logged in
            return;
        }
        JobUser::updateOrCreate([
            'job_id' => $this->job->id,
            'user_id' => auth()->id(),
            'status' => JobSavedStatus::SAVED->value,
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
