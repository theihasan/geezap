<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Models\JobApplyOption;
use App\Models\JobCategory;
use App\Models\JobListing;
use App\Models\User;

trait CreatesJobListings
{
    /**
     * Create a job listing with related models.
     */
    protected function createJobListing(array $attributes = []): JobListing
    {
        return JobListing::factory()->create($attributes);
    }
    
    /**
     * Create a job listing with a category.
     */
    protected function createJobListingWithCategory(array $jobAttributes = [], array $categoryAttributes = []): array
    {
        $category = JobCategory::factory()->create($categoryAttributes);
        $jobListing = JobListing::factory()->create(array_merge(
            ['job_category' => $category->id],
            $jobAttributes
        ));
        
        return [
            'jobListing' => $jobListing,
            'category' => $category,
        ];
    }
    
    /**
     * Create a job listing with apply options.
     */
    protected function createJobListingWithApplyOptions(
        int $optionsCount = 3,
        array $jobAttributes = [],
        array $optionAttributes = []
    ): array {
        $jobListing = JobListing::factory()->create($jobAttributes);
        
        $applyOptions = JobApplyOption::factory()->count($optionsCount)->create(array_merge(
            ['job_listing_id' => $jobListing->id],
            $optionAttributes
        ));
        
        return [
            'jobListing' => $jobListing,
            'applyOptions' => $applyOptions,
        ];
    }
    
    /**
     * Create a job listing with users who have applied.
     */
    protected function createJobListingWithApplicants(
        int $applicantsCount = 3,
        array $jobAttributes = [],
        array $userAttributes = []
    ): array {
        $jobListing = JobListing::factory()->create($jobAttributes);
        
        $users = User::factory()->count($applicantsCount)->create($userAttributes);
        
        $jobListing->users()->attach($users->pluck('id'));
        
        return [
            'jobListing' => $jobListing,
            'applicants' => $users,
        ];
    }
}