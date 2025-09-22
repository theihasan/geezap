<?php

namespace Tests\Feature;

use App\Jobs\SubmitUrlToGoogleIndexing;
use App\Models\JobCategory;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class GoogleIndexingJobIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        config(['services.google_indexing.enabled' => true]);
    }

    public function test_creating_a_job_listing_dispatches_URL_UPDATED_job(): void
    {
        $jobCategory = JobCategory::factory()->create();
        
        $jobListing = JobListing::factory()->create([
            'job_title' => 'Software Engineer',
            'slug' => 'software-engineer-123',
            'job_category' => $jobCategory->id
        ]);

        Queue::assertPushed(SubmitUrlToGoogleIndexing::class, function ($job) use ($jobListing) {
            return $job->url === route('job.show', $jobListing->slug) && 
                   $job->type === 'URL_UPDATED';
        });
    }

    public function test_updating_a_job_listing_dispatches_URL_UPDATED_job(): void
    {
        $jobCategory = JobCategory::factory()->create();
        $jobListing = JobListing::factory()->create(['job_category' => $jobCategory->id]);
        
        Queue::assertPushed(SubmitUrlToGoogleIndexing::class);
        Queue::fake(); // Reset queue for the update test
        
        $jobListing->update(['job_title' => 'Updated Job Title']);

        Queue::assertPushed(SubmitUrlToGoogleIndexing::class, function ($job) use ($jobListing) {
            return $job->url === route('job.show', $jobListing->slug) && 
                   $job->type === 'URL_UPDATED';
        });
    }

    public function test_deleting_a_job_listing_dispatches_URL_DELETED_job(): void
    {
        $jobCategory = JobCategory::factory()->create();
        $jobListing = JobListing::factory()->create(['job_category' => $jobCategory->id]);
        
        Queue::assertPushed(SubmitUrlToGoogleIndexing::class);
        Queue::fake(); // Reset queue for the delete test
        
        $slug = $jobListing->slug;
        $jobListing->delete();

        Queue::assertPushed(SubmitUrlToGoogleIndexing::class, function ($job) use ($slug) {
            return $job->url === route('job.show', $slug) && 
                   $job->type === 'URL_DELETED';
        });
    }

    public function test_google_indexing_jobs_are_not_dispatched_when_disabled(): void
    {
        config(['services.google_indexing.enabled' => false]);
        
        $jobCategory = JobCategory::factory()->create();
        JobListing::factory()->create(['job_category' => $jobCategory->id]);

        Queue::assertNotPushed(SubmitUrlToGoogleIndexing::class);
    }

    public function test_job_observer_handles_route_generation_errors_gracefully(): void
    {
        // This test ensures the observer doesn't break if route generation fails
        $jobCategory = JobCategory::factory()->create();
        $jobListing = JobListing::factory()->make(['slug' => null, 'job_category' => $jobCategory->id]);
        
        // The save should not throw an exception even if slug is null
        $this->expectNotToPerformAssertions();
        $jobListing->save();
    }
}
