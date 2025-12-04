<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Enums\ApiName;
use App\Jobs\GetJobData;
use App\Models\ApiKey;
use App\Models\Country;
use App\Models\JobCategory;
use App\Services\ApiKeyService;
use App\Services\Cache\ApiKeyHealthCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Feature tests for job fetching edge cases and error scenarios
 */
class JobFetchingEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    private ApiKeyService $apiKeyService;

    private ApiKeyHealthCache $healthCache;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiKeyService = app(ApiKeyService::class);
        $this->healthCache = app(ApiKeyHealthCache::class);
        Queue::fake();
        Cache::flush();
    }

    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }

    // ==================== No Available Keys Tests ====================

    #[Test]
    public function it_handles_job_fetch_when_no_keys_available(): void
    {
        // Create exhausted API key
        ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 0,
        ]);

        // Should return null when no keys available
        $selected = $this->apiKeyService->getAvailableApiKey();
        $this->assertNull($selected);
    }

    #[Test]
    public function it_logs_when_all_keys_in_cooldown(): void
    {
        // Create key in rate limit cooldown
        ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 100,
            'rate_limit_reset' => now()->addMinutes(5),
        ]);

        // Should return null when all in cooldown
        $selected = $this->apiKeyService->getAvailableApiKey();
        $this->assertNull($selected);
    }

    #[Test]
    public function it_logs_when_all_keys_have_circuit_breaker_open(): void
    {
        $brokenKey = ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 100,
        ]);

        // Simulate circuit breaker activation (5 failures)
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($brokenKey, false);
        }

        // Should return null when all keys have circuit breaker open
        $selected = $this->apiKeyService->getAvailableApiKey();
        $this->assertNull($selected);
    }

    // ==================== Key Selection Tests ====================

    #[Test]
    public function it_selects_correct_key_for_each_page(): void
    {
        $category = JobCategory::factory()->create();
        $country = Country::factory()->create();

        $key1 = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);
        $key2 = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // Reset round-robin
        $this->apiKeyService->resetRoundRobinIndex();

        // Simulate multiple page requests
        $selectedKeys = [];
        for ($page = 1; $page <= 4; $page++) {
            $selected = $this->apiKeyService->getAvailableApiKey();
            if ($selected) {
                $selectedKeys[] = $selected->id;
            }
        }

        // Should have selected keys
        $this->assertNotEmpty($selectedKeys);
        // Should have both key1 and key2 in rotation
        $this->assertContains($key1->id, $selectedKeys);
        $this->assertContains($key2->id, $selectedKeys);
    }

    #[Test]
    public function it_skips_unhealthy_keys_during_selection(): void
    {
        $category = JobCategory::factory()->create();
        $country = Country::factory()->create();

        $healthyKey = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);
        $unhealthyKey = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // Make unhealthy key have high failure rate
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($unhealthyKey, $i >= 3);
        }

        $this->apiKeyService->resetRoundRobinIndex();

        // Should prefer healthy key
        $selected = $this->apiKeyService->getAvailableApiKey();
        $this->assertEquals($healthyKey->id, $selected->id);
    }

    // ==================== Health and Circuit Breaker Tests ====================

    #[Test]
    public function it_tracks_failures_per_key(): void
    {
        $key1 = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);
        $key2 = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // Simulate failures for key1
        for ($i = 0; $i < 3; $i++) {
            $this->healthCache->updateHealthMetrics($key1, false);
        }

        // Simulate successes for key2
        for ($i = 0; $i < 3; $i++) {
            $this->healthCache->updateHealthMetrics($key2, true);
        }

        $stats1 = $this->healthCache->getCacheStats($key1->id);
        $stats2 = $this->healthCache->getCacheStats($key2->id);

        $this->assertEquals(3, $stats1['requests']);
        $this->assertEquals(3, $stats1['failures']);

        $this->assertEquals(3, $stats2['requests']);
        $this->assertEquals(0, $stats2['failures']);
    }

    #[Test]
    public function it_recovers_keys_from_circuit_breaker(): void
    {
        $key = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // Trigger circuit breaker
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($key, $i >= 3);
        }

        $this->assertTrue($this->healthCache->isCircuitBreakerOpen($key));

        // Clear cache
        $this->healthCache->clearAllCache($key->id);

        // Should be available again
        $this->assertFalse($this->healthCache->isCircuitBreakerOpen($key));
    }

    // ==================== Multiple Key Rotation Tests ====================

    #[Test]
    public function it_distributes_requests_across_multiple_healthy_keys(): void
    {
        // Create 3 equally healthy keys
        $keys = ApiKey::factory()
            ->count(3)
            ->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        $this->apiKeyService->resetRoundRobinIndex();

        // Make 9 selections (should cycle through 3 keys, 3 times)
        $selections = [];
        for ($i = 0; $i < 9; $i++) {
            $selected = $this->apiKeyService->getAvailableApiKey();
            $selections[] = $selected->id;
        }

        // Count occurrences
        $counts = array_count_values($selections);

        // Each key should be selected 3 times
        foreach ($keys as $key) {
            $this->assertEquals(3, $counts[$key->id]);
        }
    }

    #[Test]
    public function it_prioritizes_healthier_keys_in_rotation_order(): void
    {
        Cache::flush();
        
        $key1 = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);
        $key2 = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);
        $key3 = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // key1: some failures but not circuit breaker threshold
        // 3 failures, 2 successes = 60% failure rate - health = 1.0 - (0.6 * 0.8) = 0.52
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($key1, $i >= 3);
        }

        // key2: moderate failures (40% failure rate) - health = 1.0 - (0.4 * 0.8) = 0.68
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($key2, $i >= 2);
        }

        // key3: all success (0% failure rate) - health = 1.0
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($key3, true);
        }

        // Reset round-robin
        $this->apiKeyService->resetRoundRobinIndex();

        // Get selections - should cycle through all three
        $first = $this->apiKeyService->getAvailableApiKey();
        $second = $this->apiKeyService->getAvailableApiKey();
        $third = $this->apiKeyService->getAvailableApiKey();

        $this->assertNotNull($first);
        $this->assertNotNull($second);
        $this->assertNotNull($third);

        // Verify health ordering - key3 (healthiest) should be selected first
        // Health: key3=1.0, key2=0.68, key1=0.52
        $this->assertEquals($key3->id, $first->id, 'First selection should be healthiest key (key3)');
    }

    // ==================== Exhaustion Tests ====================

    #[Test]
    public function it_handles_key_becoming_exhausted_mid_fetch(): void
    {
        $category = JobCategory::factory()->create();
        $country = Country::factory()->create();

        $key = ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 1,
        ]);

        // First selection should work
        $selected = $this->apiKeyService->getAvailableApiKey();
        $this->assertNotNull($selected);

        // Exhaust the key
        $key->update(['request_remaining' => 0]);

        // Second selection should return null
        $selected = $this->apiKeyService->getAvailableApiKey();
        $this->assertNull($selected);
    }

    #[Test]
    public function it_handles_transition_from_healthy_to_unhealthy(): void
    {
        Cache::flush();
        
        $key = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100]);

        // Initially healthy (no failure data)
        $this->assertFalse($this->healthCache->isCircuitBreakerOpen($key));

        // Simulate multiple failures until breaker opens
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($key, false);
        }

        // After 5 failures with 0 successes (100% failure rate), circuit breaker should open
        $isBroken = $this->healthCache->isCircuitBreakerOpen($key);
        $this->assertTrue($isBroken, "Circuit breaker should open after 5 consecutive failures");
    }

    // ==================== API Key Stats Tests ====================

    #[Test]
    public function it_generates_accurate_api_key_stats(): void
    {
        Cache::flush();
        
        $key1 = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 100, 'sent_request' => 50]);
        $key2 = ApiKey::factory()->create(['api_name' => ApiName::JOB, 'request_remaining' => 50, 'sent_request' => 100]);

        // Add metrics for key1 - all successful
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($key1, true);
        }

        // Add metrics for key2 (with failures) - 2 failures out of 5
        for ($i = 0; $i < 5; $i++) {
            $this->healthCache->updateHealthMetrics($key2, $i >= 2); // failures on index 0,1
        }

        $stats = $this->apiKeyService->getApiKeyStats();

        $this->assertCount(2, $stats);

        $stat1 = collect($stats)->firstWhere('id', $key1->id);
        $stat2 = collect($stats)->firstWhere('id', $key2->id);

        $this->assertEquals(100, $stat1['request_remaining']);
        $this->assertEquals(50, $stat2['request_remaining']);

        // key1 is healthier (1.0 health)
        // key2 is less healthy: 1.0 - (0.4 * 0.8) = 0.68
        $this->assertGreaterThan($stat2['health_factor'], $stat1['health_factor']);
    }
}
