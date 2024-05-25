<?php

namespace App\Jobs;

use App\Constants\ApiName;
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

class ReactJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 4;

    public array $backoff = [30, 45, 60];


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
        try {
            $apiKey = DB::table('api_keys')
                ->where('api_name', '=', ApiName::JOB)
                ->orderByDesc('request_remaining')
                ->first();
            if ($apiKey->request_remaining > 0) {
                for ($page = 1; $page <= 2; $page++) {
                    $response = Http::job()->get('/search', [
                        'query' => config('job-fetch.react_search_query'),
                        'page' => $page,
                        'num_pages' => 20,
                        'date_posted' => 'week',
                        'api_key_id' => $apiKey->id,
                    ]);
                    if ($response->ok()) {
                        StoreJobs::dispatch($response->json(), 'React');

                        DB::table('api_keys')
                            ->where('id', $apiKey->id)
                            ->update(['request_remaining' => $response->header('X-RateLimit-Requests-Remaining')]);
                    } else {
                        Log::error('Error fetching data from API'. $response->json('message'));
                    }
                }
            } else {
                Log::error('No request remaining for React API');
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

    }
}
