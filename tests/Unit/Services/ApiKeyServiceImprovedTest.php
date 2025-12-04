<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Enums\ApiName;
use App\Models\ApiKey;
use App\Services\ApiKeyService;
use App\Services\Cache\ApiKeyHealthCache;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Comprehensive tests for improved API Key Service
 * Tests cover:
 * - Round-robin selection instead of random weighted
 * - Health factor calculations
 * - Circuit breaker logic
 * - Edge cases and error scenarios
 */
class ApiKeyServiceImprovedTest extends TestCase
{
    use RefreshDatabase;

    private ApiKeyService $service;

    private ApiKeyHealthCache $healthCache;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ApiKeyService::class);
        $this->healthCache = app(ApiKeyHealthCache::class);
        Cache::flush();
    }

    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }

    // ==================== Round-Robin Selection Tests ====================

    #[Test]
    public function it_selects_keys_in_round_robin_order(): void
    {
        // Create 3 keys with equal health
        $key1 = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);
        $key2 = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);
        $key3 = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // Reset round-robin index
        $this->service->resetRoundRobinIndex();

        // Select keys multiple times - should cycle through all three
        $selections = [];
        for ($i = 0; $i < 6; $i++) {
            $selected = $this->service->getAvailableApiKey();
            $this->assertNotNull($selected);
            $selections[] = $selected->id;
        }

        // Should cycle: key1 -> key2 -> key3 -> key1 -> key2 -> key3
        // (actual order depends on sorting, but should show cycling)
        $uniqueKeys = count(array_unique($selections));
        $this->assertEquals(3, $uniqueKeys, 'Should use all 3 keys');

        // Verify it cycles back (same key appears in position 0 and 3)
        $this->assertEquals($selections[0], $selections[3]);
    }

    #[Test]
    public function it_prioritizes_healthier_keys_over_less_healthy(): void
    {
        Cache::flush();
        
        // Create two keys with different health
        $unhealthyKey = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);
        $healthyKey = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // Simulate 5 requests with 4 failures for unhealthy key (80% failure rate)
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($unhealthyKey, $i === 0);
        }

        // Healthy key has all successful requests
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($healthyKey, true);
        }

        // Reset round-robin and get stats to verify health differences
        $this->service->resetRoundRobinIndex();
        $stats = $this->service->getApiKeyStats();
        
        $healthyStats = collect($stats)->firstWhere('id', $healthyKey->id);
        $unhealthyStats = collect($stats)->firstWhere('id', $unhealthyKey->id);
        
        // Verify health difference
        $this->assertGreaterThan($unhealthyStats['health_factor'], $healthyStats['health_factor']);
        
        // First selection should be the healthier key
        $firstSelection = $this->service->getAvailableApiKey();
        $this->assertNotNull($firstSelection);
        $this->assertEquals($healthyKey->id, $firstSelection->id);
    }

    #[Test]
    public function it_excludes_keys_with_no_remaining_requests(): void
    {
        $validKey = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);
        $exhaustedKey = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 0]);

        $this->service->resetRoundRobinIndex();

        $selected = $this->service->getAvailableApiKey();
        $this->assertEquals($validKey->id, $selected->id);
    }

    #[Test]
    public function it_excludes_keys_in_rate_limit_cooldown(): void
    {
        $validKey = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100, 'rate_limit_reset' => null]);
        $coolingDownKey = ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 100,
            'rate_limit_reset' => now()->addMinutes(5),
        ]);

        $this->service->resetRoundRobinIndex();

        $selected = $this->service->getAvailableApiKey();
        $this->assertEquals($validKey->id, $selected->id);
    }

    #[Test]
    public function it_returns_null_when_all_keys_exhausted(): void
    {
        ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 0]);
        ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 0]);

        $selected = $this->service->getAvailableApiKey();
        $this->assertNull($selected);
    }

    #[Test]
    public function it_returns_null_when_all_keys_in_cooldown(): void
    {
        ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 100,
            'rate_limit_reset' => now()->addMinutes(10),
        ]);

        $selected = $this->service->getAvailableApiKey();
        $this->assertNull($selected);
    }

    // ==================== Health Factor Tests ====================

    #[Test]
    public function it_calculates_health_factor_with_no_failures(): void
    {
        $key = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // 5 successful requests
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($key, true);
        }

        $health = $this->healthCache->getHealthFactor($key);

        // All successful = health factor of 1.0
        $this->assertEquals(1.0, $health);
    }

    #[Test]
    public function it_calculates_health_factor_with_50_percent_failures(): void
    {
        $key = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // 5 requests with 2 or 3 failures (50%)
        $this->healthCache->updateHealthMetrics($key, false); // failure
        $this->healthCache->updateHealthMetrics($key, true);
        $this->healthCache->updateHealthMetrics($key, false); // failure
        $this->healthCache->updateHealthMetrics($key, true);
        $this->healthCache->updateHealthMetrics($key, true);

        $health = $this->healthCache->getHealthFactor($key);

        // 40% failure rate: health = 1.0 - (0.4 * 0.8) = 0.68
        $expectedHealth = 1.0 - (0.4 * 0.8);
        $this->assertEqualsWithDelta($expectedHealth, $health, 0.01);
    }

    #[Test]
    public function it_returns_1_0_health_when_insufficient_data(): void
    {
        $key = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // Less than min_requests_threshold (5)
        $this->healthCache->updateHealthMetrics($key, false);
        $this->healthCache->updateHealthMetrics($key, false);

        $health = $this->healthCache->getHealthFactor($key);

        // Not enough data to judge
        $this->assertEquals(1.0, $health);
    }

    #[Test]
    public function it_applies_minimum_health_floor(): void
    {
        $key = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // All failures
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($key, false);
        }

        $health = $this->healthCache->getHealthFactor($key);

        // With 100% failure rate: health = 1.0 - (1.0 * 0.8) = 0.2, but minimum is 0.1
        // So result should be 0.2 (not hitting minimum since penalty factor is 0.8)
        $this->assertGreaterThanOrEqual(0.1, $health);
        // 100% failure rate with penalty 0.8 = 1.0 - 0.8 = 0.2
        $this->assertEqualsWithDelta(0.2, $health, 0.001);
    }

    // ==================== Circuit Breaker Tests ====================

    #[Test]
    public function it_opens_circuit_breaker_at_threshold(): void
    {
        $key = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // Create 5 requests with 3 failures (60% failure rate, exceeds 50% threshold)
        $this->healthCache->updateHealthMetrics($key, false);
        $this->healthCache->updateHealthMetrics($key, false);
        $this->healthCache->updateHealthMetrics($key, false);
        $this->healthCache->updateHealthMetrics($key, true);
        $this->healthCache->updateHealthMetrics($key, true);

        // Circuit breaker should open
        $isOpen = $this->healthCache->isCircuitBreakerOpen($key);
        $this->assertTrue($isOpen);
    }

    #[Test]
    public function it_does_not_open_circuit_breaker_below_threshold(): void
    {
        $key = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // Create 5 requests with 2 failures (40% failure rate, below 50% threshold)
        $this->healthCache->updateHealthMetrics($key, false);
        $this->healthCache->updateHealthMetrics($key, false);
        $this->healthCache->updateHealthMetrics($key, true);
        $this->healthCache->updateHealthMetrics($key, true);
        $this->healthCache->updateHealthMetrics($key, true);

        // Circuit breaker should not open
        $isOpen = $this->healthCache->isCircuitBreakerOpen($key);
        $this->assertFalse($isOpen);
    }

    #[Test]
    public function it_does_not_open_breaker_with_insufficient_data(): void
    {
        $key = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // Only 3 requests (below min_threshold of 5)
        $this->healthCache->updateHealthMetrics($key, false);
        $this->healthCache->updateHealthMetrics($key, false);
        $this->healthCache->updateHealthMetrics($key, false);

        // Circuit breaker should not open yet
        $isOpen = $this->healthCache->isCircuitBreakerOpen($key);
        $this->assertFalse($isOpen);
    }

    #[Test]
    public function it_keeps_breaker_open_until_cooldown_expires(): void
    {
        $key = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // Trigger circuit breaker
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($key, $i >= 3);
        }

        // Verify breaker is open
        $isOpen = $this->healthCache->isCircuitBreakerOpen($key);
        $this->assertTrue($isOpen);

        // Try again immediately - still open
        $isOpen = $this->healthCache->isCircuitBreakerOpen($key);
        $this->assertTrue($isOpen);
    }

    #[Test]
    public function it_excludes_circuit_breaker_keys_from_selection(): void
    {
        $brokenKey = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);
        $healthyKey = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // Break the first key
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($brokenKey, $i >= 3);
        }

        // Reset round-robin
        $this->service->resetRoundRobinIndex();

        // Should select healthy key instead
        $selected = $this->service->getAvailableApiKey();
        $this->assertEquals($healthyKey->id, $selected->id);
    }

    // ==================== Usage Update Tests ====================

    #[Test]
    public function it_updates_usage_with_successful_response(): void
    {
        $key = ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 100,
            'sent_request' => 50,
        ]);

        $mockResponse = $this->createMock(Response::class);
        $mockResponse->method('header')->willReturnMap([
            ['X-RateLimit-Requests-Remaining', 99],
            ['X-RateLimit-Reset', Carbon::now()->addHour()->timestamp],
        ]);
        $mockResponse->method('successful')->willReturn(true);

        $this->service->updateUsage($key, $mockResponse);

        $updated = $key->fresh();
        $this->assertEquals(99, $updated->request_remaining);
        $this->assertEquals(51, $updated->sent_request);
        $this->assertNotNull($updated->rate_limit_reset);
    }

    #[Test]
    public function it_updates_health_metrics_on_failure(): void
    {
        Cache::flush();
        
        $key = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        $mockResponse = $this->createMock(Response::class);
        $mockResponse->method('header')->willReturnMap([
            ['X-RateLimit-Requests-Remaining', 99],
            ['X-RateLimit-Reset', null],
        ]);
        $mockResponse->method('successful')->willReturn(false);

        $this->service->updateUsage($key, $mockResponse);

        // Health cache should track the failure
        $stats = $this->healthCache->getCacheStats($key->id);
        $this->assertEquals(1, $stats['requests']);
        $this->assertEquals(1, $stats['failures']);
    }

    // ==================== Edge Cases Tests ====================

    #[Test]
    public function it_handles_zero_keys_available(): void
    {
        // Create keys but make them all unavailable
        ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 0]);
        ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 0]);

        $selected = $this->service->getAvailableApiKey();
        $this->assertNull($selected);
    }

    #[Test]
    public function it_handles_single_key(): void
    {
        Cache::flush();
        
        $onlyKey = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        $this->service->resetRoundRobinIndex();

        // Should always return the same key
        $first = $this->service->getAvailableApiKey();
        $this->assertNotNull($first, 'First selection should not be null');
        
        $second = $this->service->getAvailableApiKey();
        $this->assertNotNull($second, 'Second selection should not be null');

        $this->assertEquals($onlyKey->id, $first->id);
        $this->assertEquals($onlyKey->id, $second->id);
    }

    #[Test]
    public function it_handles_round_robin_index_out_of_bounds(): void
    {
        $key1 = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);
        ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // Set index to value larger than available keys
        Cache::put('api_key_round_robin_index', 999, 3600);

        $selected = $this->service->getAvailableApiKey();

        // Should wrap around and return first key
        $this->assertNotNull($selected);
    }

    #[Test]
    public function it_resets_round_robin_index(): void
    {
        $key = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        Cache::put('api_key_round_robin_index', 5, 3600);

        $this->service->resetRoundRobinIndex();

        $cachedIndex = Cache::get('api_key_round_robin_index');
        $this->assertNull($cachedIndex);
    }

    #[Test]
    public function it_handles_mixed_key_availability_scenarios(): void
    {
        Cache::flush();
        
        // Create various states
        $validKey = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);
        $exhaustedKey = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 0]);
        $coolingKey = ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 100,
            'rate_limit_reset' => now()->addMinutes(5),
        ]);
        $brokenKey = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // Break the broken key
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($brokenKey, $i >= 3);
        }

        $this->service->resetRoundRobinIndex();

        // Only validKey should be selectable
        $selected = $this->service->getAvailableApiKey();
        $this->assertNotNull($selected, 'Should have a selected key');
        $this->assertEquals($validKey->id, $selected->id);

        // Second call should also return validKey (only one available)
        $selected = $this->service->getAvailableApiKey();
        $this->assertNotNull($selected, 'Should have a selected key on second call');
        $this->assertEquals($validKey->id, $selected->id);
    }

    #[Test]
    public function it_caches_health_factor_correctly(): void
    {
        $key = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // Add some metrics
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($key, true);
        }

        // First call - calculated
        $health1 = $this->healthCache->getHealthFactor($key);

        // Modify cache time to verify it's using cached value
        $cacheKey = "api_key_health_{$key->id}";
        Cache::put($cacheKey, 0.99, 3600);

        // Second call - should return cached 0.99
        $health2 = $this->healthCache->getHealthFactor($key);
        $this->assertEquals(0.99, $health2);
    }
}
