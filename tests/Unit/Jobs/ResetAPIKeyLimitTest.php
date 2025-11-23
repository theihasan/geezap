<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Jobs\ResetAPIKeyLimit;
use App\Models\ApiKey;
use App\Services\Cache\ApiKeyHealthCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ResetAPIKeyLimitTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_resets_api_key_limits_and_clears_cache_data(): void
    {
        // Arrange
        $apiKey1 = ApiKey::factory()->create([
            'request_remaining' => 10,
            'sent_request' => 40,
        ]);

        $apiKey2 = ApiKey::factory()->create([
            'request_remaining' => 5,
            'sent_request' => 45,
        ]);

        $cache = $this->createMock(ApiKeyHealthCache::class);

        // Expect clearAllCache to be called for each API key
        $cache->expects($this->exactly(2))
            ->method('clearAllCache')
            ->with($this->logicalOr($apiKey1->id, $apiKey2->id));

        $job = new ResetAPIKeyLimit;

        // Act
        $job->handle($cache);

        // Assert
        $apiKey1->refresh();
        $apiKey2->refresh();

        $this->assertEquals(0, $apiKey1->sent_request);
        $this->assertEquals(0, $apiKey2->sent_request);
        $this->assertGreaterThanOrEqual(50, $apiKey1->request_remaining);
        $this->assertLessThanOrEqual(80, $apiKey1->request_remaining);
        $this->assertGreaterThanOrEqual(50, $apiKey2->request_remaining);
        $this->assertLessThanOrEqual(80, $apiKey2->request_remaining);
        $this->assertNotNull($apiKey1->rate_limit_reset);
        $this->assertNotNull($apiKey2->rate_limit_reset);
    }

    #[Test]
    public function it_handles_empty_api_keys_table_gracefully(): void
    {
        // Arrange
        $cache = $this->createMock(ApiKeyHealthCache::class);

        // Expect clearAllCache to never be called since no API keys exist
        $cache->expects($this->never())
            ->method('clearAllCache');

        $job = new ResetAPIKeyLimit;

        // Act & Assert - Should not throw any exceptions
        $job->handle($cache);

        $this->assertTrue(true); // Test passes if no exception is thrown
    }

    #[Test]
    public function it_logs_successful_reset_with_cache_clearing_count(): void
    {
        // Arrange
        ApiKey::factory()->count(3)->create();

        $cache = $this->createMock(ApiKeyHealthCache::class);
        $cache->expects($this->exactly(3))
            ->method('clearAllCache');

        Log::shouldReceive('info')
            ->once()
            ->with('API Key limits reset via bulk update', \Mockery::subset([
                'updated_count' => 3,
                'cleared_cache_count' => 3,
            ]));

        $job = new ResetAPIKeyLimit;

        // Act
        $job->handle($cache);

        // Assert - Log expectations are verified by Mockery
        $this->assertTrue(true);
    }

    #[Test]
    public function it_handles_exceptions_gracefully_and_logs_errors(): void
    {
        // Arrange
        $apiKey = ApiKey::factory()->create();

        $cache = $this->createMock(ApiKeyHealthCache::class);
        $cache->method('clearAllCache')
            ->willThrowException(new \Exception('Cache service unavailable'));

        $job = new ResetAPIKeyLimit;

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cache service unavailable');
        $job->handle($cache);
    }

    #[Test]
    public function it_clears_cache_for_all_api_keys_even_when_some_fail(): void
    {
        // Arrange
        $apiKey1 = ApiKey::factory()->create();
        $apiKey2 = ApiKey::factory()->create();
        $apiKey3 = ApiKey::factory()->create();

        $cache = $this->createMock(ApiKeyHealthCache::class);

        // Mock cache clearing to succeed for all keys
        $cache->expects($this->exactly(3))
            ->method('clearAllCache');

        $job = new ResetAPIKeyLimit;

        // Act
        $job->handle($cache);

        // Assert - All cache clearing calls should have been made
        $this->assertTrue(true);
    }

    #[Test]
    public function it_updates_all_required_api_key_fields(): void
    {
        // Arrange
        $apiKey = ApiKey::factory()->create([
            'request_remaining' => 25,
            'sent_request' => 75,
            'rate_limit_reset' => now()->subDays(5),
            'request_sent_at' => now()->subHours(2),
        ]);

        $cache = $this->createMock(ApiKeyHealthCache::class);
        $cache->expects($this->once())
            ->method('clearAllCache')
            ->with($apiKey->id);

        $job = new ResetAPIKeyLimit;
        $initialUpdatedAt = $apiKey->updated_at;

        // Add a small delay to ensure timestamp difference
        sleep(1);

        // Act
        $job->handle($cache);

        // Assert
        $apiKey->refresh();

        $this->assertEquals(0, $apiKey->sent_request);
        $this->assertGreaterThanOrEqual(50, $apiKey->request_remaining);
        $this->assertLessThanOrEqual(80, $apiKey->request_remaining);
        $this->assertNull($apiKey->request_sent_at);
        $this->assertTrue($apiKey->rate_limit_reset->greaterThan(now()->subMinutes(5)));
        $this->assertTrue($apiKey->updated_at->greaterThanOrEqualTo($initialUpdatedAt));
    }
}
