<?php

declare(strict_types=1);

namespace App\Jobs\Store;

use App\DTO\JobDTO;
use App\DTO\JobResponseDTO;
use App\Events\ExceptionHappenEvent;
use App\Models\JobListing;
use App\Models\JobApplyOption;
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

    public function __construct(public JobResponseDTO $responseDTO)
    {
    }

    public function handle(): void
    {
        try {
            $jobListings = [];
            $applyOptionsToProcess = [];
            
            // First pass: Create/update job listings and collect apply options
            foreach ($this->responseDTO->data as $jobData) {
                $jobData['job_category'] = $this->responseDTO->jobCategory;
                $jobData['category_image'] = $this->responseDTO->categoryImage;

                $jobDTO = JobDTO::fromArray($jobData);

                $existingJob = $this->findExistingJob($jobDTO);
                
                if (!$existingJob) {
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
            if (!empty($applyOptionsToProcess)) {
                $this->batchProcessApplyOptions($applyOptionsToProcess);
            }
            
        } catch (\PDOException|\Exception $e) {
            $errorMessage = is_array($e->getMessage()) ? json_encode($e->getMessage()) : $e->getMessage();
            logger()->debug('Exception sent from store job class', ['error' => $errorMessage]);
            $this->release(60);
        }
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

        // Batch insert new options
        if (!empty($optionsToCreate)) {
            JobApplyOption::insert($optionsToCreate);
        }

        // Batch update existing options
        if (!empty($optionsToUpdate)) {
            foreach ($optionsToUpdate as $updateData) {
                JobApplyOption::where('id', $updateData['id'])
                    ->update([
                        'apply_link' => $updateData['apply_link'],
                        'is_direct' => $updateData['is_direct'],
                        'updated_at' => $updateData['updated_at'],
                    ]);
            }
        }
    }
}

