<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Enums\ApiName;
use App\Models\ApiKey;
use App\Services\ApiKeyService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiKeyServiceTest extends TestCase
{
    use RefreshDatabase;

    private ApiKeyService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ApiKeyService;
    }

    #[Test]
    public function it_returns_available_api_key_with_remaining_requests(): void
    {
        $apiKey = ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 100,
            'sent_request' => 50,
        ]);

        // Create another key with no remaining requests
        ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 0,
            'sent_request' => 100,
        ]);

        $result = $this->service->getAvailableApiKey();

        $this->assertNotNull($result);
        $this->assertEquals($apiKey->id, $result->id);
        $this->assertEquals(100, $result->request_remaining);
    }

    #[Test]
    public function it_returns_api_key_with_lowest_sent_requests_when_multiple_available(): void
    {
        $highUsageKey = ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 50,
            'sent_request' => 100,
        ]);

        $lowUsageKey = ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 75,
            'sent_request' => 25,
        ]);

        $result = $this->service->getAvailableApiKey();

        $this->assertNotNull($result);
        $this->assertEquals($lowUsageKey->id, $result->id);
        $this->assertEquals(25, $result->sent_request);
    }

    #[Test]
    public function it_returns_null_when_no_api_keys_have_remaining_requests(): void
    {
        ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 0,
            'sent_request' => 100,
        ]);

        ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 0,
            'sent_request' => 150,
        ]);

        Log::shouldReceive('warning')
            ->once()
            ->with('No available API key with remaining requests');

        $result = $this->service->getAvailableApiKey();

        $this->assertNull($result);
    }

    #[Test]
    public function it_returns_null_when_no_job_api_keys_exist(): void
    {
        ApiKey::factory()->create([
            'api_name' => 'OTHER_SERVICE',
            'request_remaining' => 100,
            'sent_request' => 50,
        ]);

        Log::shouldReceive('warning')
            ->once()
            ->with('No available API key with remaining requests');

        $result = $this->service->getAvailableApiKey();

        $this->assertNull($result);
    }

    #[Test]
    public function it_updates_api_key_usage_correctly(): void
    {
        $apiKey = ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 100,
            'sent_request' => 50,
            'rate_limit_reset' => null,
        ]);

        $mockResponse = $this->createMock(Response::class);
        $mockResponse->method('header')
            ->willReturnMap([
                ['X-RateLimit-Requests-Remaining', 99],
                ['X-RateLimit-Reset', 1640995200], // 2022-01-01 00:00:00 UTC
            ]);

        $expectedResetTime = Carbon::createFromTimestamp(1640995200);

        $this->service->updateUsage($apiKey, $mockResponse);

        $updatedKey = $apiKey->fresh();
        $this->assertEquals(99, $updatedKey->request_remaining);
        $this->assertEquals(51, $updatedKey->sent_request);
        $this->assertEquals($expectedResetTime->toDateTimeString(), $updatedKey->rate_limit_reset->toDateTimeString());
        $this->assertNotNull($updatedKey->updated_at);
    }

    #[Test]
    public function it_handles_null_rate_limit_reset_header(): void
    {
        $apiKey = ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 100,
            'sent_request' => 50,
        ]);

        $mockResponse = $this->createMock(Response::class);
        $mockResponse->method('header')
            ->willReturnMap([
                ['X-RateLimit-Requests-Remaining', 99],
                ['X-RateLimit-Reset', null],
            ]);

        $this->service->updateUsage($apiKey, $mockResponse);

        $updatedKey = $apiKey->fresh();
        $this->assertEquals(99, $updatedKey->request_remaining);
        $this->assertEquals(51, $updatedKey->sent_request);
        $this->assertNull($updatedKey->rate_limit_reset);
    }

    #[Test]
    public function it_uses_raw_sql_to_increment_sent_request_atomically(): void
    {
        $apiKey = ApiKey::factory()->create([
            'api_name' => ApiName::JOB,
            'request_remaining' => 100,
            'sent_request' => 50,
        ]);

        $mockResponse = $this->createMock(Response::class);
        $mockResponse->method('header')
            ->willReturnMap([
                ['X-RateLimit-Requests-Remaining', 99],
                ['X-RateLimit-Reset', 1640995200],
            ]);

        // Spy on DB queries
        DB::enableQueryLog();

        $this->service->updateUsage($apiKey, $mockResponse);

        $queries = DB::getQueryLog();
        $updateQuery = collect($queries)->first(function ($query) {
            return str_contains($query['query'], 'sent_request + 1');
        });

        $this->assertNotNull($updateQuery, 'Should use raw SQL to increment sent_request atomically');

        // Verify the actual value was incremented
        $this->assertEquals(51, $apiKey->fresh()->sent_request);
    }
}
