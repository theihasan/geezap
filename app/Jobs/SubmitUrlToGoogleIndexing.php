<?php

namespace App\Jobs;

use App\Services\GoogleIndexingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SubmitUrlToGoogleIndexing implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public string $url,
        public string $type = 'URL_UPDATED'
    ) {
        $this->onQueue('default');
    }

    public function handle(GoogleIndexingService $indexingService): void
    {
        try {
            $success = $indexingService->submitUrl($this->url, $this->type);
            
            if (!$success) {
                Log::warning('Google Indexing API submission failed', [
                    'url' => $this->url,
                    'type' => $this->type,
                    'attempt' => $this->attempts()
                ]);
                
                if ($this->attempts() < $this->tries) {
                    $this->release($this->backoff);
                }
            }
        } catch (\Exception $e) {
            Log::error('Google Indexing API job failed', [
                'url' => $this->url,
                'type' => $this->type,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts()
            ]);

            if ($this->attempts() < $this->tries) {
                $this->release($this->backoff);
            } else {
                $this->fail($e);
            }
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Google Indexing API job permanently failed', [
            'url' => $this->url,
            'type' => $this->type,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);
    }
}
