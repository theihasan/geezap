<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\JobListing;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotifyUserAboutNewJobsNotifications;

class NotifyUserAboutNewJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $maxExceptions = 3;
    public int $timeout = 3600;
    public array $backoff = [60,120,180];

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
        User::query()->chunkById(20, function ($users) {
            $users->each(function(User $user) {
                if (!$user->country) {
                    return;
                }

                $countryJobs = JobListing::query()
                    ->whereDate('created_at', '>=', now()->subWeeks(2))
                    ->where('country', $user->country)
                    ->inRandomOrder()
                    ->limit(6)
                    ->get();

                if ($countryJobs->isEmpty()) {
                    return;
                }

                Notification::send($user, new NotifyUserAboutNewJobsNotifications($countryJobs));
            });
        });
    }
}
