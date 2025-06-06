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
            return;
        }
        
        try {
            $apiKey = ApiKey::query()
                ->where('api_name', ApiName::JOB)
                ->where('request_remaining', '>', 0)
                ->orderBy('sent_request')
                ->first();

            if (!$apiKey) {
                Log::warning('No available API key with remaining requests');
                return;
            }

            $category = JobCategory::with('countries')->findOrFail($this->categoryId);

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
        $browser = new Browser();
        $maxConcurrentRequests = 10;
        $pendingPromises = 0;
        $promises = [];

        foreach ($category->countries as $country) {
            for ($page = 1; $page <= $this->totalPages; $page++) {
                try {
                    if ($this->batch() && $this->batch()->cancelled()) {
                        return;
                    }
                    
                    Log::info('Processing', [
                        'category_id' => $category->id,
                        'category_name' => $category->name,
                        'page' => $page,
                        'country' => $country->code,
                        'batch_id' => $this->batch() ? $this->batch()->id : null,
                    ]);

                    $url = config('services.job_api.url').'/search';
                    $query = [
                        'query' => $category->query_name,
                        'page' => $page,
                        'num_pages' => $category->num_page,
                        'date_posted' => $category->timeframe,
                        'country' => $country->code
                    ];

                    $makeRequest = function () use ($browser, $url, $query, $apiKey, $category, $country, $page) {
                        return $browser->get($url . '?' . http_build_query($query), [
                            'Authorization' => 'Bearer ' . $apiKey->api_key,
                            'Accept' => 'application/json'
                        ])->then(function (ResponseInterface $response) use($apiKey, $category, $country, $page) {
                            $statusCode = $response->getStatusCode();
                            $headers = $response->getHeaders();
                            $body = (string) $response->getBody();

                            if ($statusCode === 429) {
                                Log::warning('Rate limit exceeded', [
                                    'category_id' => $category->id,
                                    'country' => $country->code,
                                    'page' => $page
                                ]);
                                return;
                            }

                            if ($statusCode === 200) {
                                $responseData = json_decode($body, true);
                                $this->updateApiKeyUsage(
                                    $apiKey, 
                                    $headers['X-RateLimit-Requests-Remaining'][0] ?? null,
                                    $headers['X-RateLimit-Reset'][0] ?? null
                                );
                                $jobResponseDTO = JobResponseDTO::fromResponse(
                                    $responseData,
                                    $category->id,
                                    $category->category_image
                                );
                                StoreJobs::dispatch($jobResponseDTO);
                            }
                        });
                    };

                    function (Exception $e) use($category, $country, $page) {
                        Log::error('API request failed', [
                            'category_id' => $category->id,
                            'country' => $country->code,
                            'page' => $page,
                            'error' => $e->getMessage(),
                        ]);
                    };

                    if ($pendingPromises >= $maxConcurrentRequests) {
                        $batch = array_splice($promises, 0, $maxConcurrentRequests);
                        all($batch)->then(function () use (&$pendingPromises, &$promises, $maxConcurrentRequests) {
                            $pendingPromises -= $maxConcurrentRequests;
                        });

                        Loop::run();

                        $promises[] = $makeRequest();
                        $pendingPromises++;
                    }

                    if (count($promises) > 0) {
                        \React\Promise\all($promises)->then(function () use (&$pendingPromises, &$promises) {
                           Log::info('Batch processed', [
                               'batch_id' => $this->batch()? $this->batch()->id : null,
                               'pending_promises' => $pendingPromises,
                               'promises' => count($promises),
                           ]);
                        });

                        Loop::run();
                    }

                }
                catch (Exception $e) {
                    Log::error('API request failed', [
                        'category_id' => $category->id,
                        'country' => $country->code,
                        'page' => $page,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }
    
    /**
     * Update API key usage information
     */
    private function updateApiKeyUsage(ApiKey $apiKey, $response): void
    {
        DB::table('api_keys')
            ->where('id', $apiKey->id)
            ->update([
                'request_remaining' => $response->header('X-RateLimit-Requests-Remaining'),
                'rate_limit_reset' => Carbon::createFromTimestamp($response->header('X-RateLimit-Reset')),
                'sent_request' => DB::raw('sent_request + 1'),
            ]);
    }
}
