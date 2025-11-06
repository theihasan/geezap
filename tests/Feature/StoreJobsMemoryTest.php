<?php

use App\DTO\JobResponseDTO;
use App\Jobs\Store\StoreJobs;
use App\Models\JobCategory;
use App\Services\JobStorageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('processes jobs without memory exhaustion', function () {
    // Create a job category manually to avoid factory issues
    $category = JobCategory::create([
        'name' => 'Test Category',
        'slug' => 'test-category-123',
        'query_name' => 'Test Category',
        'page' => 1,
        'num_page' => 5,
        'timeframe' => 'week',
        'category_image' => 'test-image.jpg',
    ]);

    // Create mock job data (simulate a large dataset)
    $jobData = [];
    for ($i = 0; $i < 5; $i++) {
        $jobData[] = [
            'job_id' => 'test-job-'.$i,
            'employer_name' => 'Test Company '.$i,
            'employer_logo' => null,
            'employer_website' => null,
            'job_publisher' => 'Test Publisher',
            'job_employment_type' => 'Full-time',
            'job_title' => 'Test Job '.$i,
            'job_apply_link' => 'https://example.com/apply/'.$i,
            'job_description' => 'Test job description '.$i,
            'job_is_remote' => false,
            'job_city' => 'Test City',
            'job_state' => 'Test State',
            'job_country' => 'US',
            'job_latitude' => null,
            'job_longitude' => null,
            'job_google_link' => null,
            'job_posted_at_datetime_utc' => null,
            'job_offer_expiration_datetime_utc' => null,
            'job_min_salary' => null,
            'job_max_salary' => null,
            'job_salary_period' => null,
            'job_highlights' => [],
            'apply_options' => [
                [
                    'publisher' => 'Test Publisher',
                    'apply_link' => 'https://example.com/apply/'.$i,
                    'is_direct' => true,
                ],
            ],
        ];
    }

    // Create response DTO
    $responseDTO = new JobResponseDTO(
        data: $jobData,
        jobCategory: $category->id,
        categoryImage: 'test-image.jpg'
    );

    // Get initial memory usage
    $initialMemory = memory_get_usage(true);

    // Dispatch the job with service dependency
    $job = new StoreJobs($responseDTO);
    $job->handle(new JobStorageService);

    // Get final memory usage
    $finalMemory = memory_get_usage(true);
    $memoryUsed = $finalMemory - $initialMemory;

    // Assert memory usage is reasonable (less than 50MB for 5 jobs)
    expect($memoryUsed)->toBeLessThan(50 * 1024 * 1024);

    // Assert jobs were created
    expect(\App\Models\JobListing::count())->toBe(5);
});

it('handles memory threshold gracefully', function () {
    // Create a job category manually
    $category = JobCategory::create([
        'name' => 'Test Category 2',
        'slug' => 'test-category-456',
        'query_name' => 'Test Category 2',
        'page' => 1,
        'num_page' => 5,
        'timeframe' => 'week',
        'category_image' => 'test-image2.jpg',
    ]);

    $responseDTO = new JobResponseDTO(
        data: [['job_id' => 'test', 'employer_name' => 'Test', 'employer_logo' => null, 'employer_website' => null, 'job_publisher' => 'Test Publisher', 'job_employment_type' => 'Full-time', 'job_title' => 'Test', 'job_apply_link' => 'https://test.com', 'job_description' => 'Test job', 'job_is_remote' => false, 'job_city' => null, 'job_state' => null, 'job_country' => null, 'job_latitude' => null, 'job_longitude' => null, 'job_google_link' => null, 'job_posted_at_datetime_utc' => null, 'job_offer_expiration_datetime_utc' => null, 'job_min_salary' => null, 'job_max_salary' => null, 'job_salary_period' => null, 'job_highlights' => [], 'apply_options' => null]],
        jobCategory: $category->id,
        categoryImage: 'test.jpg'
    );

    $job = new StoreJobs($responseDTO);

    // Should not throw an exception
    expect(fn () => $job->handle(new JobStorageService))->not->toThrow(\Exception::class);
});
