<?php

namespace App\Notifications;

use App\Models\JobListing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class NotifyUserAboutNewJobsNotifications extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 120;
    public $backoff = [60, 120];
    public int $maxExceptions = 2;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Collection $todayAddedJobs)
    {
    }

    public function onQueue($queue)
    {
        return 'digest';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Jobs This Week - Geezap Weekly Digest')
            ->view(
                'emails.weekly-digest',
                [
                    'user' => $notifiable,
                    'jobs' => $this->todayAddedJobs,
                    'jobCount' => $this->todayAddedJobs instanceof Collection
                        ? $this->todayAddedJobs->count()
                        : 1
                ]
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Jobs Available',
            'jobs' => $this->todayAddedJobs instanceof Collection
                ? $this->todayAddedJobs->map(fn($job) => [
                    'id' => $job->id,
                    'job_title' => $job->job_title,
                    'employer_name' => $job->employer_name,
                    'location' => $job->is_remote ? 'Remote' : $job->city,
                    'url' => route('job.show', $job->slug)
                ])->toArray()
                : [
                    'id' => $this->todayAddedJobs->id,
                    'job_title' => $this->todayAddedJobs->job_title,
                    'employer_name' => $this->todayAddedJobs->employer_name,
                    'location' => $this->todayAddedJobs->is_remote ? 'Remote' : $this->todayAddedJobs->city,
                    'url' => route('job.show', $this->todayAddedJobs->slug)
                ],
            'count' => $this->todayAddedJobs instanceof Collection
                ? $this->todayAddedJobs->count()
                : 1
        ];
    }

    public function viaConnections(): array
    {
        return [
            'mail' => 'database',
            'database' => 'sync',
        ];
    }
}
