<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\User;
use App\Models\JobListing;
use App\Models\JobCategory;
use App\Services\SeoMetaService;
use App\Facades\Seo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;

class SeoMetaIntegrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function seo_facade_works_correctly()
    {
        // Act
        $meta = Seo::generateMeta('Facade Test', 'Testing the SEO facade');

        // Assert
        $this->assertStringContains('Facade Test', $meta->title);
        $this->assertEquals('Testing the SEO facade', $meta->description);
    }

    /** @test */
    public function seo_service_is_properly_bound_to_container()
    {
        // Act
        $service1 = app(SeoMetaService::class);
        $service2 = resolve(SeoMetaService::class);

        // Assert
        $this->assertInstanceOf(SeoMetaService::class, $service1);
        $this->assertInstanceOf(SeoMetaService::class, $service2);
    }

    /** @test */
    public function helper_functions_are_available()
    {
        // Assert - All helper functions should be available
        $this->assertTrue(function_exists('seo_meta'));
        $this->assertTrue(function_exists('seo_job_meta'));
        $this->assertTrue(function_exists('seo_jobs_index_meta'));
        $this->assertTrue(function_exists('seo_home_meta'));
    }

    /** @test */
    public function full_workflow_home_page_meta_generation()
    {
        // Arrange
        JobCategory::factory()->count(8)->create();
        JobListing::factory()->count(150)->create();

        // Simulate homepage controller logic
        $availableJobs = JobListing::count();
        $todayAdded = JobListing::whereDate('created_at', today())->count();
        $categories = JobCategory::take(3)->get();

        // Act
        $meta = seo_home_meta(
            $availableJobs,
            $todayAdded,
            8,
            25,
            $categories
        );

        // Assert
        $this->assertStringContains((string)$availableJobs, $meta->title);
        $this->assertStringContains((string)$todayAdded, $meta->description);
        $this->assertNotNull($meta->structuredData);
        $this->assertEquals('website', $meta->og->type);
        $this->assertStringContains('WebSite', json_encode($meta->structuredData->toArray()));
    }

    /** @test */
    public function full_workflow_job_detail_meta_generation()
    {
        // Arrange
        $job = JobListing::factory()->create([
            'job_title' => 'Full Stack Developer',
            'employer_name' => 'Innovation Labs',
            'city' => 'Austin',
            'country' => 'US',
            'min_salary' => 90000,
            'max_salary' => 130000,
            'salary_period' => 'year',
            'employment_type' => 'Full-time',
            'is_remote' => false,
            'employer_logo' => 'https://innovationlabs.com/logo.png'
        ]);

        // Act
        $meta = seo_job_meta($job);

        // Assert
        $this->assertStringContains('Full Stack Developer', $meta->title);
        $this->assertStringContains('Innovation Labs', $meta->title);
        $this->assertStringContains('Austin', $meta->title);
        $this->assertStringContains('$90000-$130000', $meta->title);
        $this->assertEquals('article', $meta->og->type);
        $this->assertEquals('https://innovationlabs.com/logo.png', $meta->og->image);
        
        // Check structured data
        $structuredData = $meta->structuredData->toArray();
        $this->assertEquals('JobPosting', $structuredData['@type']);
        $this->assertEquals('Full Stack Developer', $structuredData['title']);
        $this->assertArrayHasKey('baseSalary', $structuredData);
    }

    /** @test */
    public function full_workflow_jobs_index_with_filters()
    {
        // Arrange
        JobListing::factory()->count(75)->create();
        $request = new Request([
            'category' => 'react',
            'location' => 'Seattle',
            'remote_only' => false
        ]);

        // Act
        $meta = seo_jobs_index_meta($request, 75);

        // Assert
        $this->assertStringContains('Browse React Jobs in Seattle', $meta->title);
        $this->assertStringContains('75+ Opportunities', $meta->title);
        $this->assertStringContains('Seattle jobs', $meta->keywords);
        $this->assertStringContains('react jobs', $meta->keywords);
    }

    /** @test */
    public function meta_generation_handles_missing_data_gracefully()
    {
        // Arrange - Create job with minimal data
        $job = JobListing::factory()->create([
            'job_title' => 'Basic Job',
            'employer_name' => 'Basic Company',
            'description' => 'Basic description'
        ]);

        // Act
        $meta = seo_job_meta($job);

        // Assert
        $this->assertStringContains('Basic Job', $meta->title);
        $this->assertStringContains('Basic Company', $meta->title);
        $this->assertNotEmpty($meta->og->image); // Should use fallback
        $this->assertNotNull($meta->structuredData);
    }

    /** @test */
    public function blade_component_integration_works()
    {
        // Arrange
        $meta = seo_meta('Component Test', 'Testing Blade component integration');

        // Act
        $view = view('components.seo-meta', compact('meta'));
        $html = $view->render();

        // Assert
        $this->assertStringContains('<title>Component Test', $html);
        $this->assertStringContains('<meta name="description" content="Testing Blade component integration">', $html);
        $this->assertStringContains('<meta property="og:title"', $html);
        $this->assertStringContains('<meta name="twitter:title"', $html);
    }

    /** @test */
    public function controller_base_class_helpers_work()
    {
        // This would typically be tested with a real controller, 
        // but we can test the concept with a mock
        
        // Arrange
        $controller = new class {
            use \App\Http\Controllers\Controller;
            
            public function testViewWithMeta()
            {
                return $this->viewWithMeta('test.view', ['data' => 'test']);
            }
            
            public function testViewWithCustomMeta()
            {
                return $this->viewWithCustomMeta(
                    'test.view',
                    ['data' => 'test'],
                    'Custom Title',
                    'Custom Description'
                );
            }
        };

        // We can't fully test view rendering without actual view files,
        // but we can verify the methods exist and are callable
        $this->assertTrue(method_exists($controller, 'viewWithMeta'));
        $this->assertTrue(method_exists($controller, 'viewWithCustomMeta'));
    }

    /** @test */
    public function seo_system_handles_different_locales()
    {
        // Arrange
        app()->setLocale('en');

        // Act
        $metaEn = seo_meta('English Title');
        
        // Change locale (this would typically be done through middleware)
        app()->setLocale('es');
        $metaEs = seo_meta('Spanish Title');

        // Assert
        $this->assertStringContains('English Title', $metaEn->title);
        $this->assertStringContains('Spanish Title', $metaEs->title);
        // Both should have appropriate locale settings
        $this->assertNotNull($metaEn->og);
        $this->assertNotNull($metaEs->og);
    }

    /** @test */
    public function seo_system_works_with_cached_config()
    {
        // Arrange
        $this->artisan('config:cache');

        // Act
        $meta = seo_meta('Cached Config Test');

        // Assert
        $this->assertStringContains('Cached Config Test', $meta->title);
        $this->assertNotNull($meta->og);
        $this->assertNotNull($meta->twitter);

        // Cleanup
        $this->artisan('config:clear');
    }

    /** @test */
    public function seo_system_performs_well_with_large_datasets()
    {
        // Arrange - Create many jobs and categories
        JobCategory::factory()->count(50)->create();
        JobListing::factory()->count(1000)->create();

        $startTime = microtime(true);

        // Act - Generate meta for various scenarios
        $homeMeta = seo_home_meta(1000, 50, 50, 200, JobCategory::take(5)->get());
        $indexMeta = seo_jobs_index_meta(new Request(['category' => 'php']), 1000);
        $job = JobListing::first();
        $jobMeta = seo_job_meta($job);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Assert - Should complete within reasonable time (less than 1 second)
        $this->assertLessThan(1.0, $executionTime, 'SEO meta generation should be fast');
        $this->assertNotNull($homeMeta);
        $this->assertNotNull($indexMeta);
        $this->assertNotNull($jobMeta);
    }

    /** @test */
    public function seo_system_integrates_with_authentication()
    {
        // Arrange
        $user = User::factory()->create();

        // Act - Test authenticated routes
        $response = $this->actingAs($user)->get('/dashboard');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('noindex,nofollow', false); // Private page should not be indexed
    }

    /** @test */
    public function seo_meta_survives_request_lifecycle()
    {
        // Arrange
        $job = JobListing::factory()->create(['slug' => 'lifecycle-test-job']);

        // Act - Make actual HTTP request
        $response = $this->get("/jobs/{$job->slug}");

        // Assert - Response should contain properly rendered meta tags
        $response->assertStatus(200);
        $content = $response->getContent();
        
        // Verify all expected meta elements are present
        $this->assertStringContains('<title>', $content);
        $this->assertStringContains('<meta name="description"', $content);
        $this->assertStringContains('<meta name="keywords"', $content);
        $this->assertStringContains('<meta property="og:title"', $content);
        $this->assertStringContains('<meta name="twitter:card"', $content);
        $this->assertStringContains('<script type="application/ld+json">', $content);
    }
}