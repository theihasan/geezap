<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Country;
use App\Models\JobListing;
use App\Enums\EmailFrequency;
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
        
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        User::query()->chunkById(20, function ($users) {
            $users->each(function(User $user) {
                if (!$user->preferences) {
                    logger()->debug("User has no country or preferences", );
                    return;
                }
                
                if (!$user->preferences->email_notifications_enabled) {
                    logger()->debug("User has email notifications disabled", ['user_id' => $user->id]);
                    return;
                }
                
               // $shouldSendEmail = $this->shouldSendEmailBasedOnFrequency($user);
                
                // if (!$shouldSendEmail) {
                //     logger()->debug("Skipping email based on frequency setting", [
                //         'user_id' => $user->id,
                //         'frequency' => $user->preferences->email_frequency
                //     ]);
                //     return;
                // }
                
                $jobQuery = JobListing::query()
                    ->whereDate('created_at', '>=', now()->subWeeks(4));
                
                if (!empty($user->preferences->preferred_job_categories_id)) {
                    $jobQuery->whereIn('job_category', $user->preferences->preferred_job_categories_id);
                }
                
                if (!empty($user->preferences->preferred_regions_id)) {
                    $countryCodes = Country::whereIn('id', $user->preferences->preferred_regions_id)
                        ->pluck('code')
                        ->toArray();
                    
                    if (!empty($countryCodes)) {
                        $jobQuery->whereIn('country', $countryCodes);
                    }
                }
                
                if (!empty($user->preferences->preferred_job_types)) {
                    $jobQuery->whereIn('employment_type', $user->preferences->preferred_job_types);
                }
                
                if ($user->preferences->remote_only) {
                    $jobQuery->where('is_remote', true);
                }
                
                if ($user->preferences->min_salary) {
                    $jobQuery->where('max_salary', '>=', $user->preferences->min_salary);
                }
                
                if ($user->preferences->max_salary) {
                    $jobQuery->where('min_salary', '<=', $user->preferences->max_salary);
                }
                
                $matchingJobs = $jobQuery->inRandomOrder()
                    ->limit(20)
                    ->get();

                if ($matchingJobs->isEmpty()) {
                    logger()->info('No matching jobs found for user', ['user_id' => $user->id]);
                    return;
                }

                Notification::send($user, new NotifyUserAboutNewJobsNotifications($matchingJobs));
                
                Log::info("Sent job notification to user", [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'frequency' => $user->preferences->email_frequency,
                    'user_name' => $user->name,
                    'job_count' => $matchingJobs->count(),
                    'preferences_applied' => true
                ]);
            });
        });
    }
    
    /**
     * Determine if an email should be sent based on the user's frequency preference
     */
    private function shouldSendEmailBasedOnFrequency(User $user): bool
    {
        if (!$user->preferences || !$user->preferences->email_frequency) {
            return false;
        }
        
        $frequency = $user->preferences->email_frequency;
        $now = now();
        
        $key = 'last_email_sent_' . $user->id;
        $lastSent = cache()->get($key);
        
        if (!$lastSent) {
            cache()->put($key, $now, 60 * 24 * 30); 
            return true;
        }
        
        $lastSentTime = $lastSent;
        
        logger()->debug("Time difference check", [
            'user_id' => $user->id,
            'frequency' => $frequency,
            'last_sent' => $lastSentTime->toDateTimeString(),
            'now' => $now->toDateTimeString(),
            'diff_days' => $now->diffInDays($lastSentTime, false), 
            'diff_weeks' => $now->diffInWeeks($lastSentTime, false),
            'diff_months' => $now->diffInMonths($lastSentTime, false)
        ]);
        
        switch ($frequency) {
            case EmailFrequency::DAILY->value:
                $shouldSend = abs($now->diffInDays($lastSentTime, false)) >= 1;
                break;
            case EmailFrequency::WEEKLY->value:
                $shouldSend = abs($now->diffInDays($lastSentTime, false)) >= 7;
                break;
            case EmailFrequency::MONTHLY->value:
                $shouldSend = abs($now->diffInDays($lastSentTime, false)) >= 30; 
                break;
            default:
                $shouldSend = false;
        }
        
        if ($shouldSend) {
            cache()->put($key, $now, 60 * 24 * 30);
        }
        
        return $shouldSend;
    }
}
