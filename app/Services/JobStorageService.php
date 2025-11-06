<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\JobDTO;
use App\Models\JobApplyOption;
use App\Models\JobListing;

class JobStorageService
{
    public function storeJob(JobDTO $jobDTO): JobListing
    {
        $existingJob = $this->findExistingJob($jobDTO);

        if (! $existingJob) {
            $jobListing = JobListing::query()->create($jobDTO->toArray());
        } else {
            $jobListing = $existingJob;
            $jobListing->update($jobDTO->toArray());
        }

        $this->storeApplyOptions($jobListing, $jobDTO->applyOptions);

        return $jobListing;
    }

    private function findExistingJob(JobDTO $jobDTO): ?JobListing
    {
        if ($jobDTO->jobId) {
            $existingJob = JobListing::query()->where('job_id', $jobDTO->jobId)->first();
            if ($existingJob) {
                \Log::info('Found existing job by job_id', ['job_id' => $jobDTO->jobId]);

                return $existingJob;
            }
        }

        $fallbackQuery = JobListing::query()
            ->where('job_title', $jobDTO->jobTitle)
            ->where('employer_name', $jobDTO->employerName)
            ->when($jobDTO->city, fn ($q) => $q->where('city', $jobDTO->city))
            ->when($jobDTO->state, fn ($q) => $q->where('state', $jobDTO->state))
            ->when($jobDTO->country, fn ($q) => $q->where('country', $jobDTO->country))
            ->when($jobDTO->publisher, fn ($q) => $q->where('publisher', $jobDTO->publisher));

        $fallbackJob = $fallbackQuery->first();
        if ($fallbackJob && app()->environment('testing')) {
            \Log::info('Found existing job by fallback criteria', [
                'new_job_id' => $jobDTO->jobId,
                'existing_job_id' => $fallbackJob->job_id,
                'job_title' => $jobDTO->jobTitle,
                'employer_name' => $jobDTO->employerName,
            ]);
        }

        return $fallbackJob;
    }

    private function storeApplyOptions(JobListing $jobListing, ?array $applyOptions): void
    {
        if (! $applyOptions || ! is_array($applyOptions)) {
            return;
        }

        // Delete existing apply options for this job
        JobApplyOption::where('job_listing_id', $jobListing->id)->delete();

        // Insert new apply options
        $applyOptionsData = [];
        foreach ($applyOptions as $option) {
            $applyOptionsData[] = [
                'job_listing_id' => $jobListing->id,
                'publisher' => $option['publisher'],
                'apply_link' => $option['apply_link'],
                'is_direct' => $option['is_direct'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        JobApplyOption::insert($applyOptionsData);
    }
}
