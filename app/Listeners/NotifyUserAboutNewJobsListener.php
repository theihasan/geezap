<?php

namespace App\Listeners;

use App\Events\NotifyUserAboutNewJobsEvent;
use App\Models\JobListing;
use App\Models\User;
use App\Notifications\NotifyUserAboutNewJobsNotifications;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotifyUserAboutNewJobsListener
{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotifyUserAboutNewJobsEvent $event): void
    {
        $todayAddedJobs = JobListing::query()->whereDate('created_at', today())->get();
        Notification::send(User::all(), new NotifyUserAboutNewJobsNotifications($todayAddedJobs));
    }
}
