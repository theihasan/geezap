<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\JobCategory;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SitemapGenerateTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_sitemap_including_only_categories_with_jobs_and_recent_job_urls(): void
    {
        $sitemapPath = storage_path('app/sitemap.xml');
        if (file_exists($sitemapPath)) {
            @unlink($sitemapPath);
        }

        $categoryWithJobs = JobCategory::factory()->create([
            'name' => 'Engineering',
        ]);

        $categoryWithoutJobs = JobCategory::factory()->create([
            'name' => 'Empty Category',
        ]);

        JobListing::factory()->count(2)->create([
            'job_category' => $categoryWithJobs->id,
            'posted_at' => now()->subDays(1),
            'expired_at' => now()->addDays(7),
        ]);

        $this->artisan('sitemap:generate')->assertExitCode(0);

        $this->assertFileExists($sitemapPath);

        $xml = file_get_contents($sitemapPath);

        $this->assertStringContainsString('/categories/' . $categoryWithJobs->slug, $xml);
        $this->assertStringNotContainsString('/categories/' . $categoryWithoutJobs->slug, $xml);

        foreach ($categoryWithJobs->jobs as $job) {
            $this->assertStringContainsString('/jobs/' . $job->job_id, $xml);
        }
    }
}


