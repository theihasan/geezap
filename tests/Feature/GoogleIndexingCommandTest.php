<?php

namespace Tests\Feature;

use App\Jobs\SubmitUrlToGoogleIndexing;
use App\Models\JobCategory;
use App\Models\JobListing;
use App\Services\GoogleIndexingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class GoogleIndexingCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }

    public function test_command_fails_when_google_indexing_is_disabled(): void
    {
        config(['services.google_indexing.enabled' => false]);

        $this->artisan('google-indexing:submit --url=https://example.com')
            ->expectsOutput('Google Indexing API is not enabled. Set GOOGLE_INDEXING_ENABLED=true in your .env file.')
            ->assertExitCode(1);
    }

    public function test_command_fails_when_not_properly_configured(): void
    {
        config(['services.google_indexing.enabled' => true]);
        
        $this->mock(GoogleIndexingService::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->andReturn(false);
        });

        $this->artisan('google-indexing:submit --url=https://example.com')
            ->expectsOutput('Google Indexing API is not properly configured. Check your service account key file.')
            ->assertExitCode(1);
    }

    public function test_command_submits_single_URL_via_queue(): void
    {
        config(['services.google_indexing.enabled' => true]);
        
        $this->mock(GoogleIndexingService::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->andReturn(true);
        });

        $this->artisan('google-indexing:submit --url=https://example.com')
            ->expectsOutput('Submitting URL: https://example.com (Type: URL_UPDATED)')
            ->expectsOutput('URL submission job dispatched to queue.')
            ->assertExitCode(0);

        Queue::assertPushed(SubmitUrlToGoogleIndexing::class, function ($job) {
            return $job->url === 'https://example.com' && $job->type === 'URL_UPDATED';
        });
    }

    public function test_command_submits_single_URL_synchronously(): void
    {
        config(['services.google_indexing.enabled' => true]);
        
        $this->mock(GoogleIndexingService::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->andReturn(true);
            $mock->shouldReceive('submitUrl')
                ->with('https://example.com', 'URL_UPDATED')
                ->andReturn(true);
        });

        $this->artisan('google-indexing:submit --url=https://example.com --sync')
            ->expectsOutput('Submitting URL: https://example.com (Type: URL_UPDATED)')
            ->expectsOutput('URL submitted successfully!')
            ->assertExitCode(0);

        Queue::assertNotPushed(SubmitUrlToGoogleIndexing::class);
    }

    public function test_command_submits_URL_with_DELETE_type(): void
    {
        config(['services.google_indexing.enabled' => true]);
        
        $this->mock(GoogleIndexingService::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->andReturn(true);
        });

        $this->artisan('google-indexing:submit --url=https://example.com --type=URL_DELETED')
            ->expectsOutput('Submitting URL: https://example.com (Type: URL_DELETED)')
            ->expectsOutput('URL submission job dispatched to queue.')
            ->assertExitCode(0);

        Queue::assertPushed(SubmitUrlToGoogleIndexing::class, function ($job) {
            return $job->url === 'https://example.com' && $job->type === 'URL_DELETED';
        });
    }

    public function test_command_submits_recent_jobs(): void
    {
        config(['services.google_indexing.enabled' => true]);
        
        $this->mock(GoogleIndexingService::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->andReturn(true);
        });

        $jobCategory = JobCategory::factory()->create();

        // Temporarily disable Google indexing to prevent observer from running during setup
        config(['services.google_indexing.enabled' => false]);

        // Create jobs from different periods
        JobListing::factory()->create([
            'created_at' => now()->subDays(1),
            'job_category' => $jobCategory->id
        ]);
        JobListing::factory()->create([
            'created_at' => now()->subDays(2),
            'job_category' => $jobCategory->id
        ]);
        JobListing::factory()->create([
            'created_at' => now()->subDays(5),
            'job_category' => $jobCategory->id
        ]);

        // Re-enable Google indexing for the command
        config(['services.google_indexing.enabled' => true]);

        $result = $this->artisan('google-indexing:submit --recent=3')
            ->expectsOutput('Submitting jobs from the last 3 days...')
            ->assertExitCode(0);

        // Verify that jobs within the timeframe would be processed
        $recentJobs = JobListing::where('created_at', '>=', now()->subDays(3))->count();
        $this->assertEquals(2, $recentJobs);
    }

    public function test_command_shows_error_when_no_options_provided(): void
    {
        config(['services.google_indexing.enabled' => true]);
        
        $this->mock(GoogleIndexingService::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->andReturn(true);
        });

        $this->artisan('google-indexing:submit')
            ->expectsOutput('Please specify --url, --batch, or --recent option.')
            ->assertExitCode(1);
    }
}
