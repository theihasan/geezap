<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;

use App\Models\User;
use App\Models\JobListing;
use App\Models\JobCategory;
use App\Services\SeoMetaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class SeoMetaControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[PHPUnit\Framework\Attributes\Test]
    public function homepage_generates_correct_meta_tags()
    {
        // Arrange
        JobCategory::factory()->count(5)->create();
        JobListing::factory()->count(100)->create();

        // Act
        $response = $this->get('/');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('<title>', false);
        $response->assertSee('Find Your Next Tech Job', false);
        $response->assertSee('og:title', false);
        $response->assertSee('twitter:title', false);
        $response->assertSee('application/ld+json', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function job_detail_page_generates_job_specific_meta()
    {
        // Arrange
        $job = JobListing::factory()->create([
            'job_title' => 'Senior Laravel Developer',
            'employer_name' => 'TechCorp Inc',
            'city' => 'San Francisco',
            'slug' => 'senior-laravel-developer-techcorp'
        ]);

        // Act
        $response = $this->get("/jobs/{$job->slug}");

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Senior Laravel Developer', false);
        $response->assertSee('TechCorp Inc', false);
        $response->assertSee('San Francisco', false);
        $response->assertSee('og:type" content="article"', false);
        $response->assertSee('"@type":"JobPosting"', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function jobs_index_generates_filtered_meta()
    {
        // Arrange
        JobListing::factory()->count(50)->create();

        // Act
        $response = $this->get('/jobs?category=php&location=New York');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Browse PHP Jobs in New York', false);
        $response->assertSee('50+ Opportunities', false);
        $response->assertSee('og:title', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function jobs_categories_page_generates_correct_meta()
    {
        // Arrange
        JobCategory::factory()->count(10)->create();

        // Act
        $response = $this->get('/categories');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Job Categories', false);
        $response->assertSee('10 Tech Categories', false);
        $response->assertSee('og:title', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function about_page_uses_route_configuration()
    {
        // Act
        $response = $this->get('/about');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('About Geezap | AI-Powered Job Aggregation Platform', false);
        $response->assertSee('og:title', false);
        $response->assertSee('twitter:title', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function contact_page_generates_appropriate_meta()
    {
        // Act
        $response = $this->get('/contact');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Contact Us | Get Support', false);
        $response->assertSee('og:title', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function privacy_policy_page_generates_appropriate_meta()
    {
        // Act
        $response = $this->get('/privacy-policy');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Privacy Policy | Your Data Protection', false);
        $response->assertSee('og:title', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function terms_page_generates_appropriate_meta()
    {
        // Act
        $response = $this->get('/terms');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Terms of Service | User Agreement', false);
        $response->assertSee('og:title', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function dashboard_has_noindex_meta_for_privacy()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->get('/dashboard');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Dashboard | Manage Your Job Search', false);
        $response->assertSee('noindex,nofollow', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function applications_page_has_noindex_meta_for_privacy()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->get('/applications');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('My Applications | Track Job Applications', false);
        $response->assertSee('noindex,nofollow', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function job_preferences_page_generates_custom_meta()
    {
        // Act
        $response = $this->get('/job-preferences');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Job Preferences | Customize Your Experience', false);
        $response->assertSee('personalized job recommendations', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function cover_letter_page_generates_appropriate_meta()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->get('/cover-letter');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('AI Cover Letter Generator | Create Professional Cover Letters', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function profile_preferences_has_privacy_meta()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->get('/profile/preferences');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Job Preferences | Customize Your Job Search', false);
        $response->assertSee('noindex,nofollow', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function all_pages_have_canonical_urls()
    {
        // Test various pages for canonical URLs
        $pages = [
            '/',
            '/about',
            '/contact',
            '/privacy-policy',
            '/terms',
            '/categories'
        ];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertSee('<link rel="canonical"', false);
        }
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function all_public_pages_have_structured_data()
    {
        // Test various public pages for structured data
        $pages = [
            '/',
            '/about',
            '/contact',
            '/categories'
        ];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertSee('application/ld+json', false);
            $response->assertSee('@context', false);
            $response->assertSee('https://schema.org', false);
        }
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function job_listings_include_proper_opengraph_images()
    {
        // Arrange
        $job = JobListing::factory()->create([
            'employer_logo' => 'https://example.com/logo.png',
            'slug' => 'test-job-with-logo'
        ]);

        // Act
        $response = $this->get("/jobs/{$job->slug}");

        // Assert
        $response->assertSee('https://example.com/logo.png', false);
        $response->assertSee('og:image:width', false);
        $response->assertSee('og:image:height', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function pages_use_fallback_image_when_no_specific_image()
    {
        // Act
        $response = $this->get('/about');

        // Assert
        $response->assertSee('og:image', false);
        // Should use fallback image from config
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function meta_descriptions_are_within_seo_limits()
    {
        // Test that descriptions don't exceed 155 characters
        $pages = [
            '/',
            '/about',
            '/contact',
            '/categories'
        ];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $content = $response->getContent();
            
            // Extract description content
            preg_match('/<meta name="description" content="([^"]*)"/', $content, $matches);
            
            if (isset($matches[1])) {
                $this->assertLessThanOrEqual(155, strlen($matches[1]), 
                    "Description too long for page: {$page}");
            }
        }
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function twitter_cards_have_proper_format()
    {
        // Act
        $response = $this->get('/');

        // Assert
        $response->assertSee('twitter:card', false);
        $response->assertSee('twitter:title', false);
        $response->assertSee('twitter:description', false);
        $response->assertSee('summary_large_image', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function remote_job_shows_remote_in_meta()
    {
        // Arrange
        $remoteJob = JobListing::factory()->create([
            'job_title' => 'Remote React Developer',
            'is_remote' => true,
            'slug' => 'remote-react-developer'
        ]);

        // Act
        $response = $this->get("/jobs/{$remoteJob->slug}");

        // Assert
        $response->assertSee('Remote', false);
        $response->assertSee('Remote position', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function job_with_salary_shows_salary_in_meta()
    {
        // Arrange
        $jobWithSalary = JobListing::factory()->create([
            'job_title' => 'Senior Developer',
            'min_salary' => 120000,
            'max_salary' => 160000,
            'salary_period' => 'year',
            'slug' => 'senior-developer-with-salary'
        ]);

        // Act
        $response = $this->get("/jobs/{$jobWithSalary->slug}");

        // Assert
        $response->assertSee('$120000-$160000', false);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function pages_have_proper_viewport_meta()
    {
        // Act
        $response = $this->get('/');

        // Assert
        $response->assertSee('name="viewport" content="width=device-width, initial-scale=1"', false);
    }
}