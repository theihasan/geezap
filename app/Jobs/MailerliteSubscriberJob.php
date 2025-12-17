<?php

namespace App\Jobs;

use App\Models\User;
use Ihasan\LaravelMailerlite\Facades\MailerLite;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class MailerliteSubscriberJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected int $userId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::query()->findOrFail($this->userId);
        
        $subscriber = MailerLite::subscribers()
            ->email($user->email)
            ->find();
            
        if($subscriber) {
            MailerLite::subscribers()
                ->email($user->email)
                ->named($user->name)
                ->subscribe();
        }
    }
}
