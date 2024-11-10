<?php

namespace App\Livewire;

use App\Enums\JobSavedStatus;
use App\Models\JobUser;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class MyApplications extends Component
{
    use WithPagination;

    public bool $isLoading = false;
    public string $activeTab = 'all';
    protected $paginationTheme = 'tailwind';
    protected $perPage = 5;

    public function setTab($tab): void
    {
        $this->isLoading = true;
        $this->activeTab = $tab;
        $this->resetPage();

        switch ($tab) {
            case JobSavedStatus::SAVED->value:
                $this->saved();
                break;
            case JobSavedStatus::APPLIED->value:
                $this->applied();
                break;
            default:
                $this->allApplications();
        }

        $this->isLoading = false;
    }

    #[Computed]
    public function applications()
    {
        $query = auth()->user()->jobs();

        switch ($this->activeTab) {
            case JobSavedStatus::SAVED->value:
                $query->wherePivot('status', JobSavedStatus::SAVED->value);
                break;
            case JobSavedStatus::APPLIED->value:
                $query->wherePivot('status', JobSavedStatus::APPLIED->value);
                break;
            default:
                break;
        }

        return $query->latest()->paginate($this->perPage);
    }

    public function allApplications(): void
    {
        $this->activeTab = 'all';
    }

    public function applied(): void
    {
        $this->activeTab = JobSavedStatus::APPLIED->value;
    }

    public function saved(): void
    {
        $this->activeTab = JobSavedStatus::SAVED->value;
    }

    public function updatedPage()
    {
        $this->isLoading = true;
        $this->isLoading = false;
    }

    public function removeSavedJob($id){
        $job = JobUser::query()
            ->where('job_id', $id)
            ->where('user_id', auth()->user()->id)
            ->first();
        $job->delete();
        $this->dispatch('notify', [
            'message' => 'Job removed from saved jobs',
            'type' => 'success'
        ]);
    }
    public function render()
    {
        return view('livewire.my-applications');
    }
}
