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
        $category->countries->each(function ($country) use ($category, $totalPages) {
            $this->fetchJobsForCountry($category, $country, $totalPages);
        });
    }

    public function fetchJobsForCountry(JobCategory $category, $country, int $totalPages): void
    {
        collect(range(1, $totalPages))->each(function ($page) use ($category, $country) {
            $apiKey = $this->apiKeyService->getAvailableApiKey();

            if (! $apiKey) {
                Log::warning('No available API key for request', [
                    'category_id' => $category->id,
                    'country' => $country->code,
                    'page' => $page,
                ]);

                return;
            }

            $this->fetchJobsForPage($apiKey, $category, $country, $page);
        });
    }

    private function fetchJobsForPage(ApiKey $apiKey, JobCategory $category, $country, int $page): void
    {
        try {
            Log::info('Processing job fetch', [
                'category_id' => $category->id,
                'category_name' => $category->name,
                'page' => $page,
                'country' => $country->code,
                'api_key_id' => $apiKey->id,
            ]);

            Log::debug('Using API key', [
                'api_key_id' => $apiKey->id,
                'api_key_preview' => substr($apiKey->api_key, 0, 8).'...',
                'request_remaining' => $apiKey->request_remaining,
                'sent_request' => $apiKey->sent_request,
            ]);

            $response = Http::withHeaders([
                'X-RapidAPI-Host' => 'jsearch.p.rapidapi.com',
                'X-RapidAPI-Key' => $apiKey->api_key,
            ])
                ->baseUrl('https://jsearch.p.rapidapi.com')
                ->retry([100, 200])
                ->get('/search', [
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

                Log::info('Jobs queued for storage', [
                    'job_count' => count($jobResponseDTO->data),
                    'category_id' => $category->id,
                    'page' => $page,
                ]);
            }

        } catch (RequestException|RuntimeException|\Exception $e) {
            Log::error('API request failed', [
                'category_id' => $category->id,
                'country' => $country->code,
                'page' => $page,
                'api_key_id' => $apiKey->id,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);
        }
    }
}
