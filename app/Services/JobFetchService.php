<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\JobResponseDTO;
use App\Jobs\Store\StoreJobs;
use App\Models\ApiKey;
use App\Models\JobCategory;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class JobFetchService
{
    public function __construct(
        private readonly ApiKeyService $apiKeyService
    ) {}

    public function fetchJobsForCategory(JobCategory $category, int $totalPages): void
    {
        $apiKey = $this->apiKeyService->getAvailableApiKey();

        if (! $apiKey) {
            return;
        }

        $category->countries->each(function ($country) use ($apiKey, $category, $totalPages) {
            collect(range(1, $totalPages))->each(function ($page) use ($apiKey, $category, $country) {
                $this->fetchJobsForPage($apiKey, $category, $country, $page);
            });
        });
    }

    private function fetchJobsForPage(ApiKey $apiKey, JobCategory $category, $country, int $page): void
    {
        try {
            Log::info('Processing', [
                'category_id' => $category->id,
                'category_name' => $category->name,
                'page' => $page,
                'country' => $country->code,
            ]);

            $response = Http::job()->retry([100, 200])->get('/search', [
                'query' => $category->query_name,
                'page' => $page,
                'num_pages' => $category->num_page,
                'date_posted' => $category->timeframe,
                'country' => $country->code,
            ]);
            
            $this->apiKeyService->updateUsage($apiKey, $response);

            throw_if($response->status() === 429, RuntimeException::class, 'Rate limit exceeded');

            throw_if(! $response->successful(), RequestException::class, $response);

            if ($response->ok()) {
                $jobResponseDTO = JobResponseDTO::fromResponse(
                    $response->json(),
                    $category->id,
                    $category->category_image
                );
                StoreJobs::dispatch($jobResponseDTO);
            }

        } catch (RequestException|RuntimeException|\Exception $e) {
            Log::error('API request failed', [
                'category_id' => $category->id,
                'country' => $country->code,
                'page' => $page,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
