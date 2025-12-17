<?php

namespace App\Jobs;

use App\Enums\EmailFrequency;
use App\Models\Country;
use App\Models\JobListing;
use App\Models\User;
use App\Notifications\NotifyUserAboutNewJobsNotifications;
use App\Services\MailerLiteService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifyUserAboutNewJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $maxExceptions = 3;

    public int $timeout = 3600;

    public array $backoff = [60, 120, 180];

    /**
     * Create a new job instance.
     */
    public function __construct(
        private bool $useMailerLite = true,
        private bool $fallbackToNotification = true
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        User::query()->chunkById(20, function ($users) {
            $users->each(function (User $user) {
                if (! $user->preferences) {
                    logger()->debug('User has no country or preferences');

                    return;
                }

                if (! $user->preferences->email_notifications_enabled) {
                    logger()->debug('User has email notifications disabled', ['user_id' => $user->id]);

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

                if (! empty($user->preferences->preferred_job_categories_id)) {
                    $jobQuery->whereIn('job_category', $user->preferences->preferred_job_categories_id);
                }

                if (! empty($user->preferences->preferred_regions_id)) {
                    $countryCodes = Country::whereIn('id', $user->preferences->preferred_regions_id)
                        ->pluck('code')
                        ->toArray();

                    if (! empty($countryCodes)) {
                        $jobQuery->whereIn('country', $countryCodes);
                    }
                }

                if (! empty($user->preferences->preferred_job_types)) {
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

                $notificationSent = false;

                // Try MailerLite first if enabled
                if ($this->useMailerLite) {
                    try {
                        $mailerLiteService = app(MailerLiteService::class);
                        $campaign = $mailerLiteService->sendJobNotificationCampaign($user, $matchingJobs);

                        if ($campaign) {
                            $notificationSent = true;
                            Log::info('Sent job notification via MailerLite', [
                                'user_id' => $user->id,
                                'email' => $user->email,
                                'frequency' => $user->preferences->email_frequency,
                                'user_name' => $user->name,
                                'job_count' => $matchingJobs->count(),
                                'campaign_id' => $campaign['id'] ?? null,
                                'method' => 'mailerlite',
                                'preferences_applied' => true,
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::warning('MailerLite notification failed, will try fallback', [
                            'user_id' => $user->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }

                // Fallback to Laravel notification if MailerLite failed or disabled
                // if (! $notificationSent && $this->fallbackToNotification) {
                //     Notification::send($user, new NotifyUserAboutNewJobsNotifications($matchingJobs));

                //     Log::info('Sent job notification via Laravel notification', [
                //         'user_id' => $user->id,
                //         'email' => $user->email,
                //         'frequency' => $user->preferences->email_frequency,
                //         'user_name' => $user->name,
                //         'job_count' => $matchingJobs->count(),
                //         'method' => 'laravel_notification',
                //         'preferences_applied' => true,
                //     ]);

                //     $notificationSent = true;
                // }

                if (! $notificationSent) {
                    Log::error('Failed to send job notification via any method', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'job_count' => $matchingJobs->count(),
                    ]);
                }
            });
        });
    }

    /**
     * Create job instance with MailerLite enabled
     */
    public static function withMailerLite(): self
    {
        return new self(useMailerLite: true, fallbackToNotification: true);
    }

    /**
     * Create job instance with only Laravel notifications
     */
    public static function withoutMailerLite(): self
    {
        return new self(useMailerLite: false, fallbackToNotification: true);
    }

    /**
     * Create job instance with MailerLite only (no fallback)
     */
    public static function mailerLiteOnly(): self
    {
        return new self(useMailerLite: true, fallbackToNotification: false);
    }

    /**
     * Determine if an email should be sent based on the user's frequency preference
     */
    private function shouldSendEmailBasedOnFrequency(User $user): bool
    {
        if (! $user->preferences || ! $user->preferences->email_frequency) {
            return false;
        }

        $frequency = $user->preferences->email_frequency;
        $now = now();

        $key = 'last_email_sent_'.$user->id;
        $lastSent = cache()->get($key);

        if (! $lastSent) {
            cache()->put($key, $now, 60 * 24 * 30);

            return true;
        }

        $lastSentTime = $lastSent;

        logger()->debug('Time difference check', [
            'user_id' => $user->id,
            'frequency' => $frequency,
            'last_sent' => $lastSentTime->toDateTimeString(),
            'now' => $now->toDateTimeString(),
            'diff_days' => $now->diffInDays($lastSentTime, false),
            'diff_weeks' => $now->diffInWeeks($lastSentTime, false),
            'diff_months' => $now->diffInMonths($lastSentTime, false),
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
