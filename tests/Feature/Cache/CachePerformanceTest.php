<?php

namespace Tests\Feature\Cache;

use App\Helpers\RedisCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CachePerformanceTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        config(['cache.default' => 'redis']);
    }

    public function test_pattern_deletion_performance_with_many_keys(): void
    {
        $keyCount = 1000;
        $startTime = microtime(true);

        for ($i = 1; $i <= $keyCount; $i++) {
            Cache::put("perf_test_key_{$i}", "value{$i}", 60);
        }

        $setupTime = microtime(true) - $startTime;

        $deleteStartTime = microtime(true);
        $deletedCount = RedisCache::forgetPattern('perf_test_key_*');
        $deleteTime = microtime(true) - $deleteStartTime;

        $this->assertEquals($keyCount, $deletedCount);

        $this->assertLessThan(5.0, $deleteTime, 'Pattern deletion should complete within 5 seconds');

        for ($i = 1; $i <= min(10, $keyCount); $i++) {
            $this->assertFalse(Cache::has("perf_test_key_{$i}"));
        }
    }

    public function test_get_keys_matching_performance(): void
    {
        $keyCount = 500;
        for ($i = 1; $i <= $keyCount; $i++) {
            Cache::put("match_perf_key_{$i}", "value{$i}", 60);
        }

        $startTime = microtime(true);
        $matchingKeys = RedisCache::getKeysMatching('match_perf_key_*');
        $retrievalTime = microtime(true) - $startTime;

        $this->assertCount($keyCount, $matchingKeys);
        $this->assertLessThan(2.0, $retrievalTime, 'Key matching should complete within 2 seconds');
    }
}
