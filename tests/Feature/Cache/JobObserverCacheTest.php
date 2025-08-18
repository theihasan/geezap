<?php

namespace Tests\Feature\Cache;

use App\Caches\JobListingCache;
use App\Caches\CountryAwareLatestJobsCache;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class JobObserverCacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['cache.default' => 'redis']);
    }

    public function test_job_creation_invalidates_related_caches(): void
    {
        // Arrange: Pre-populate some caches
        Cache::put('jobs_page_1_test', 'data', 60);
        Cache::put('latestJobs_global_test', 'data', 60);
        Cache::put('mostViewedJobs_test', 'data', 60);

        $this->assertTrue(Cache::has('jobs_page_1_test'));
        $this->assertTrue(Cache::has('latestJobs_global_test'));
        $this->assertTrue(Cache::has('mostViewedJobs_test'));

        // Act: Create a new job (this should trigger observer)
        JobListing::factory()->create(['slug' => 'new-job']);

        // Assert: Related caches should be invalidated
        $this->assertFalse(Cache::has('jobs_page_1_test'));
        $this->assertFalse(Cache::has('latestJobs_global_test'));
        $this->assertFalse(Cache::has('mostViewedJobs_test'));
    }

    public function test_job_update_invalidates_specific_caches(): void
    {
        // Arrange
        $job = JobListing::factory()->create(['slug' => 'original-slug']);
        
        // Cache the job
        JobListingCache::get('original-slug');
        Cache::put('jobs_page_test_data', 'data', 60);
        
        $this->assertTrue(Cache::has('job_original-slug'));
        $this->assertTrue(Cache::has('jobs_page_test_data'));

        // Act: Update the job
        $job->update(['title' => 'Updated Title']);

        // Assert: Specific job cache and page caches should be cleared
        $this->assertFalse(Cache::has('job_original-slug'));
        $this->assertFalse(Cache::has('jobs_page_test_data'));
    }

    public function test_job_deletion_clears_all_related_caches(): void
    {
        // Arrange
        $job = JobListing::factory()->create(['slug' => 'job-to-delete']);
        
        // Pre-populate caches
        JobListingCache::get('job-to-delete');
        Cache::put('jobs_page_delete_test', 'data', 60);
        Cache::put('latestJobs_delete_test', 'data', 60);
        
        $this->assertTrue(Cache::has('job_job-to-delete'));
        $this->assertTrue(Cache::has('jobs_page_delete_test'));
        $this->assertTrue(Cache::has('latestJobs_delete_test'));

        // Act: Delete the job
        $job->delete();

        // Assert: All related caches should be cleared
        $this->assertFalse(Cache::has('job_job-to-delete'));
        $this->assertFalse(Cache::has('jobs_page_delete_test'));
        $this->assertFalse(Cache::has('latestJobs_delete_test'));
    }
}