<?php

namespace App\Jobs\Store;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class AddSubscriberToMailerLite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 4;

    public $backoff = [30, 45, 60];

    /**
     * Create a new job instance.
     */
    public function __construct(public $email)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = Http::mailerlite()->post('api/subscribers', [
            'email' => $this->email,
            'groups' => config('mailerlite.group_id'),
        ]);
    }
}
