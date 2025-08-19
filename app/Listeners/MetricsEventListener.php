<?php

namespace App\Listeners;

use App\Services\MetricsService;

use App\Events\ExceptionHappenEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Notifications\Events\NotificationSent;

class MetricsEventListener
{
    public function __construct(private MetricsService $metricsService)
    {
    }

    /**
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    public function handleUserRegistered(Registered $event): void
    {
        $this->metricsService->recordUserRegistration();
    }

    public function handleUserLogin(Login $event): void
    {
        $event->user->touch('last_login_at');
    }



    /**
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    public function handleExceptionHappen(ExceptionHappenEvent $event): void
    {
        $this->metricsService->recordException(
            'application',
            get_class($event->exception),
            $event->exception->getFile()
        );
    }

    /**
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    public function handleJobFailed(JobFailed $event): void
    {
        $this->metricsService->recordException(
            'job_failure',
            $event->job->resolveName(),
            'queue'
        );
    }

    public function handleMessageSent(MessageSent $event): void
    {
        $mailClass = get_class($event->message);
        $this->metricsService->recordEmailSent($mailClass, 'sent');
    }

    public function handleNotificationSent(NotificationSent $event): void
    {
        $channel = $event->channel;
        $this->metricsService->recordNotificationSent($channel, 'sent');
    }
}
