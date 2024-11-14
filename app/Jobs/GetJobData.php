<?php

namespace App\Jobs;

use App\DTO\JobResponseDTO;
use App\Enums\ApiName;
use App\Exceptions\ApiKeyNotFoundException;
use App\Jobs\Store\StoreJobs;
use App\Models\ApiKey;
use App\Models\JobCategory;
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

    public function handle(): void
    {
        try {
            $apiKey = ApiKey::query()
                ->where('api_name', ApiName::JOB)
                ->orderByDesc('request_remaining')
                ->first();

            ApiKeyNotFoundException::validateApiKey($apiKey);

            $categories = JobCategory::all();
            foreach ($categories as $category) {
                $this->fetchAndStoreJobs($apiKey, $category);
            }

        } catch (ApiKeyNotFoundException|\Exception $e) {
            Log::error('Error on job fetching', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }


    protected function fetchAndStoreJobs($apiKey, JobCategory $category): void
    {
        for ($page = 1; $page <= $category->page; $page++) {
            $response = Http::job()->retry([100, 200])->get('/search', [
                'query' => $category->query_name,
                'page' => $page,
                'num_pages' => $category->num_page,
                'date_posted' => $category->timeframe,
                'api_key_id' => $apiKey->id,
            ]);

            if ($response->ok()) {
                $jobResponseDTO = JobResponseDTO::fromResponse(
                    $response->json(),
                    $category->id,
                    $category->category_image
                );
                StoreJobs::dispatch($jobResponseDTO);
            } else {
                Log::error($response['message']);
            }

            DB::table('api_keys')
                ->where('id', $apiKey->id)
                ->update(['request_remaining' => $response->header('X-RateLimit-Requests-Remaining')]);
        }
    }
}
