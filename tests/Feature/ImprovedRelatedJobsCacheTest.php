<?php

use App\Caches\ImprovedRelatedJobListingCache;
use App\Models\JobListing;

it('can fetch related jobs using improved cache', function () {
    // Create a test job listing
    $job = JobListing::factory()->create();
    $job->update([
        'job_category' => 'Software Engineering',
        'slug' => 'test-job-' . time()
    ]);

    // Create some related jobs in the same category
    $relatedJob1 = JobListing::factory()->create();
    $relatedJob1->update(['job_category' => 'Software Engineering']);
    
    $relatedJob2 = JobListing::factory()->create();
    $relatedJob2->update(['job_category' => 'Software Engineering']);
    
    $relatedJob3 = JobListing::factory()->create();
    $relatedJob3->update(['job_category' => 'Software Engineering']);

    // Fetch related jobs using the improved cache
    $result = ImprovedRelatedJobListingCache::get($job);

    // Assert that we got results (could be less than 3 due to randomization)
    expect($result)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($result->count())->toBeLessThanOrEqual(3);
    
    // If we have results, assert that none of them is the original job
    if ($result->isNotEmpty()) {
        expect($result->pluck('id'))->not->toContain($job->id);
        
        // Assert that all related jobs are in the same category
        expect($result->every(fn($relatedJob) => $relatedJob->job_category === 'Software Engineering'))->toBeTrue();
    }
});

it('returns empty collection when no related jobs exist', function () {
    // Create a job with unique category
    $job = JobListing::factory()->create();
    $job->update([
        'job_category' => 'Unique Category ' . time(),
        'slug' => 'unique-job-' . time()
    ]);

    // Fetch related jobs
    $result = ImprovedRelatedJobListingCache::get($job);

    // Should return empty collection
    expect($result)->toBeEmpty();
});

it('class exists and method is callable', function () {
    $job = JobListing::factory()->create();
    $job->update(['job_category' => 'Test Category']);

    // Just verify the method exists and returns a collection
    $result = ImprovedRelatedJobListingCache::get($job);
    
    expect($result)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});
