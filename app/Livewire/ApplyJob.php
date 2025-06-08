<?php

namespace App\Livewire;

use App\Enums\JobSavedStatus;
use App\Models\JobListing;
use Livewire\Component;

class ApplyJob extends Component
{
    public JobListing $job;
    public $hasApplied = false;

    public function mount($job)
    {
        $this->job = $job;
        $this->alreadyApplied();
    }

    public function apply()
    {
        auth()->user()->jobs()->attach($this->job->id, ['status' => JobSavedStatus::APPLIED->value]);

        logger('Execution time', [(microtime(true) - LARAVEL_START) * 1000]);
    }

    public function alreadyApplied(): void
    {
        if (auth()->user()) {
            $this->hasApplied = auth()->user()
                                ->jobs()
                                ->where('job_user.job_id', $this->job->id)
                                ->exists();
        }
    }
    public function render()
    {
        return view('livewire.apply-job');
    }
}
