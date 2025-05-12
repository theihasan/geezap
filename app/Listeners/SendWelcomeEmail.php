<?php

namespace App\Listeners;

use App\Notifications\WelcomeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmail implements ShouldQueue
{
    use InteractsWithQueue;

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
    public function handle(Registered $event): void
    {
        $user = $event->user;

        try {
            Log::info('Sending welcome notification to: ' . $user->email);
            $user->notify(new WelcomeNotification());
            Log::info('Welcome notification sent successfully');
        } catch (\Exception $e) {
            Log::error('Failed to send welcome notification: ' . $e->getMessage());
        }
    }
}
