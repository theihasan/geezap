<?php

namespace Tests\Feature\Cache;

use App\Helpers\RedisCache;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class JobCacheTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['cache.default' => 'redis']);
        
        // Clear any existing test keys
        $this->clearTestKeys();
    }

    protected function tearDown(): void
    {
        $this->clearTestKeys();
        parent::tearDown();
    }

    private function clearTestKeys(): void
    {
        $patterns = ['job_test_*', 'latestJobs_test_*', 'jobs_page_test_*', 'user_recommendations_test_*'];
        foreach ($patterns as $pattern) {
            RedisCache::forgetPattern($pattern);
        }
    }

    public function test_redis_cache_pattern_invalidation(): void
    {
        // Arrange: Create test cache keys simulating job caches
        Cache::put('job_test_slug_1', 'job_data_1', 60);
        Cache::put('job_test_slug_2', 'job_data_2', 60);
        Cache::put('other_test_data', 'other_data', 60);

        // Verify keys exist
        $this->assertTrue(Cache::has('job_test_slug_1'));
        $this->assertTrue(Cache::has('job_test_slug_2'));
        $this->assertTrue(Cache::has('other_test_data'));

        // Act: Invalidate all job caches using pattern
        $deletedCount = RedisCache::forgetPattern('job_test_*');

        // Assert: Only job keys are deleted
        $this->assertEquals(2, $deletedCount);
        $this->assertFalse(Cache::has('job_test_slug_1'));
        $this->assertFalse(Cache::has('job_test_slug_2'));
        $this->assertTrue(Cache::has('other_test_data')); // Should remain
    }

    public function test_country_aware_cache_pattern_invalidation(): void
    {
        // Arrange: Create country-specific cache keys
        Cache::put('latestJobs_test_country_US_exclude_123', 'us_jobs', 60);
        Cache::put('latestJobs_test_country_US_exclude_456', 'us_jobs_2', 60);
        Cache::put('latestJobs_test_country_CA_exclude_789', 'ca_jobs', 60);
        Cache::put('latestJobs_test_global_exclude_abc', 'global_jobs', 60);

        // Verify all keys exist
        $this->assertTrue(Cache::has('latestJobs_test_country_US_exclude_123'));
        $this->assertTrue(Cache::has('latestJobs_test_country_US_exclude_456'));
        $this->assertTrue(Cache::has('latestJobs_test_country_CA_exclude_789'));
        $this->assertTrue(Cache::has('latestJobs_test_global_exclude_abc'));

        // Act: Invalidate only US country caches
        $deletedCount = RedisCache::forgetPattern('latestJobs_test_country_US_*');

        // Assert: Only US caches are deleted
        $this->assertEquals(2, $deletedCount);
        $this->assertFalse(Cache::has('latestJobs_test_country_US_exclude_123'));
        $this->assertFalse(Cache::has('latestJobs_test_country_US_exclude_456'));
        $this->assertTrue(Cache::has('latestJobs_test_country_CA_exclude_789'));
        $this->assertTrue(Cache::has('latestJobs_test_global_exclude_abc'));
    }

    public function test_job_page_cache_pattern_invalidation(): void
    {
        // Arrange: Create job page cache keys
        Cache::put('jobs_page_test_US_1_abc123', 'page_data_1', 60);
        Cache::put('jobs_page_test_US_2_def456', 'page_data_2', 60);
        Cache::put('jobs_page_test_global_1_ghi789', 'global_page_1', 60);
        Cache::put('other_page_data', 'other_data', 60);

        // Verify keys exist
        $this->assertTrue(Cache::has('jobs_page_test_US_1_abc123'));
        $this->assertTrue(Cache::has('jobs_page_test_US_2_def456'));
        $this->assertTrue(Cache::has('jobs_page_test_global_1_ghi789'));
        $this->assertTrue(Cache::has('other_page_data'));

        // Act: Invalidate US job page caches
        $deletedCount = RedisCache::forgetPattern('jobs_page_test_US_*');

        // Assert: Only US job page caches are deleted
        $this->assertEquals(2, $deletedCount);
        $this->assertFalse(Cache::has('jobs_page_test_US_1_abc123'));
        $this->assertFalse(Cache::has('jobs_page_test_US_2_def456'));
        $this->assertTrue(Cache::has('jobs_page_test_global_1_ghi789'));
        $this->assertTrue(Cache::has('other_page_data'));
    }

    public function test_user_recommendations_cache_pattern_invalidation(): void
    {
        // Arrange: Create user recommendation cache keys
        Cache::put('user_recommendations_test_123_5', 'user_123_recs', 60);
        Cache::put('user_recommendations_test_123_10', 'user_123_more_recs', 60);
        Cache::put('user_recommendations_test_456_5', 'user_456_recs', 60);
        Cache::put('other_user_data', 'other_data', 60);

        // Verify keys exist
        $this->assertTrue(Cache::has('user_recommendations_test_123_5'));
        $this->assertTrue(Cache::has('user_recommendations_test_123_10'));
        $this->assertTrue(Cache::has('user_recommendations_test_456_5'));
        $this->assertTrue(Cache::has('other_user_data'));

        // Act: Invalidate recommendations for user 123
        $deletedCount = RedisCache::forgetPattern('user_recommendations_test_123_*');

        // Assert: Only user 123 recommendations are deleted
        $this->assertEquals(2, $deletedCount);
        $this->assertFalse(Cache::has('user_recommendations_test_123_5'));
        $this->assertFalse(Cache::has('user_recommendations_test_123_10'));
        $this->assertTrue(Cache::has('user_recommendations_test_456_5'));
        $this->assertTrue(Cache::has('other_user_data'));
    }

    public function test_bulk_cache_invalidation(): void
    {
        // Arrange: Create multiple cache types
        Cache::put('job_test_1', 'job_1', 60);
        Cache::put('job_test_2', 'job_2', 60);
        Cache::put('jobs_page_test_1', 'page_1', 60);
        Cache::put('jobs_page_test_2', 'page_2', 60);
        Cache::put('latestJobs_test_1', 'latest_1', 60);
        Cache::put('related_jobs_test_1', 'related_1', 60);
        Cache::put('keep_this_cache', 'keep_this', 60);

        // Verify all keys exist
        $this->assertTrue(Cache::has('job_test_1'));
        $this->assertTrue(Cache::has('jobs_page_test_1'));
        $this->assertTrue(Cache::has('latestJobs_test_1'));
        $this->assertTrue(Cache::has('related_jobs_test_1'));
        $this->assertTrue(Cache::has('keep_this_cache'));

        // Act: Simulate what happens when a job is created/updated (observer behavior)
        $deletedCounts = [];
        $deletedCounts[] = RedisCache::forgetPattern('jobs_page_test_*');
        $deletedCounts[] = RedisCache::forgetPattern('latestJobs_test_*');
        $deletedCounts[] = RedisCache::forgetPattern('related_jobs_test_*');

        // Assert: Related caches are cleared but others remain
        $totalDeleted = array_sum($deletedCounts);
        $this->assertGreaterThan(0, $totalDeleted);
        
        $this->assertFalse(Cache::has('jobs_page_test_1'));
        $this->assertFalse(Cache::has('jobs_page_test_2'));
        $this->assertFalse(Cache::has('latestJobs_test_1'));
        $this->assertFalse(Cache::has('related_jobs_test_1'));
        
        // These should remain
        $this->assertTrue(Cache::has('job_test_1'));
        $this->assertTrue(Cache::has('job_test_2'));
        $this->assertTrue(Cache::has('keep_this_cache'));
    }
}