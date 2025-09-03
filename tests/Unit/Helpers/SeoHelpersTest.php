<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;


use App\Models\JobListing;
use App\Models\JobCategory;
use App\DTO\MetaTagDTO;
use App\Services\SeoMetaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SeoHelpersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure helper functions are loaded
        if (!function_exists('seo_meta')) {
            require_once app_path('Helpers/SeoHelper.php');
        }
        
        // Set up basic config
        config([
            'seo.defaults.title' => 'Test App',
            'seo.defaults.description' => 'Test Description',
            'seo.defaults.keywords' => 'test, keywords',
            'seo.images.default' => '/assets/images/og-default.jpg',
            'seo.images.fallback' => '/assets/images/favicon.ico',
        ]);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function seo_meta_helper_works_without_parameters()
    {
        // Act
        $meta = seo_meta();

        // Assert
        $this->assertInstanceOf(MetaTagDTO::class, $meta);
        $this->assertNotEmpty($meta->title);
        $this->assertNotEmpty($meta->description);
        $this->assertNotNull($meta->og);
        $this->assertNotNull($meta->twitter);
        $this->assertNotNull($meta->discord);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function seo_meta_helper_works_with_custom_parameters()
    {
        // Act
        $meta = seo_meta(
            'Custom Helper Title',
            'Custom helper description',
            'helper, test, keywords',
            'https://example.com/helper.jpg'
        );

        // Assert
        $this->assertInstanceOf(MetaTagDTO::class, $meta);
        $this->assertStringContains('Custom Helper Title', $meta->title);
        $this->assertEquals('Custom helper description', $meta->description);
        $this->assertEquals('helper, test, keywords', $meta->keywords);
        $this->assertEquals('https://example.com/helper.jpg', $meta->og->image);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function seo_job_meta_helper_works_with_job_model()
    {
        // Arrange
        $job = JobListing::factory()->create([
            'job_title' => 'Helper Test Job',
            'employer_name' => 'Helper Test Company',
            'city' => 'Helper City'
        ]);

        // Act
        $meta = seo_job_meta($job);

        // Assert
        $this->assertInstanceOf(MetaTagDTO::class, $meta);
        $this->assertStringContains('Helper Test Job', $meta->title);
        $this->assertStringContains('Helper Test Company', $meta->title);
        $this->assertStringContains('Helper City', $meta->title);
        $this->assertEquals('article', $meta->og->type);
        $this->assertNotNull($meta->structuredData);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function seo_jobs_index_meta_helper_works_with_request_and_total()
    {
        // Arrange
        $request = new Request([
            'category' => 'php',
            'location' => 'Test City'
        ]);
        $totalJobs = 250;

        // Act
        $meta = seo_jobs_index_meta($request, $totalJobs);

        // Assert
        $this->assertInstanceOf(MetaTagDTO::class, $meta);
        $this->assertStringContains('PHP', $meta->title);
        $this->assertStringContains('Test City', $meta->title);
        $this->assertStringContains('250+', $meta->title);
        $this->assertStringContains('250+ job opportunities', $meta->description);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function seo_home_meta_helper_works_with_statistics()
    {
        // Arrange
        $availableJobs = 1500;
        $todayAdded = 45;
        $categoriesCount = 15;
        $weeklyAdded = 250;
        $jobCategories = collect([
            (object)['name' => 'PHP'],
            (object)['name' => 'JavaScript'],
            (object)['name' => 'Python']
        ]);

        // Act
        $meta = seo_home_meta($availableJobs, $todayAdded, $categoriesCount, $weeklyAdded, $jobCategories);

        // Assert
        $this->assertInstanceOf(MetaTagDTO::class, $meta);
        $this->assertStringContains('1500+', $meta->title);
        $this->assertStringContains('45 jobs added today', $meta->description);
        $this->assertStringContains('250 this week', $meta->description);
        $this->assertStringContains('PHP, JavaScript, Python', $meta->description);
        $this->assertNotNull($meta->structuredData);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function helpers_return_same_results_as_service_methods()
    {
        // Arrange
        $title = 'Comparison Test';
        $description = 'Testing helper vs service';

        // Act
        $helperMeta = seo_meta($title, $description);
        $serviceMeta = app(SeoMetaService::class)->generateMeta($title, $description);

        // Assert
        $this->assertEquals($serviceMeta->title, $helperMeta->title);
        $this->assertEquals($serviceMeta->description, $helperMeta->description);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function seo_job_meta_helper_handles_null_job_gracefully()
    {
        // This test ensures the helper handles edge cases
        $this->expectException(\TypeError::class);
        seo_job_meta(null);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function helpers_work_with_empty_parameters()
    {
        // Act
        $meta1 = seo_meta('', '', '', '');
        $meta2 = seo_jobs_index_meta(new Request(), 0);

        // Assert
        $this->assertInstanceOf(MetaTagDTO::class, $meta1);
        $this->assertInstanceOf(MetaTagDTO::class, $meta2);
        $this->assertNotEmpty($meta1->title); // Should fall back to defaults
        $this->assertStringContains('0+', $meta2->title);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function seo_home_meta_helper_handles_empty_categories()
    {
        // Arrange
        $emptyCategories = collect([]);

        // Act
        $meta = seo_home_meta(100, 5, 0, 20, $emptyCategories);

        // Assert
        $this->assertInstanceOf(MetaTagDTO::class, $meta);
        $this->assertStringContains('100+', $meta->title);
        $this->assertStringContains('5 jobs added today', $meta->description);
        // Should handle empty categories gracefully
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function helpers_maintain_consistent_return_types()
    {
        // Test that all helper functions return MetaTagDTO
        $meta1 = seo_meta();
        $meta2 = seo_home_meta(100, 5, 5, 25, collect());
        $meta3 = seo_jobs_index_meta(new Request(), 50);
        
        $job = JobListing::factory()->create([
            'job_title' => 'Test Job',
            'employer_name' => 'Test Company',
            'description' => 'Test description',
        ]);
        $meta4 = seo_job_meta($job);

        // Assert
        $this->assertInstanceOf(MetaTagDTO::class, $meta1);
        $this->assertInstanceOf(MetaTagDTO::class, $meta2);
        $this->assertInstanceOf(MetaTagDTO::class, $meta3);
        $this->assertInstanceOf(MetaTagDTO::class, $meta4);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function helpers_work_in_different_request_contexts()
    {
        // Simulate different request contexts
        $contexts = [
            ['HTTP_HOST' => 'localhost'],
            ['HTTP_HOST' => 'geezap.com'],
        ];

        foreach ($contexts as $context) {
            // Set server variables
            foreach ($context as $key => $value) {
                $_SERVER[$key] = $value;
            }

            // Act
            $meta = seo_meta('Context Test');

            // Assert
            $this->assertInstanceOf(MetaTagDTO::class, $meta);
            $this->assertStringContains('Context Test', $meta->title);
        }

        // Clean up
        unset($_SERVER['HTTP_HOST']);
    }
}