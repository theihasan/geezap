<?php

declare(strict_types=1);

namespace App\Jobs\Store;

use App\DTO\JobDTO;
use App\DTO\JobResponseDTO;
use App\Models\JobApplyOption;
use App\Models\JobListing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 4;

    public $backoff = [45, 90, 180];

    public function __construct(public JobResponseDTO $responseDTO) {}

    public function handle(): void
    {
        try {
            // Process jobs in chunks to prevent memory exhaustion
            $chunkSize = 50; // Process 50 jobs at a time
            $jobDataChunks = array_chunk($this->responseDTO->data, $chunkSize);

            foreach ($jobDataChunks as $chunkIndex => $jobDataChunk) {
                $this->processJobChunk($jobDataChunk);

                // Force garbage collection after each chunk
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }

                // Log memory usage for monitoring
                $memoryUsage = memory_get_usage(true);
                $memoryPeak = memory_get_peak_usage(true);
                logger()->debug('StoreJobs memory usage', [
                    'chunk' => $chunkIndex + 1,
                    'memory_usage_mb' => round($memoryUsage / 1024 / 1024, 2),
                    'memory_peak_mb' => round($memoryPeak / 1024 / 1024, 2),
                ]);

                // If memory usage is getting high, break and retry later
                if ($memoryUsage > 100 * 1024 * 1024) { // 100MB threshold
                    logger()->warning('StoreJobs memory threshold exceeded, releasing job for retry', [
                        'memory_usage_mb' => round($memoryUsage / 1024 / 1024, 2),
                    ]);
                    $this->release(60);

                    return;
                }
            }

        } catch (\PDOException|\Exception $e) {
            $errorMessage = is_array($e->getMessage()) ? json_encode($e->getMessage()) : $e->getMessage();
            logger()->debug('Exception sent from store job class', ['error' => $errorMessage]);
            $this->release(60);
        }
    }

    /**
     * Process a chunk of job data
     */
    private function processJobChunk(array $jobDataChunk): void
    {
        $jobListings = [];
        $applyOptionsToProcess = [];

        // First pass: Create/update job listings and collect apply options
        foreach ($jobDataChunk as $jobData) {
            $jobData['job_category'] = $this->responseDTO->jobCategory;
            $jobData['category_image'] = $this->responseDTO->categoryImage;

            $jobDTO = JobDTO::fromArray($jobData);

            $existingJob = $this->findExistingJob($jobDTO);

            if (! $existingJob) {
                $jobListing = JobListing::query()->create($jobDTO->toArray());
            } else {
                $jobListing = $existingJob;
                // Update existing job with latest data
                $jobListing->update($jobDTO->toArray());
            }

            $jobListings[] = $jobListing;

            // Collect apply options for batch processing
            if ($jobDTO->applyOptions && is_array($jobDTO->applyOptions)) {
                foreach ($jobDTO->applyOptions as $option) {
                    $applyOptionsToProcess[] = [
                        'job_listing_id' => $jobListing->id,
                        'publisher' => $option['publisher'],
                        'apply_link' => $option['apply_link'],
                        'is_direct' => $option['is_direct'],
                    ];
                }
            }
        }

        // Second pass: Batch process apply options to avoid N+1 queries
        if (! empty($applyOptionsToProcess)) {
            $this->batchProcessApplyOptions($applyOptionsToProcess);
        }

        // Clear variables to free memory
        unset($jobListings, $applyOptionsToProcess, $jobDataChunk);
    }

    /**
     * Find existing job using multiple criteria to prevent duplicates
     */
    private function findExistingJob(JobDTO $jobDTO): ?JobListing
    {
        $query = JobListing::query();

        if ($jobDTO->jobId) {
            $existingJob = $query->where('job_id', $jobDTO->jobId)->first();
            if ($existingJob) {
                return $existingJob;
            }
        }

        $query = JobListing::query()
            ->where('job_title', $jobDTO->jobTitle)
            ->where('employer_name', $jobDTO->employerName);

        if ($jobDTO->city) {
            $query->where('city', $jobDTO->city);
        }

        if ($jobDTO->state) {
            $query->where('state', $jobDTO->state);
        }

        if ($jobDTO->country) {
            $query->where('country', $jobDTO->country);
        }

        if ($jobDTO->publisher) {
            $query->where('publisher', $jobDTO->publisher);
        }

        return $query->first();
    }

    /**
     * Batch process apply options to avoid N+1 queries
     */
    private function batchProcessApplyOptions(array $applyOptionsData): void
    {
        if (empty($applyOptionsData)) {
            return;
        }

        // Process apply options in smaller chunks to prevent memory issues
        $chunkSize = 100;
        $chunks = array_chunk($applyOptionsData, $chunkSize);

        foreach ($chunks as $chunk) {
            $this->processApplyOptionsChunk($chunk);
        }
    }

    /**
     * Process a chunk of apply options
     */
    private function processApplyOptionsChunk(array $applyOptionsData): void
    {
        // Get all job listing IDs
        $jobListingIds = array_unique(array_column($applyOptionsData, 'job_listing_id'));

        // Load existing apply options for all job listings in one query
        $existingOptions = JobApplyOption::whereIn('job_listing_id', $jobListingIds)
            ->get()
            ->groupBy('job_listing_id')
            ->map(function ($options) {
                return $options->keyBy('publisher');
            });

        $optionsToCreate = [];
        $optionsToUpdate = [];

        // Process each apply option
        foreach ($applyOptionsData as $optionData) {
            $jobListingId = $optionData['job_listing_id'];
            $publisher = $optionData['publisher'];

            $existingOption = $existingOptions->get($jobListingId)?->get($publisher);

            if ($existingOption) {
                // Update existing option
                $optionsToUpdate[] = [
                    'id' => $existingOption->id,
                    'apply_link' => $optionData['apply_link'],
                    'is_direct' => $optionData['is_direct'],
                    'updated_at' => now(),
                ];
            } else {
                // Create new option
                $optionsToCreate[] = array_merge($optionData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Upsert to handle both creates and updates in one efficient query
        if (! empty($optionsToCreate) || ! empty($optionsToUpdate)) {
            $payload = [];
            foreach ($optionsToCreate as $row) {
                $payload[] = $row;
            }
            foreach ($optionsToUpdate as $row) {
                $payload[] = [
                    'id' => $row['id'],
                    'job_listing_id' => $existingOptions->flatten()->firstWhere('id', $row['id'])->job_listing_id ?? null,
                    'publisher' => $existingOptions->flatten()->firstWhere('id', $row['id'])->publisher ?? null,
                    'apply_link' => $row['apply_link'],
                    'is_direct' => $row['is_direct'],
                    'updated_at' => $row['updated_at'],
                    'created_at' => $existingOptions->flatten()->firstWhere('id', $row['id'])->created_at ?? now(),
                ];
            }

            // Use publisher + job_listing_id as conflict target
            JobApplyOption::upsert(
                $payload,
                ['job_listing_id', 'publisher'],
                ['apply_link', 'is_direct', 'updated_at']
            );
        }

        // Clear variables to free memory
        unset($existingOptions, $optionsToCreate, $optionsToUpdate, $payload, $applyOptionsData);
    }
}
