<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\SeoMetaService;
use App\DTO\MetaTagDTO;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeoMetaServiceTest extends TestCase
{
    use RefreshDatabase;

    private SeoMetaService $seoService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up basic config
        config([
            'seo.defaults.title' => 'Test App',
            'seo.defaults.description' => 'Test Description',
            'seo.defaults.keywords' => 'test, keywords',
            'seo.defaults.title_separator' => ' | ',
            'seo.defaults.robots' => 'index,follow',
            'seo.images.default' => '/assets/images/og-default.jpg',
            'seo.images.fallback' => '/assets/images/favicon.ico',
            'seo.images.width' => 1200,
            'seo.images.height' => 630,
            'seo.open_graph.site_name' => 'Test App',
            'seo.open_graph.locale' => 'en_US',
            'seo.open_graph.type' => 'website',
            'seo.twitter.site' => '@testapp',
            'seo.twitter.creator' => '@testapp',
            'seo.twitter.card' => 'summary_large_image',
        ]);
        
        $request = new Request();
        $this->seoService = new SeoMetaService($request);
    }

    /** @test */
    public function it_generates_basic_meta_with_defaults()
    {
        // Act
        $meta = $this->seoService->generateMeta();

        // Assert
        $this->assertInstanceOf(MetaTagDTO::class, $meta);
        $this->assertStringContainsString('Test App', $meta->title);
        $this->assertEquals('Test Description', $meta->description);
        $this->assertEquals('test, keywords', $meta->keywords);
        $this->assertNotNull($meta->og);
        $this->assertNotNull($meta->twitter);
        $this->assertNotNull($meta->discord);
    }

    /** @test */
    public function it_generates_meta_with_custom_parameters()
    {
        // Arrange
        $title = 'Custom Title';
        $description = 'Custom Description';
        $keywords = 'custom, test';
        $image = 'https://test.com/image.jpg';

        // Act
        $meta = $this->seoService->generateMeta($title, $description, $keywords, $image);

        // Assert
        $this->assertStringContainsString($title, $meta->title);
        $this->assertEquals($description, $meta->description);
        $this->assertStringContainsString('custom, test', $meta->keywords); // Keywords are merged with defaults
        $this->assertEquals($image, $meta->og->image);
    }

    /** @test */
    public function it_appends_site_name_to_title()
    {
        // Act
        $meta = $this->seoService->generateMeta('Page Title');

        // Assert
        $this->assertEquals('Page Title | Test App', $meta->title);
    }

    /** @test */
    public function it_limits_description_to_155_characters()
    {
        // Arrange
        $longDescription = str_repeat('This is a very long description that exceeds the normal limit. ', 10);

        // Act
        $meta = $this->seoService->generateMeta(description: $longDescription);

        // Assert
        $this->assertLessThanOrEqual(158, strlen($meta->description)); // 155 + "..."
        $this->assertStringEndsWith('...', $meta->description);
    }

    /** @test */
    public function it_generates_homepage_meta_with_statistics()
    {
        // Arrange
        $availableJobs = 1250;
        $todayAdded = 35;
        $categoriesCount = 12;
        $weeklyAdded = 180;
        $categories = collect([
            (object)['name' => 'PHP'],
            (object)['name' => 'JavaScript'],
            (object)['name' => 'Python']
        ]);

        // Act
        $meta = $this->seoService->generateHomePageMeta(
            $availableJobs,
            $todayAdded,
            $categoriesCount,
            $weeklyAdded,
            $categories
        );

        // Assert
        $this->assertStringContainsString('1250+', $meta->title);
        $this->assertStringContainsString('35 jobs added today', $meta->description);
        $this->assertStringContainsString('180 this week', $meta->description);
        $this->assertStringContainsString('PHP', $meta->description); // Categories are included in description
        $this->assertNotNull($meta->structuredData);
    }

    /** @test */
    public function it_generates_job_detail_meta()
    {
        // Arrange
        $job = new JobListing([
            'job_title' => 'Senior PHP Developer',
            'employer_name' => 'TechCorp',
            'city' => 'San Francisco',
            'is_remote' => false,
            'min_salary' => 120000,
            'max_salary' => 160000,
            'salary_period' => 'year',
            'employment_type' => 'Full-time',
            'description' => '<p>We are looking for a senior PHP developer with extensive experience...</p>',
            'job_category' => 'PHP',
            'country' => 'US',
            'employer_logo' => 'https://techcorp.com/logo.png'
        ]);
        $job->created_at = now();

        // Act
        $meta = $this->seoService->generateJobDetailMeta($job);

        // Assert
        $this->assertStringContainsString('Senior PHP Developer', $meta->title);
        $this->assertStringContainsString('TechCorp', $meta->title);
        $this->assertStringContainsString('San Francisco', $meta->title);
        $this->assertStringContainsString('$120000-$160000', $meta->title);
        $this->assertStringContainsString('Full-time', $meta->description);
        $this->assertEquals('https://techcorp.com/logo.png', $meta->og->image);
        $this->assertEquals('article', $meta->og->type);
        
        // Test structured data
        $this->assertNotNull($meta->structuredData);
        $structuredData = $meta->structuredData->toArray();
        $this->assertEquals('JobPosting', $structuredData['@type']);
        $this->assertEquals('Senior PHP Developer', $structuredData['title']);
    }

    /** @test */
    public function it_generates_jobs_index_meta_with_filters()
    {
        // Arrange
        $request = new Request([
            'category' => 'php',
            'location' => 'New York',
            'remote_only' => false
        ]);
        $seoService = new SeoMetaService($request);
        $totalJobs = 450;

        // Act
        $meta = $seoService->generateJobsIndexMeta($request, $totalJobs);

        // Assert
        $this->assertStringContainsString('Browse PHP Jobs in New York', $meta->title);
        $this->assertStringContainsString('450+ Opportunities', $meta->title);
        $this->assertStringContainsString('450+ job opportunities', $meta->description);
        $this->assertStringContainsString('New York jobs', $meta->keywords);
        $this->assertStringContainsString('php jobs', $meta->keywords);
    }
}