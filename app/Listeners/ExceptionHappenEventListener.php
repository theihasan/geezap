<?php

namespace App\Listeners;

use App\Events\ExceptionHappenEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ExceptionHappenEventListener
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
    public function handle(ExceptionHappenEvent $event): void
    {
        if (config('app.exception_notification_mail')){
            Mail::to(config('app.exception_notification_mail'))
                ->send(new \App\Mail\ExceptionHappenMail($event->exception));
        }
    }
}
