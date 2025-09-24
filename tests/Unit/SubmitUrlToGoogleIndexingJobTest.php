<?php

namespace Tests\Unit;

use App\Jobs\SubmitUrlToGoogleIndexing;
use App\Services\GoogleIndexingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class SubmitUrlToGoogleIndexingJobTest extends TestCase
{
    public function test_job_submits_url_successfully(): void
    {
        $mockService = Mockery::mock(GoogleIndexingService::class);
        $mockService->shouldReceive('submitUrl')
            ->once()
            ->with('https://example.com/test', 'URL_UPDATED')
            ->andReturn(true);

        $this->app->instance(GoogleIndexingService::class, $mockService);

        $job = new SubmitUrlToGoogleIndexing('https://example.com/test', 'URL_UPDATED');
        $job->handle($mockService);

        $this->assertTrue(true); // Job completed without exception
    }

    public function test_job_logs_warning_on_failure(): void
    {
        $mockService = Mockery::mock(GoogleIndexingService::class);
        $mockService->shouldReceive('submitUrl')
            ->once()
            ->with('https://example.com/test', 'URL_UPDATED')
            ->andReturn(false);

        Log::shouldReceive('warning')
            ->once()
            ->with('Google Indexing API submission failed', [
                'url' => 'https://example.com/test',
                'type' => 'URL_UPDATED',
                'attempt' => 1
            ]);

        $job = new SubmitUrlToGoogleIndexing('https://example.com/test', 'URL_UPDATED');
        $job->handle($mockService);
    }

    public function test_job_logs_error_on_exception(): void
    {
        $mockService = Mockery::mock(GoogleIndexingService::class);
        $mockService->shouldReceive('submitUrl')
            ->once()
            ->andThrow(new \Exception('API Error'));

        Log::shouldReceive('error')
            ->once()
            ->with('Google Indexing API job failed', [
                'url' => 'https://example.com/test',
                'type' => 'URL_UPDATED',
                'error' => 'API Error',
                'attempt' => 1
            ]);

        $job = new SubmitUrlToGoogleIndexing('https://example.com/test', 'URL_UPDATED');
        $job->handle($mockService);
    }

    public function test_job_has_correct_properties(): void
    {
        $job = new SubmitUrlToGoogleIndexing('https://example.com/test', 'URL_DELETED');

        $this->assertEquals('https://example.com/test', $job->url);
        $this->assertEquals('URL_DELETED', $job->type);
        $this->assertEquals(3, $job->tries);
        $this->assertEquals(60, $job->backoff);
    }
}
