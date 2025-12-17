<?php

namespace App\Services;

use App\Models\User;
use Ihasan\LaravelMailerlite\Exceptions\CampaignCreateException;
use Ihasan\LaravelMailerlite\Exceptions\MailerLiteAuthenticationException;
use Ihasan\LaravelMailerlite\Facades\MailerLite;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class MailerLiteService
{
    public function __construct(
        private string $defaultFromName = 'Geezap Jobs',
        private string $defaultFromEmail = 'hello@geezap.com'
    ) {}

    /**
     * Send job notification campaign to user
     */
    public function sendJobNotificationCampaign(User $user, Collection $jobs): ?array
    {
        try {
            // Create campaign content
            $subject = $this->generateSubject($jobs->count());
            $htmlContent = $this->generateHtmlContent($user, $jobs);
            $plainContent = $this->generatePlainContent($user, $jobs);

            // Get or create user in MailerLite
            $this->ensureSubscriberExists($user);

            // Create and send campaign
            $campaign = MailerLite::campaigns()
                ->subject($subject)
                ->name("Job Digest for {$user->name} - ".now()->format('Y-m-d H:i'))
                ->from($this->defaultFromName, $this->defaultFromEmail)
                ->html($htmlContent)
                ->plain($plainContent)
                ->forFreePlan() // Ensure compatibility with all MailerLite plans
                ->create();

            if ($campaign) {
                // Send immediately to the specific user
                // Note: In a real implementation, you'd want to use segments or groups
                // For now, we'll log the campaign creation
                Log::info('MailerLite campaign created successfully', [
                    'user_id' => $user->id,
                    'campaign_id' => $campaign['id'] ?? null,
                    'campaign_name' => $campaign['name'] ?? null,
                    'job_count' => $jobs->count(),
                ]);

                return $campaign;
            }

            return null;

        } catch (CampaignCreateException $e) {
            Log::error('Failed to create MailerLite campaign', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            // If it's a plan restriction, we could fall back to regular Laravel mail
            if ($e->getCode() === 403) {
                Log::info('MailerLite plan restriction detected, consider upgrading plan or using fallback mail method');
            }

            return null;
        } catch (MailerLiteAuthenticationException $e) {
            Log::error('MailerLite authentication failed', [
                'error' => $e->getMessage(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Unexpected error creating MailerLite campaign', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Ensure subscriber exists in MailerLite
     */
    public function ensureSubscriberExists(User $user): ?array
    {
        try {
            // Try to find existing subscriber
            $subscriber = MailerLite::subscribers()
                ->email($user->email)
                ->find();

            if (! $subscriber) {
                // Create new subscriber
                $subscriber = MailerLite::subscribers()
                    ->email($user->email)
                    ->named($user->name)
                    ->withField('user_id', (string) $user->id)
                    ->withField('registered_at', $user->created_at->toDateString())
                    ->active()
                    ->subscribe();

                Log::info('Created MailerLite subscriber', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'subscriber_id' => $subscriber['id'] ?? null,
                ]);
            } else {
                Log::debug('MailerLite subscriber already exists', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'subscriber_id' => $subscriber['id'] ?? null,
                ]);
            }

            return $subscriber;

        } catch (\Exception $e) {
            Log::error('Failed to ensure MailerLite subscriber exists', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Generate email subject based on job count
     */
    private function generateSubject(int $jobCount): string
    {
        if ($jobCount === 1) {
            return 'New Job Match Found on Geezap';
        }

        return "{$jobCount} New Job Matches Found on Geezap";
    }

    /**
     * Generate HTML content for the email
     */
    private function generateHtmlContent(User $user, Collection $jobs): string
    {
        $jobsHtml = $jobs->map(function ($job) {
            $location = $job->is_remote ? 'Remote' : ($job->city ?? 'Location not specified');
            $salary = $this->formatSalary($job);
            $url = route('job.show', $job->slug);

            return "
                <div style='border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; margin-bottom: 16px; background: #ffffff;'>
                    <h3 style='margin: 0 0 8px 0; color: #1f2937;'>
                        <a href='{$url}' style='color: #3b82f6; text-decoration: none;'>{$job->job_title}</a>
                    </h3>
                    <p style='margin: 4px 0; color: #6b7280;'><strong>{$job->employer_name}</strong></p>
                    <p style='margin: 4px 0; color: #6b7280;'>📍 {$location}</p>
                    ".($salary ? "<p style='margin: 4px 0; color: #059669;'>💰 {$salary}</p>" : '')."
                    <p style='margin: 8px 0 0 0;'>
                        <a href='{$url}' style='background: #3b82f6; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; display: inline-block;'>View Job</a>
                    </p>
                </div>
            ";
        })->join('');

        return "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h1 style='color: #1f2937; border-bottom: 2px solid #3b82f6; padding-bottom: 16px;'>
                    Hello {$user->name}! 👋
                </h1>
                
                <p style='color: #374151; font-size: 16px; line-height: 1.6;'>
                    We found <strong>{$jobs->count()}</strong> new job".($jobs->count() > 1 ? 's' : '')." that match your preferences:
                </p>
                
                {$jobsHtml}
                
                <div style='border-top: 1px solid #e5e7eb; padding-top: 16px; margin-top: 24px; text-align: center; color: #6b7280;'>
                    <p>Want to update your job preferences? <a href='".route('preferences')."' style='color: #3b82f6;'>Update Preferences</a></p>
                    <p style='font-size: 14px;'>You're receiving this because you've enabled job notifications in your Geezap account.</p>
                </div>
            </div>
        ";
    }

    /**
     * Generate plain text content for the email
     */
    private function generatePlainContent(User $user, Collection $jobs): string
    {
        $jobsList = $jobs->map(function ($job) {
            $location = $job->is_remote ? 'Remote' : ($job->city ?? 'Location not specified');
            $salary = $this->formatSalary($job);
            $url = route('job.show', $job->slug);

            return "
{$job->job_title}
Company: {$job->employer_name}
Location: {$location}".($salary ? "\nSalary: {$salary}" : '')."
View Job: {$url}

---
            ";
        })->join('');

        return "
Hello {$user->name}!

We found {$jobs->count()} new job".($jobs->count() > 1 ? 's' : '')." that match your preferences:

{$jobsList}

Want to update your job preferences? Visit: ".route('preferences')."

You're receiving this because you've enabled job notifications in your Geezap account.

Best regards,
The Geezap Team
        ";
    }

    /**
     * Format salary information
     */
    private function formatSalary($job): ?string
    {
        if (! $job->min_salary && ! $job->max_salary) {
            return null;
        }

        if ($job->min_salary && $job->max_salary) {
            return '$'.number_format($job->min_salary).' - $'.number_format($job->max_salary);
        }

        if ($job->min_salary) {
            return 'From $'.number_format($job->min_salary);
        }

        if ($job->max_salary) {
            return 'Up to $'.number_format($job->max_salary);
        }

        return null;
    }

    /**
     * Add user to MailerLite group
     */
    public function addUserToGroup(User $user, string $groupIdOrName): bool
    {
        try {
            $result = MailerLite::subscribers()
                ->email($user->email)
                ->addToGroup($groupIdOrName);

            Log::info('Added user to MailerLite group', [
                'user_id' => $user->id,
                'email' => $user->email,
                'group' => $groupIdOrName,
            ]);

            return (bool) $result;

        } catch (\Exception $e) {
            Log::error('Failed to add user to MailerLite group', [
                'user_id' => $user->id,
                'email' => $user->email,
                'group' => $groupIdOrName,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get MailerLite groups for configuration
     */
    public function getGroups(): ?array
    {
        try {
            return MailerLite::groups()->all();
        } catch (\Exception $e) {
            Log::error('Failed to fetch MailerLite groups', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
