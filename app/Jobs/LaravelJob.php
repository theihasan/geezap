<?php

namespace App\Jobs;

use App\Constants\QueueName;
use App\Jobs\Store\StoreJobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LaravelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 4;

    public $backoff = [30, 45, 60];

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }
    public $queue = QueueName::JOBS;
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $apiKey = DB::table('api_keys')
        ->where('provider', '=', 'job')
        ->orderByDesc('request_remaining')
        ->first();
        if ($apiKey->request_count > 0) {
            for ($page = 1; $page <= 2; $page++) {
                $response = Http::job()->retry([100, 200])->get('/search', [
                    'query' => config('job-fetch.laravel_search_query'),
                    'page' => $page,
                    'num_pages' => 20,
                    'date_posted' => 'week',
                    'api_key_id' => $apiKey->id,
                ]);

                if ($response->ok()) {
                    StoreJobs::dispatch($response->json(), 'Laravel');

                    DB::table('api_keys')
                        ->where('id', $apiKey->id)
                        ->update(['request_count' => $response->header('X-RateLimit-Requests-Remaining')]);
                } else {
                    Log::error($response['message']);
                    DB::table('api_keys')
                        ->where('id', $apiKey->id)
                        ->update(['request_count' => $response->header('X-RateLimit-Requests-Remaining')]);

                    continue;
                }
            }
        }
    }
}
