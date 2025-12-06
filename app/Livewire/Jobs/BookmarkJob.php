<?php

namespace App\Livewire\Jobs;

use App\Enums\JobSavedStatus;
use App\Models\JobUser;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class BookmarkJob extends Component
{
    public int $jobId;

    public bool $isBookmarked = false;

    public function mount(int $jobId): void
    {
        $this->jobId = $jobId;
        $this->checkBookmarkStatus();
    }

    public function checkBookmarkStatus(): void
    {
        if (! auth()->check()) {
            $this->isBookmarked = false;

            return;
        }

        $cacheKey = 'user_bookmark_'.auth()->id().'_job_'.$this->jobId;

        $this->isBookmarked = Cache::remember($cacheKey, 300, function () {
            return JobUser::where([
                'job_id' => $this->jobId,
                'user_id' => auth()->id(),
                'status' => JobSavedStatus::SAVED->value,
            ])->exists();
        });
    }

    public function toggleBookmark(): void
    {
        if (! auth()->check()) {
            $this->dispatch('notify', [
                'message' => 'You need to login to bookmark jobs',
                'type' => 'error',
            ]);

            return;
        }

        $cacheKey = 'user_bookmark_'.auth()->id().'_job_'.$this->jobId;

        if ($this->isBookmarked) {
            // Remove bookmark
            JobUser::where([
                'job_id' => $this->jobId,
                'user_id' => auth()->id(),
                'status' => JobSavedStatus::SAVED->value,
            ])->delete();

            $this->isBookmarked = false;
            Cache::put($cacheKey, false, 300);

            $this->dispatch('notify', [
                'message' => 'Job removed from bookmarks',
                'type' => 'success',
            ]);
        } else {
            // Add bookmark
            JobUser::updateOrCreate([
                'job_id' => $this->jobId,
                'user_id' => auth()->id(),
            ], [
                'status' => JobSavedStatus::SAVED->value,
            ]);

            $this->isBookmarked = true;
            Cache::put($cacheKey, true, 300);

            $this->dispatch('notify', [
                'message' => 'Job bookmarked successfully',
                'type' => 'success',
            ]);
        }

        // Clear user's bookmarks list cache
        Cache::forget('user_bookmarks_'.auth()->id());
    }

    public function render()
    {
        return view('livewire.jobs.bookmark-job');
    }
}
