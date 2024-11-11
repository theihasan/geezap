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
        if (!auth()->user()) {
            return redirect()->route('login');
        }

        if ($this->hasApplied) {
            $this->dispatch('notify', [
                'message' => 'You have already applied for this job',
                'type' => 'info'
            ]);
        }

        auth()->user()->jobs()->attach($this->job->id, ['status' => JobSavedStatus::APPLIED->value]);
        $this->redirect($this->job->apply_link);
    }

    public function alreadyApplied(): void
    {
        if (auth()->user()) {
            $this->hasApplied = auth()->user()
                                ->jobs()
                                ->where('job_id', $this->job->id)
                                ->exists();
        }
    }
    public function render()
    {
        return view('livewire.apply-job');
    }
}
