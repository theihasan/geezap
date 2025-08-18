<?php

namespace Tests\Feature\Cache;

use App\Caches\JobListingCache;
use App\Caches\CountryAwareLatestJobsCache;
use App\Caches\CountryAwareJobPageCache;
use App\Caches\JobRecommendationCache;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class JobCacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['cache.default' => 'redis']);
    }

    public function test_job_listing_cache_stores_and_retrieves_job(): void
    {
        // Arrange
        $job = JobListing::factory()->create(['slug' => 'test-job-slug']);

        // Act: First call should hit database and cache
        $cachedJob1 = JobListingCache::get('test-job-slug');
        
        // Second call should hit cache
        $cachedJob2 = JobListingCache::get('test-job-slug');

        // Assert
        $this->assertEquals($job->id, $cachedJob1->id);
        $this->assertEquals($job->id, $cachedJob2->id);
        $this->assertTrue(Cache::has('job_test-job-slug'));
    }

    public function test_job_listing_cache_invalidation_single_job(): void
    {
        // Arrange
        $job = JobListing::factory()->create(['slug' => 'test-job-slug']);
        JobListingCache::get('test-job-slug'); // Cache the job

        $this->assertTrue(Cache::has('job_test-job-slug'));

        // Act: Invalidate specific job
        JobListingCache::invalidate('test-job-slug');

        // Assert
        $this->assertFalse(Cache::has('job_test-job-slug'));
    }

    public function test_job_listing_cache_invalidation_all_jobs(): void
    {
        // Arrange
        $job1 = JobListing::factory()->create(['slug' => 'job-1']);
        $job2 = JobListing::factory()->create(['slug' => 'job-2']);
        
        JobListingCache::get('job-1');
        JobListingCache::get('job-2');

        $this->assertTrue(Cache::has('job_job-1'));
        $this->assertTrue(Cache::has('job_job-2'));

        // Act: Invalidate all job caches
        JobListingCache::invalidate();

        // Assert
        $this->assertFalse(Cache::has('job_job-1'));
        $this->assertFalse(Cache::has('job_job-2'));
    }

    public function test_country_aware_latest_jobs_cache(): void
    {
        // Arrange
        JobListing::factory()->create(['country' => 'US']);
        JobListing::factory()->create(['country' => 'CA']);

        // Act: Cache jobs for US
        $usJobs = CountryAwareLatestJobsCache::get([], 'US', 4);
        
        // Verify cache exists
        $cacheKey = CountryAwareLatestJobsCache::key('US', []);
        $this->assertTrue(Cache::has($cacheKey));

        // Act: Invalidate US cache
        CountryAwareLatestJobsCache::invalidate('US');

        // Assert: US cache should be cleared
        $this->assertFalse(Cache::has($cacheKey));
    }

    public function test_country_aware_job_page_cache(): void
    {
        // Arrange
        JobListing::factory()->count(10)->create(['country' => 'US']);
        $request = new Request(['page' => 1, 'category' => 'tech']);

        // Act: Cache job page
        $jobPage = CountryAwareJobPageCache::get($request, 'US');
        
        $cacheKey = CountryAwareJobPageCache::key($request, 'US');
        $this->assertTrue(Cache::has($cacheKey));

        // Act: Invalidate country cache
        CountryAwareJobPageCache::invalidate('US');

        // Assert
        $this->assertFalse(Cache::has($cacheKey));
    }

    public function test_job_recommendation_cache(): void
    {
        // Arrange
        $user = User::factory()->create();
        
        // Act: Cache user recommendations
        $recommendations = JobRecommendationCache::getUserRecommendations(
            $user, 
            5, 
            fn() => collect(['job1', 'job2', 'job3'])
        );

        $cacheKey = JobRecommendationCache::userKey($user->id, 5);
        $this->assertTrue(Cache::has($cacheKey));

        // Act: Invalidate user recommendations
        JobRecommendationCache::invalidateUserRecommendations($user->id);

        // Assert
        $this->assertFalse(Cache::has($cacheKey));
    }

    public function test_job_recommendation_cache_invalidate_all(): void
    {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        JobRecommendationCache::getUserRecommendations($user1, 5, fn() => collect([]));
        JobRecommendationCache::getUserRecommendations($user2, 5, fn() => collect([]));

        $key1 = JobRecommendationCache::userKey($user1->id, 5);
        $key2 = JobRecommendationCache::userKey($user2->id, 5);
        
        $this->assertTrue(Cache::has($key1));
        $this->assertTrue(Cache::has($key2));

        // Act: Invalidate all recommendations
        JobRecommendationCache::invalidateAll();

        // Assert
        $this->assertFalse(Cache::has($key1));
        $this->assertFalse(Cache::has($key2));
    }
}