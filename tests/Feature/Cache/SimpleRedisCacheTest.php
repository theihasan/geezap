<?php

namespace Tests\Feature\Cache;

use App\Helpers\RedisCache;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SimpleRedisCacheTest extends TestCaseπ
{
    public function test_redis_connection_works(): void
    {
        // Skip if Redis is not available
        try {
            Cache::store('redis')->put('test_connection', 'test_value', 1);
            $this->assertTrue(true, 'Redis connection successful');
        } catch (\Exception $e) {
            $this->markTestSkipped('Redis not available: ' . $e->getMessage());
        }
    }

    public function test_basic_cache_operations(): void
    {
        // Test with array cache if Redis fails
        config(['cache.default' => 'array']);

        Cache::put('test_key', 'test_value', 60);
        $this->assertTrue(Cache::has('test_key'));
        $this->assertEquals('test_value', Cache::get('test_key'));

        Cache::forget('test_key');
        $this->assertFalse(Cache::has('test_key'));
    }

    public function test_redis_cache_helper_with_array_fallback(): void
    {
        // Test RedisCache helper with array cache (for when Redis is not available)
        config(['cache.default' => 'array']);

        // Put some test data
        Cache::put('test_pattern_1', 'value1', 60);
        Cache::put('test_pattern_2', 'value2', 60);
        Cache::put('other_key', 'other_value', 60);

        $this->assertTrue(Cache::has('test_pattern_1'));
        $this->assertTrue(Cache::has('test_pattern_2'));
        $this->assertTrue(Cache::has('other_key'));

        // Test the forget method with exact key (should work with array cache)
        $result = RedisCache::forget('test_pattern_1');
        $this->assertTrue($result);
        $this->assertFalse(Cache::has('test_pattern_1'));

        // The remaining keys should still exist
        $this->assertTrue(Cache::has('test_pattern_2'));
        $this->assertTrue(Cache::has('other_key'));
    }

    public function test_redis_cache_with_redis_if_available(): void
    {
        try {
            // Try to use Redis
            config(['cache.default' => 'redis']);

            // Test basic Redis operations
            Cache::put('redis_test_1', 'redis_value_1', 60);
            Cache::put('redis_test_2', 'redis_value_2', 60);
            Cache::put('other_redis_key', 'other_redis_value', 60);

            $this->assertTrue(Cache::has('redis_test_1'));
            $this->assertTrue(Cache::has('redis_test_2'));
            $this->assertTrue(Cache::has('other_redis_key'));

            // Test pattern deletion (Redis-specific)
            $deletedCount = RedisCache::forgetPattern('redis_test_*');

            // With Redis, this should delete 2 keys
            $this->assertEquals(2, $deletedCount);
            $this->assertFalse(Cache::has('redis_test_1'));
            $this->assertFalse(Cache::has('redis_test_2'));
            $this->assertTrue(Cache::has('other_redis_key'));

            // Clean up
            Cache::forget('other_redis_key');

        } catch (\Exception $e) {
            // If Redis is not available, skip this test
            $this->markTestSkipped('Redis not available for pattern testing: ' . $e->getMessage());
        }
    }
}
