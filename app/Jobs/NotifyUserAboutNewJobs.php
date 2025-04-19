<?php

namespace App\Jobs;

use App\Models\JobListing;
use App\Models\User;
use App\Notifications\NotifyUserAboutNewJobsNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class NotifyUserAboutNewJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $maxExceptions = 3;
    public int $timeout = 3600;
    public array $backoff = [60,120,180]

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('digest');
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $todayAddedJobs = JobListing::query()
            ->whereDate('created_at', '>=', now()->subWeeks(2))
            ->get();

        if ($todayAddedJobs->isEmpty()) {
            return;
        }

        User::query()->chunkById(20, function ($users) use ($todayAddedJobs) {
            $users->each(function($user) use ($todayAddedJobs){
                \Illuminate\Support\Facades\Notification::send($user, new NotifyUserAboutNewJobsNotifications($todayAddedJobs));
            });
        });
    }
}
