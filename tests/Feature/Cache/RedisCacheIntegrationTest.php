<?php

namespace Tests\Feature\Cache;

use App\Helpers\RedisCache;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class RedisCacheIntegrationTest extends TestCase
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
        $patterns = ['integration_test_*', 'bulk_test_*', 'pattern_test_*'];
        foreach ($patterns as $pattern) {
            RedisCache::forgetPattern($pattern);
        }
    }

    public function test_redis_cache_basic_operations(): void
    {
        // Test basic cache operations work with Redis
        Cache::put('integration_test_basic', 'test_value', 60);
        
        $this->assertTrue(Cache::has('integration_test_basic'));
        $this->assertEquals('test_value', Cache::get('integration_test_basic'));
        
        Cache::forget('integration_test_basic');
        $this->assertFalse(Cache::has('integration_test_basic'));
    }

    public function test_redis_cache_pattern_matching(): void
    {
        // Test pattern matching with various key structures
        $testData = [
            'pattern_test_simple' => 'simple_value',
            'pattern_test_with_underscores_123' => 'underscore_value',
            'pattern_test_with-dashes-456' => 'dash_value',
            'different_pattern_test' => 'different_value',
            'not_matching_at_all' => 'not_matching_value',
        ];

        // Store all test data
        foreach ($testData as $key => $value) {
            Cache::put($key, $value, 60);
        }

        // Verify all keys exist
        foreach ($testData as $key => $value) {
            $this->assertTrue(Cache::has($key), "Key {$key} should exist");
            $this->assertEquals($value, Cache::get($key));
        }

        // Test pattern matching
        $matchingKeys = RedisCache::getKeysMatching('pattern_test_*');
        $this->assertCount(3, $matchingKeys, 'Should find 3 keys matching pattern_test_*');

        // Test pattern deletion
        $deletedCount = RedisCache::forgetPattern('pattern_test_*');
        $this->assertEquals(3, $deletedCount, 'Should delete 3 keys');

        // Verify correct keys were deleted
        $this->assertFalse(Cache::has('pattern_test_simple'));
        $this->assertFalse(Cache::has('pattern_test_with_underscores_123'));
        $this->assertFalse(Cache::has('pattern_test_with-dashes-456'));

        // Verify other keys remain
        $this->assertTrue(Cache::has('different_pattern_test'));
        $this->assertTrue(Cache::has('not_matching_at_all'));
    }

    public function test_redis_cache_performance_with_moderate_load(): void
    {
        // Test with a moderate number of keys to ensure performance
        $keyCount = 100;
        $startTime = microtime(true);

        // Create keys
        for ($i = 1; $i <= $keyCount; $i++) {
            Cache::put("bulk_test_key_{$i}", "value_{$i}", 60);
        }

        $creationTime = microtime(true) - $startTime;

        // Verify keys exist (sample check)
        $this->assertTrue(Cache::has('bulk_test_key_1'));
        $this->assertTrue(Cache::has('bulk_test_key_50'));
        $this->assertTrue(Cache::has('bulk_test_key_100'));

        // Test pattern deletion performance
        $deleteStartTime = microtime(true);
        $deletedCount = RedisCache::forgetPattern('bulk_test_key_*');
        $deleteTime = microtime(true) - $deleteStartTime;

        // Assertions
        $this->assertEquals($keyCount, $deletedCount, "Should delete all {$keyCount} keys");
        $this->assertLessThan(2.0, $deleteTime, 'Deletion should complete within 2 seconds');

        // Verify keys are actually deleted
        $this->assertFalse(Cache::has('bulk_test_key_1'));
        $this->assertFalse(Cache::has('bulk_test_key_50'));
        $this->assertFalse(Cache::has('bulk_test_key_100'));
    }

    public function test_redis_cache_with_complex_patterns(): void
    {
        // Test complex real-world cache key patterns
        $cacheKeys = [
            // Job listing patterns
            'job_software-engineer-123',
            'job_data-scientist-456',
            'job_product-manager-789',
            
            // Latest jobs patterns
            'latestJobs_country_US_exclude_abc123',
            'latestJobs_country_CA_exclude_def456',
            'latestJobs_global_exclude_ghi789',
            
            // Job page patterns
            'jobs_page_US_1_hash123',
            'jobs_page_global_2_hash456',
            
            // User recommendations
            'user_recommendations_123_5',
            'user_recommendations_456_10',
            
            // Other patterns
            'mostViewedJobs_cache',
            'related_jobs_software-engineer-123',
        ];

        // Store all cache keys
        foreach ($cacheKeys as $key) {
            Cache::put($key, "data_for_{$key}", 60);
        }

        // Verify all keys exist
        foreach ($cacheKeys as $key) {
            $this->assertTrue(Cache::has($key));
        }

        // Test selective pattern deletion
        
        // 1. Delete only job listing caches
        $jobDeletedCount = RedisCache::forgetPattern('job_*');
        $this->assertEquals(3, $jobDeletedCount);
        
        // 2. Delete only US-specific latest jobs
        $usJobsDeletedCount = RedisCache::forgetPattern('latestJobs_country_US_*');
        $this->assertEquals(1, $usJobsDeletedCount);
        
        // 3. Delete all job page caches
        $pageDeletedCount = RedisCache::forgetPattern('jobs_page_*');
        $this->assertEquals(2, $pageDeletedCount);

        // Verify correct deletions
        $this->assertFalse(Cache::has('job_software-engineer-123'));
        $this->assertFalse(Cache::has('latestJobs_country_US_exclude_abc123'));
        $this->assertFalse(Cache::has('jobs_page_US_1_hash123'));
        
        // Verify remaining keys
        $this->assertTrue(Cache::has('latestJobs_country_CA_exclude_def456'));
        $this->assertTrue(Cache::has('latestJobs_global_exclude_ghi789'));
        $this->assertTrue(Cache::has('user_recommendations_123_5'));
        $this->assertTrue(Cache::has('mostViewedJobs_cache'));
        $this->assertTrue(Cache::has('related_jobs_software-engineer-123'));
    }

    public function test_redis_cache_edge_cases(): void
    {
        // Test edge cases and error conditions
        
        // 1. Test with non-existent pattern
        $deletedCount = RedisCache::forgetPattern('non_existent_pattern_*');
        $this->assertEquals(0, $deletedCount);
        
        // 2. Test with empty result pattern
        $keys = RedisCache::getKeysMatching('empty_pattern_*');
        $this->assertEmpty($keys);
        
        // 3. Test with special characters in keys
        $specialKeys = [
            'integration_test_special-chars',
            'integration_test_special_underscores',
            'integration_test_special.dots',
            'integration_test_special:colons',
        ];
        
        foreach ($specialKeys as $key) {
            Cache::put($key, 'special_value', 60);
        }
        
        // Verify special character keys work
        foreach ($specialKeys as $key) {
            $this->assertTrue(Cache::has($key));
        }
        
        // Test pattern deletion with special characters
        $deletedCount = RedisCache::forgetPattern('integration_test_special*');
        $this->assertEquals(4, $deletedCount);
        
        foreach ($specialKeys as $key) {
            $this->assertFalse(Cache::has($key));
        }
    }
}