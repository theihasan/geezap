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
use RuntimeException;

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
                // ->where(function($query) {
                //     $query->whereNull('rate_limit_reset')
                //         ->orWhere('rate_limit_reset', '>', Carbon::now());
                // })
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

                    $response = Http::job()->retry([100, 200])->get('/search', [
                        'query' => $category->query_name,
                        'page' => $page,
                        'num_pages' => $category->num_page,
                        'date_posted' => $category->timeframe,
                        'country' => $country->code
                    ]);

                    throw_if($response->status() === 429, new RuntimeException('Rate limit exceeded'));
                    throw_if(!$response->successful(), new RequestException($response));

                    $this->updateApiKeyUsage($apiKey, $response);

                    if ($response->ok()) {
                        $jobResponseDTO = JobResponseDTO::fromResponse(
                            $response->json(),
                            $category->id,
                            $category->category_image
                        );
                        StoreJobs::dispatch($jobResponseDTO);
                    }

                } catch (RequestException | RuntimeException | Exception $e) {
                    Log::error('API request failed', [
                        'category_id' => $category->id,
                        'country' => $country->code,
                        'page' => $page,
                        'error' => $e->getMessage(),
                    ]);
                    
                    continue;
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
                'updated_at' => now(),
            ]);
    }
}
