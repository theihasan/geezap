<?php
namespace App\Jobs;

use App\Constants\ApiName;
use App\Jobs\Store\StoreJobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetJobData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 4;
    public array $backoff = [30, 45, 60];

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        try {
            $apiKey = DB::table('api_keys')
                ->where('api_name', '=', ApiName::JOB)
                ->orderByDesc('request_remaining')
                ->first();

            if ($apiKey && $apiKey->request_remaining > 0) {
                $categories = config('geezap');

                foreach ($categories as $category => $config) {
                    $this->fetchAndStoreJobs($apiKey, $config, $category);
                }
            } else {
                Log::error('No request remaining for Job API');
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    protected function fetchAndStoreJobs($apiKey, $config, $category): void
    {
        $searchQuery = $config['query'];
        $numPages = $config['num_pages'];
        $datePosted = $config['date_posted'];
        $totalPages = $config['page'];
        $categoryImage = $config['category_image'];

        for ($page = 1; $page <= $totalPages; $page++) {
            $response = Http::job()->retry([100, 200])->get('/search', [
                'query' => $searchQuery,
                'page' => $page,
                'num_pages' => $numPages,
                'date_posted' => $datePosted,
                'api_key_id' => $apiKey->id,
            ]);

            if ($response->ok()) {
                StoreJobs::dispatch($response->json(), $category, $categoryImage);
            } else {
                Log::error($response['message']);
            }

            DB::table('api_keys')
                ->where('id', $apiKey->id)
                ->update(['request_remaining' => $response->header('X-RateLimit-Requests-Remaining')]);
        }
    }
}
