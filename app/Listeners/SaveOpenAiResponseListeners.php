<?php

namespace App\Listeners;

use App\Events\CoverLetterGenerated;
use App\Models\Airesponse;

class SaveOpenAiResponseListeners
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
    public function handle(CoverLetterGenerated $event): void
    {
        Airesponse::query()->firstOrCreate([
            'user_id' => $event->user->id,
            'job_id' => $event->jobId,
            'response' => $event->coverLetter['response']
        ]);
    }
}
