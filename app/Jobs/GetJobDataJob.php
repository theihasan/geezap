<?php

namespace App\Jobs;

use App\DTO\JobResponseDTO;
use App\Jobs\Store\StoreJobs;
use App\Models\ApiKey;
use App\Models\Country;
use App\Models\JobCategory;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use RuntimeException;

abstract class GetJobDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public array $backoff = [60];

    public int $timeout = 3600;
    public int $maxExceptions = 1;

    public function __construct(
        protected int $categoryId,
        protected int $totalPages,
        protected bool $isLastCategory
    ) {
        logger('Job constructed', [
            'job' => get_class($this),
            'category_id' => $categoryId,
            'total_pages' => $totalPages,
            'is_last' => $isLastCategory
        ]);
    }


    public function handle(): void
    {
        try {
            logger('Finding API key for '.$this->getApiName());
            $apiKey = $this->getApiKey();

            logger('Finding category', ['category_id' => $this->categoryId]);
            $category = JobCategory::with('countries')->findOrFail($this->categoryId);
            logger('Category found in abstract class', ['category_id' => $category]);
            logger('Starting job execution', [
                'api_key_found' => (bool) $apiKey,
                'category_found' => (bool) $category,
                'countries_count' => $category->countries->count()
            ]);

            $this->fetchAndStoreJobs($apiKey, $category);
        } catch (Exception $e) {
            Log::error('Error on job fetching', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'job_class' => get_class($this),
                'api_name' => $this->getApiName()
            ]);
        }
    }

    abstract protected function getApiName(): string;

    protected function getApiKey(): ApiKey
    {
        $apiKey = ApiKey::query()
            ->where('api_name', $this->getApiName())
            ->where('request_remaining', '>', 0)
            ->orderBy('sent_request')
            ->first();

        if (! $apiKey) {
            logger('No API key found or no requests remaining', [
                'api_name' => $this->getApiName()
            ]);
            throw new RuntimeException('No valid API key found for '.$this->getApiName());
        }

        return $apiKey;
    }

    protected function fetchAndStoreJobs(ApiKey $apiKey, JobCategory $category): void
    {
        foreach ($category->countries as $country) {
            for ($page = 1; $page <= $this->totalPages; $page++) {
                try {
                    $this->logProcessing($category, $page, $country->code);

                    $responseData = $this->makeApiRequest($apiKey, $category, $country, $page);
                    $jobResponseDTO = $this->transformResponseToJobDTO($responseData, $category->id,
                        $category->category_image);

                    StoreJobs::dispatch($jobResponseDTO);

                    if ($this->isLastCategory && $page === $this->totalPages && $country->is($category->countries->last())) {
                        return;
                    }
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }
    }

    protected function logProcessing(JobCategory $category, int $page, string $countryCode): void
    {
        logger('Processing', [
            'category_id' => $category->id,
            'category_name' => $category->name,
            'page' => $page,
            'country' => $countryCode
        ]);
    }

    abstract protected function makeApiRequest(
        ApiKey $apiKey,
        JobCategory $category,
        Country $countryCode,
        int $page
    ): array;

    abstract protected function transformResponseToJobDTO(
        array $responseData,
        int $categoryId,
        string $categoryImage
    ): JobResponseDTO;

}
