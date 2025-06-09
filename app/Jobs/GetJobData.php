<?php

declare(strict_types=1);

namespace App\Jobs;

use App\DTO\JobResponseDTO;
use App\Enums\ApiName;
use App\Exceptions\CountryNotFoundException;
use App\Jobs\Store\StoreJobs;
use App\Models\ApiKey;
use App\Models\JobCategory;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use React\EventLoop\Loop;
use React\Http\Browser;
use RuntimeException;

use function React\Promise\all;

class GetJobData implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public array $backoff = [60];
    public int $maxExceptions = 1;
    
    public function __construct(
        private readonly int $categoryId,
        private readonly int $totalPages,
        private readonly bool $isLastCategory
    ) {}

    public function handle(): void
    {
        if ($this->batch() && $this->batch()->cancelled()) {
            Log::info('Job cancelled due to batch cancellation', [
                'category_id' => $this->categoryId,
                'batch_id' => $this->batch() ? $this->batch()->id : null,
            ]);
            return;
        }
        
        try {
            Log::info('Starting job execution', [
                'category_id' => $this->categoryId,
                'total_pages' => $this->totalPages,
                'is_last_category' => $this->isLastCategory,
                'batch_id' => $this->batch() ? $this->batch()->id : null,
            ]);
            
            $apiKey = ApiKey::query()
                ->where('api_name', ApiName::JOB)
                ->where('request_remaining', '>', 0)
                ->where(function($query) {
                    $query->whereNull('rate_limit_reset')
                        ->orWhere('rate_limit_reset', '>', Carbon::now());
                })
                ->orderBy('sent_request')
                ->first();

            if (!$apiKey) {
                Log::warning('No available API key with remaining requests', [
                    'category_id' => $this->categoryId,
                ]);
                return;
            }
            
            Log::info('Found available API key', [
                'api_key_id' => $apiKey->id,
                'requests_remaining' => $apiKey->request_remaining,
                'category_id' => $this->categoryId,
            ]);

            $category = JobCategory::with('countries')->findOrFail($this->categoryId);
            
            Log::info('Found category with countries', [
                'category_id' => $category->id,
                'category_name' => $category->name,
                'countries_count' => $category->countries->count(),
                'country_codes' => $category->countries->pluck('code')->toArray(),
            ]);

            $this->fetchAndStoreJobs($apiKey, $category);

        } catch (ValidationException | InvalidArgumentException | CountryNotFoundException | ModelNotFoundException | Exception $e) {
            Log::error('Error on job fetching', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'category_id' => $this->categoryId,
            ]);
            
            $this->fail($e);
        }
    }

    protected function fetchAndStoreJobs($apiKey, JobCategory $category): void
    {
        Log::info('Starting fetchAndStoreJobs', [
            'category_id' => $category->id,
            'api_key_id' => $apiKey->id,
            'total_pages' => $this->totalPages,
            'countries_count' => $category->countries->count(),
        ]);
        
        $browser = new Browser();
        $maxConcurrentRequests = 5;
        $pendingPromises = 0;
        $promises = [];
        $baseUrl = config('services.job_api.url');
        
        Log::info('ReactPHP browser initialized', [
            'max_concurrent_requests' => $maxConcurrentRequests,
            'base_url' => $baseUrl,
        ]);

        foreach ($category->countries as $country) {
            for ($page = 1; $page <= $this->totalPages; $page++) {
                try {
                    if ($this->batch() && $this->batch()->cancelled()) {
                        Log::info('Batch cancelled during request preparation', [
                            'category_id' => $category->id,
                            'batch_id' => $this->batch()->id,
                        ]);
                        return;
                    }
                    
                    Log::info('Preparing request', [
                        'category_id' => $category->id,
                        'category_name' => $category->name,
                        'page' => $page,
                        'country' => $country->code,
                        'batch_id' => $this->batch() ? $this->batch()->id : null,
                    ]);

                    $url = $baseUrl . '/search';
                    $query = [
                        'query' => $category->query_name,
                        'page' => $page,
                        'num_pages' => $category->num_page,
                        'date_posted' => $category->timeframe,
                        'country' => $country->code
                    ];
                    
                    $fullUrl = $url . '?' . http_build_query($query);
                    Log::info('Request URL prepared', [
                        'url' => $fullUrl,
                        'category_id' => $category->id,
                        'country' => $country->code,
                        'page' => $page,
                    ]);

                    $headers = [
                        'X-RapidAPI-Key' => $apiKey->api_key,
                        'X-RapidAPI-Host' => 'jsearch.p.rapidapi.com',
                        'Accept' => 'application/json'
                    ];
                    
                    Log::info('Request headers prepared', [
                        'headers' => array_keys($headers),
                        'auth_header_present' => isset($headers['X-RapidAPI-Key']),
                        'category_id' => $category->id,
                        'country' => $country->code,
                        'page' => $page,
                    ]);

                    $makeRequest = function () use ($browser, $fullUrl, $headers, $apiKey, $category, $country, $page) {
                        Log::info('Making API request', [
                            'url' => $fullUrl,
                            'category_id' => $category->id,
                            'country' => $country->code,
                            'page' => $page,
                        ]);
                        
                        return $browser->get($fullUrl, $headers)
                            ->then(
                                function (ResponseInterface $response) use ($apiKey, $category, $country, $page, $fullUrl) {
                                    $statusCode = $response->getStatusCode();
                                    $headers = $response->getHeaders();
                                    $body = (string) $response->getBody();
                                    
                                    Log::info('API response received', [
                                        'status_code' => $statusCode,
                                        'headers' => array_keys($headers),
                                        'body_length' => strlen($body),
                                        'category_id' => $category->id,
                                        'country' => $country->code,
                                        'page' => $page,
                                        'url' => $fullUrl,
                                    ]);


                                    if ($statusCode === 429) {
                                        Log::warning('Rate limit exceeded', [
                                            'category_id' => $category->id,
                                            'country' => $country->code,
                                            'page' => $page,
                                            'headers' => $headers,
                                        ]);
                                        return;
                                    }

                                   
                                    if ($statusCode === 200) {
                                        Log::info('Successful API response', [
                                            'category_id' => $category->id,
                                            'country' => $country->code,
                                            'page' => $page,
                                            'rate_limit_remaining' => $headers['X-RateLimit-Requests-Remaining'][0] ?? 'unknown',
                                            'rate_limit_reset' => $headers['X-RateLimit-Reset'][0] ?? 'unknown',
                                        ]);
                                        
                                        $responseData = json_decode($body, true);
                                        
                                        if (json_last_error() !== JSON_ERROR_NONE) {
                                            Log::error('JSON decode error', [
                                                'error' => json_last_error_msg(),
                                                'category_id' => $category->id,
                                                'country' => $country->code,
                                                'page' => $page,
                                            ]);
                                            return;
                                        }
                                        
                                        Log::info('JSON decoded successfully', [
                                            'category_id' => $category->id,
                                            'country' => $country->code,
                                            'page' => $page,
                                            'data_keys' => array_keys($responseData),
                                        ]);
                                        
                                        $this->updateApiKeyUsage(
                                            $apiKey, 
                                            $headers['X-RateLimit-Requests-Remaining'][0] ?? null,
                                            $headers['X-RateLimit-Reset'][0] ?? null
                                        );
                                        
                                        try {
                                            $jobResponseDTO = JobResponseDTO::fromResponse(
                                                $responseData,
                                                $category->id,
                                                $category->category_image
                                            );
                                            
                                            Log::info('JobResponseDTO created', [
                                                'category_id' => $category->id,
                                                'country' => $country->code,
                                                'page' => $page,
                                                'jobs_count' => count($jobResponseDTO->jobs ?? []),
                                            ]);
                                            
                                            StoreJobs::dispatch($jobResponseDTO);
                                            
                                            Log::info('StoreJobs dispatched', [
                                                'category_id' => $category->id,
                                                'country' => $country->code,
                                                'page' => $page,
                                            ]);
                                        } catch (Exception $e) {
                                            Log::error('Error creating JobResponseDTO', [
                                                'message' => $e->getMessage(),
                                                'category_id' => $category->id,
                                                'country' => $country->code,
                                                'page' => $page,
                                            ]);
                                        }
                                    } else {
                                        Log::error('Unexpected status code', [
                                            'status_code' => $statusCode,
                                            'category_id' => $category->id,
                                            'country' => $country->code,
                                            'page' => $page,
                                            'body' => $body,
                                        ]);
                                    }
                                },
                                function (Exception $e) use ($category, $country, $page, $fullUrl) {
                                    Log::error('API request failed', [
                                        'message' => $e->getMessage(),
                                        'category_id' => $category->id,
                                        'country' => $country->code,
                                        'page' => $page,
                                        'url' => $fullUrl,
                                        'error_class' => get_class($e),
                                    ]);
                                }
                            );
                    };

                  
                    $promises[] = $makeRequest();
                    $pendingPromises++;
                    
                    Log::info('Request added to queue', [
                        'category_id' => $category->id,
                        'country' => $country->code,
                        'page' => $page,
                        'pending_promises' => $pendingPromises,
                        'promises_count' => count($promises),
                    ]);

                  
                    if ($pendingPromises >= $maxConcurrentRequests) {
                        Log::info('Processing batch of requests', [
                            'batch_size' => $maxConcurrentRequests,
                            'total_promises' => count($promises),
                            'category_id' => $category->id,
                        ]);
                        
                        $batch = array_splice($promises, 0, $maxConcurrentRequests);
                        all($batch)->then(function () use (&$pendingPromises, $maxConcurrentRequests, $category) {
                            $pendingPromises -= $maxConcurrentRequests;
                            Log::info('Batch completed', [
                                'remaining_promises' => $pendingPromises,
                                'category_id' => $category->id,
                            ]);
                        });

                        Log::info('Running event loop', [
                            'category_id' => $category->id,
                        ]);
                        Loop::run();
                        Log::info('Event loop completed', [
                            'category_id' => $category->id,
                        ]);
                    }
                } catch (Exception $e) {
                    Log::error('Exception during request preparation', [
                        'message' => $e->getMessage(),
                        'category_id' => $category->id,
                        'country' => $country->code,
                        'page' => $page,
                        'error_class' => get_class($e),
                    ]);
                }
            }
        }

     
        if (count($promises) > 0) {
            Log::info('Processing remaining promises', [
                'promises_count' => count($promises),
                'category_id' => $category->id,
            ]);
            
            all($promises)->then(function () use ($category) {
                Log::info('All remaining promises completed', [
                    'category_id' => $category->id,
                    'batch_id' => $this->batch() ? $this->batch()->id : null,
                ]);
            });

            Log::info('Running final event loop', [
                'category_id' => $category->id,
            ]);
            Loop::run();
            Log::info('Final event loop completed', [
                'category_id' => $category->id,
            ]);
        }
        
        Log::info('fetchAndStoreJobs completed', [
            'category_id' => $category->id,
        ]);
    }
    
    /**
     * Update API key usage information
     */
    private function updateApiKeyUsage(ApiKey $apiKey, ?string $requestsRemaining, ?string $rateLimitReset): void
    {
        if ($requestsRemaining === null || $rateLimitReset === null) {
            Log::warning('Missing rate limit headers for API key update', [
                'api_key_id' => $apiKey->id,
                'requests_remaining' => $requestsRemaining,
                'rate_limit_reset' => $rateLimitReset,
            ]);
            return;
        }
        
        Log::info('Updating API key usage', [
            'api_key_id' => $apiKey->id,
            'requests_remaining' => $requestsRemaining,
            'rate_limit_reset' => $rateLimitReset,
        ]);
        
        try {
            DB::table('api_keys')
                ->where('id', $apiKey->id)
                ->update([
                    'request_remaining' => $requestsRemaining,
                    'rate_limit_reset' => Carbon::createFromTimestamp((int) $rateLimitReset),
                    'sent_request' => DB::raw('sent_request + 1'),
                ]);
                
            Log::info('API key usage updated successfully', [
                'api_key_id' => $apiKey->id,
            ]);
        } catch (Exception $e) {
            Log::error('Failed to update API key usage', [
                'message' => $e->getMessage(),
                'api_key_id' => $apiKey->id,
            ]);
        }
    }
}
