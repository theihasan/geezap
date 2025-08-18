<?php

namespace Tests\Unit\Helpers;

use App\Helpers\RedisCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class RedisCacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Use Redis for testing cache operations
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
        $patterns = ['test_*', 'user_test_*', 'job_test_*'];
        foreach ($patterns as $pattern) {
            RedisCache::forgetPattern($pattern);
        }
    }

    public function test_forget_pattern_deletes_matching_keys(): void
    {
        // Arrange: Create test cache keys
        Cache::put('test_key_1', 'value1', 60);
        Cache::put('test_key_2', 'value2', 60);
        Cache::put('test_key_3', 'value3', 60);
        Cache::put('other_key', 'other_value', 60);

        // Verify keys exist
        $this->assertTrue(Cache::has('test_key_1'));
        $this->assertTrue(Cache::has('test_key_2'));
        $this->assertTrue(Cache::has('test_key_3'));
        $this->assertTrue(Cache::has('other_key'));

        // Act: Delete keys matching pattern
        $deletedCount = RedisCache::forgetPattern('test_key_*');

        // Assert: Only matching keys are deleted
        $this->assertEquals(3, $deletedCount);
        $this->assertFalse(Cache::has('test_key_1'));
        $this->assertFalse(Cache::has('test_key_2'));
        $this->assertFalse(Cache::has('test_key_3'));
        $this->assertTrue(Cache::has('other_key')); // Should remain
    }

    public function test_forget_pattern_with_cache_prefix(): void
    {
        // Arrange: Set cache prefix
        $originalPrefix = config('cache.prefix');
        config(['cache.prefix' => 'geezap_cache_']);

        Cache::put('test_prefixed_key', 'value', 60);
        $this->assertTrue(Cache::has('test_prefixed_key'));

        // Act: Delete with pattern (should work with prefix)
        $deletedCount = RedisCache::forgetPattern('test_prefixed_*');

        // Assert
        $this->assertEquals(1, $deletedCount);
        $this->assertFalse(Cache::has('test_prefixed_key'));

        // Restore original prefix
        config(['cache.prefix' => $originalPrefix]);
    }

    public function test_forget_pattern_returns_zero_for_no_matches(): void
    {
        // Act: Try to delete non-existent pattern
        $deletedCount = RedisCache::forgetPattern('nonexistent_*');

        // Assert
        $this->assertEquals(0, $deletedCount);
    }

    public function test_forget_method_handles_exact_keys(): void
    {
        // Arrange
        Cache::put('exact_key', 'value', 60);
        $this->assertTrue(Cache::has('exact_key'));

        // Act: Use forget method with exact key
        $result = RedisCache::forget('exact_key');

        // Assert
        $this->assertTrue($result);
        $this->assertFalse(Cache::has('exact_key'));
    }

    public function test_forget_method_handles_wildcard_keys(): void
    {
        // Arrange
        Cache::put('wildcard_test_1', 'value1', 60);
        Cache::put('wildcard_test_2', 'value2', 60);

        // Act: Use forget method with wildcard
        $result = RedisCache::forget('wildcard_test_*');

        // Assert
        $this->assertTrue($result);
        $this->assertFalse(Cache::has('wildcard_test_1'));
        $this->assertFalse(Cache::has('wildcard_test_2'));
    }

    public function test_get_keys_matching_returns_correct_keys(): void
    {
        // Arrange
        Cache::put('match_key_1', 'value1', 60);
        Cache::put('match_key_2', 'value2', 60);
        Cache::put('no_match_key', 'value3', 60);

        // Act
        $matchingKeys = RedisCache::getKeysMatching('match_key_*');

        // Assert
        $this->assertCount(2, $matchingKeys);
        
        // Keys should contain the cache prefix
        $prefix = config('cache.prefix', '');
        $expectedKeys = [
            $prefix . 'match_key_1',
            $prefix . 'match_key_2'
        ];
        
        foreach ($expectedKeys as $expectedKey) {
            $this->assertContains($expectedKey, $matchingKeys);
        }
    }

    public function test_forget_pattern_handles_large_number_of_keys(): void
    {
        // Arrange: Create many keys
        for ($i = 1; $i <= 50; $i++) {
            Cache::put("bulk_test_key_{$i}", "value{$i}", 60);
        }

        // Act: Delete all at once
        $deletedCount = RedisCache::forgetPattern('bulk_test_key_*');

        // Assert
        $this->assertEquals(50, $deletedCount);
        
        // Verify all are deleted
        for ($i = 1; $i <= 50; $i++) {
            $this->assertFalse(Cache::has("bulk_test_key_{$i}"));
        }
    }
}