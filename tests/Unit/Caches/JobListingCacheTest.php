<?php

namespace Tests\Unit\Caches;

use Tests\TestCase;
use App\Models\JobListing;
use App\Models\JobApplyOption;
use App\Caches\JobListingCache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobListingCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_listing_cache_eager_loads_apply_options()
    {
        // Create a job listing
        $job = JobListing::factory()->create();
        
        // Update the slug after creation to ensure it's set correctly
        $job->update(['slug' => 'test-job-slug']);
        $job->refresh();

        // Create apply options for the job
        JobApplyOption::factory()->create([
            'job_listing_id' => $job->id,
            'publisher' => 'LinkedIn',
            'apply_link' => 'https://linkedin.com/jobs/test',
            'is_direct' => true
        ]);

        JobApplyOption::factory()->create([
            'job_listing_id' => $job->id,
            'publisher' => 'Indeed',
            'apply_link' => 'https://indeed.com/jobs/test',
            'is_direct' => false
        ]);

        // Clear any existing cache
        \Illuminate\Support\Facades\Cache::forget(JobListingCache::key('test-job-slug'));

        // Get the job from cache
        $cachedJob = JobListingCache::get('test-job-slug');

        // Verify the job is loaded
        $this->assertInstanceOf(JobListing::class, $cachedJob);
        $this->assertEquals('test-job-slug', $cachedJob->slug);

        // Verify apply options relationship is eager loaded (this checks the relationship, not the accessor)
        $this->assertTrue($cachedJob->relationLoaded('applyOptions'));
        
        // Access the relationship directly (not through the accessor)
        $applyOptionsRelation = $cachedJob->getRelation('applyOptions');
        $this->assertCount(2, $applyOptionsRelation);
        
        // Verify the apply options data using the relationship
        $publishers = $applyOptionsRelation->pluck('publisher')->toArray();
        $this->assertContains('LinkedIn', $publishers);
        $this->assertContains('Indeed', $publishers);
    }

    public function test_job_listing_cache_handles_job_without_apply_options()
    {
        // Create a job listing without apply options
        $job = JobListing::factory()->create();
        
        // Update the slug after creation to ensure it's set correctly
        $job->update(['slug' => 'test-job-no-options']);
        $job->refresh();

        // Clear any existing cache
        \Illuminate\Support\Facades\Cache::forget(JobListingCache::key('test-job-no-options'));

        // Get the job from cache
        $cachedJob = JobListingCache::get('test-job-no-options');

        // Verify the job is loaded
        $this->assertInstanceOf(JobListing::class, $cachedJob);
        $this->assertEquals('test-job-no-options', $cachedJob->slug);

        // Verify apply options relationship is loaded but empty
        $this->assertTrue($cachedJob->relationLoaded('applyOptions'));
        
        // Access the relationship directly (not through the accessor)
        $applyOptionsRelation = $cachedJob->getRelation('applyOptions');
        $this->assertCount(0, $applyOptionsRelation);
    }
}
