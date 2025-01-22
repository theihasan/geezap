<?php

namespace App\Jobs;

use App\Models\ApiKey;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ResetAPIKeyLimit implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ApiKey::query()->chunk(10, function ($apiKeys) {
            $apiKeys->each(function ($apiKey) {
                $apiKey->update([
                    'request_remaining' => rand(10, 30),
                    'sent_request' => 0,
                ]);
            });
        });
    }
}
