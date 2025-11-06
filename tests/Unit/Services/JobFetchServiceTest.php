<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\ApiKey;
use App\Models\Country;
use App\Models\JobCategory;
use App\Services\ApiKeyService;
use App\Services\JobFetchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class JobFetchServiceTest extends TestCase
{
    use RefreshDatabase;

    private JobFetchService $service;

    private ApiKeyService $apiKeyService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiKeyService = $this->createMock(ApiKeyService::class);
        $this->service = new JobFetchService($this->apiKeyService);
        Queue::fake();
    }

    #[Test]
    public function it_returns_early_when_no_api_key_available(): void
    {
        $category = JobCategory::factory()
            ->has(Country::factory()->count(2))
            ->create();

        $this->apiKeyService
            ->expects($this->once())
            ->method('getAvailableApiKey')
            ->willReturn(null);

        Http::fake();

        $this->service->fetchJobsForCategory($category, 2);

        Http::assertNothingSent();
    }

    #[Test]
    public function it_fetches_jobs_for_all_countries_and_pages(): void
    {
        $countries = Country::factory()->count(2)->create([
            'code' => 'US',
        ]);
        $category = JobCategory::factory()->create([
            'query_name' => 'software engineer',
            'num_page' => 5,
            'timeframe' => 'week',
            'category_image' => 'tech.jpg',
        ]);
        $category->countries()->attach($countries);

        $apiKey = ApiKey::factory()->create();

        $this->apiKeyService
            ->method('getAvailableApiKey')
            ->willReturn($apiKey);

        $this->apiKeyService
            ->expects($this->exactly(4))
            ->method('updateUsage');

        Http::fake([
            '*' => Http::response([
                'data' => [
                    [
                        'job_id' => 'test-job-1',
                        'job_title' => 'Software Engineer',
                        'employer_name' => 'Tech Company',
                    ],
                ],
            ], 200, [
                'X-RateLimit-Requests-Remaining' => 99,
                'X-RateLimit-Reset' => 1640995200,
            ]),
        ]);

        $this->service->fetchJobsForCategory($category->fresh(), 2);

        Http::assertSentCount(4);

        Http::assertSent(function ($request) use ($category) {
            return $request->url() === config('services.job_api.base_url').'/search' &&
                   $request->data()['query'] === $category->query_name &&
                   $request->data()['num_pages'] === $category->num_page &&
                   $request->data()['date_posted'] === $category->timeframe &&
                   in_array($request->data()['country'], ['US']) &&
                   in_array($request->data()['page'], [1, 2]);
        });
    }

    #[Test]
    public function it_handles_rate_limit_response_gracefully(): void
    {
        $country = Country::factory()->create(['code' => 'US']);
        $category = JobCategory::factory()->create();
        $category->countries()->attach($country);

        $apiKey = ApiKey::factory()->create();

        $this->apiKeyService
            ->method('getAvailableApiKey')
            ->willReturn($apiKey);

        Http::fake([
            '*' => Http::response([], 429),
        ]);

        Log::shouldReceive('info')->once();
        Log::shouldReceive('error')
            ->once()
            ->with('API request failed', \Mockery::subset([
                'category_id' => $category->id,
                'country' => 'US',
                'page' => 1,
                'error' => 'Rate limit exceeded',
            ]));

        $this->service->fetchJobsForCategory($category->fresh(), 1);

        Http::assertSentCount(1);
        $this->apiKeyService
            ->expects($this->never())
            ->method('updateUsage');
    }

    #[Test]
    public function it_handles_http_request_failure(): void
    {
        $country = Country::factory()->create(['code' => 'US']);
        $category = JobCategory::factory()->create();
        $category->countries()->attach($country);

        $apiKey = ApiKey::factory()->create();

        $this->apiKeyService
            ->method('getAvailableApiKey')
            ->willReturn($apiKey);

        Http::fake([
            '*' => Http::response([], 500),
        ]);

        Log::shouldReceive('info')->once();
        Log::shouldReceive('error')
            ->once()
            ->with('API request failed', \Mockery::any());

        $this->service->fetchJobsForCategory($category->fresh(), 1);

        Http::assertSentCount(1);
    }

    #[Test]
    public function it_dispatches_store_job_when_response_is_successful(): void
    {
        $country = Country::factory()->create(['code' => 'US']);
        $category = JobCategory::factory()->create([
            'id' => 123,
            'category_image' => 'tech.jpg',
        ]);
        $category->countries()->attach($country);

        $apiKey = ApiKey::factory()->create();

        $this->apiKeyService
            ->method('getAvailableApiKey')
            ->willReturn($apiKey);

        $this->apiKeyService
            ->expects($this->once())
            ->method('updateUsage');

        $responseData = [
            'data' => [
                [
                    'job_id' => 'test-job-1',
                    'job_title' => 'Software Engineer',
                    'employer_name' => 'Tech Company',
                ],
            ],
        ];

        Http::fake([
            '*' => Http::response($responseData, 200, [
                'X-RateLimit-Requests-Remaining' => 99,
                'X-RateLimit-Reset' => 1640995200,
            ]),
        ]);

        $this->service->fetchJobsForCategory($category->fresh(), 1);

        Queue::assertPushed(\App\Jobs\Store\StoreJobs::class, function ($job) {
            return $job->responseDTO->jobCategory === 123 &&
                   $job->responseDTO->categoryImage === 'tech.jpg' &&
                   count($job->responseDTO->data) === 1 &&
                   $job->responseDTO->data[0]['job_id'] === 'test-job-1';
        });
    }

    #[Test]
    public function it_does_not_dispatch_store_job_when_response_is_not_ok(): void
    {
        $country = Country::factory()->create(['code' => 'US']);
        $category = JobCategory::factory()->create();
        $category->countries()->attach($country);

        $apiKey = ApiKey::factory()->create();

        $this->apiKeyService
            ->method('getAvailableApiKey')
            ->willReturn($apiKey);

        $this->apiKeyService
            ->expects($this->once())
            ->method('updateUsage');

        Http::fake([
            '*' => Http::response([], 202, [
                'X-RateLimit-Requests-Remaining' => 99,
                'X-RateLimit-Reset' => 1640995200,
            ]),
        ]);

        $this->service->fetchJobsForCategory($category->fresh(), 1);

        Queue::assertNothingPushed();
    }

    #[Test]
    public function it_logs_processing_information(): void
    {
        $country = Country::factory()->create(['code' => 'US']);
        $category = JobCategory::factory()->create([
            'id' => 123,
            'name' => 'Software Engineering',
        ]);
        $category->countries()->attach($country);

        $apiKey = ApiKey::factory()->create();

        $this->apiKeyService
            ->method('getAvailableApiKey')
            ->willReturn($apiKey);

        Http::fake([
            '*' => Http::response(['data' => []], 200, [
                'X-RateLimit-Requests-Remaining' => 99,
                'X-RateLimit-Reset' => 1640995200,
            ]),
        ]);

        Log::shouldReceive('info')
            ->once()
            ->with('Processing', [
                'category_id' => 123,
                'category_name' => 'Software Engineering',
                'page' => 1,
                'country' => 'US',
            ]);

        $this->service->fetchJobsForCategory($category->fresh(), 1);
    }

    #[Test]
    public function it_continues_processing_other_pages_when_one_fails(): void
    {
        $country = Country::factory()->create(['code' => 'US']);
        $category = JobCategory::factory()->create();
        $category->countries()->attach($country);

        $apiKey = ApiKey::factory()->create();

        $this->apiKeyService
            ->method('getAvailableApiKey')
            ->willReturn($apiKey);

        Http::fake([
            '*/search?*page=1*' => Http::response([], 500),
            '*/search?*page=2*' => Http::response(['data' => []], 200, [
                'X-RateLimit-Requests-Remaining' => 99,
                'X-RateLimit-Reset' => 1640995200,
            ]),
        ]);

        Log::shouldReceive('info')->twice();
        Log::shouldReceive('error')->once();

        $this->service->fetchJobsForCategory($category->fresh(), 2);

        Http::assertSentCount(2);
        $this->apiKeyService
            ->expects($this->once())
            ->method('updateUsage');
    }

    #[Test]
    public function it_uses_http_retry_mechanism(): void
    {
        $country = Country::factory()->create(['code' => 'US']);
        $category = JobCategory::factory()->create();
        $category->countries()->attach($country);

        $apiKey = ApiKey::factory()->create();

        $this->apiKeyService
            ->method('getAvailableApiKey')
            ->willReturn($apiKey);

        Http::fake([
            '*' => Http::response(['data' => []], 200, [
                'X-RateLimit-Requests-Remaining' => 99,
                'X-RateLimit-Reset' => 1640995200,
            ]),
        ]);

        $this->service->fetchJobsForCategory($category->fresh(), 1);

        Http::assertSentCount(1);
    }
}
