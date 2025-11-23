<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\DTO\JobResponseDTO;
use App\Jobs\GetJobData;
use App\Jobs\Store\StoreJobs;
use App\Models\ApiKey;
use App\Models\Country;
use App\Models\JobCategory;
use App\Models\JobListing;
use App\Services\ApiKeyService;
use App\Services\JobFetchService;
use App\Services\JobStorageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EndToEndJobDispatchTest extends TestCase
{
    use RefreshDatabase;

    public function test_completes_end_to_end_job_dispatch_flow_successfully(): void
    {
        $apiKey = ApiKey::create([
            'api_key' => 'test-api-key-123',
            'api_name' => 'job',
            'sent_request' => 10,
            'request_remaining' => 990,
            'rate_limit_reset' => now()->addHour(),
        ]);

        $countries = collect([
            Country::create(['name' => 'United States', 'code' => 'US', 'is_active' => true]),
        ]);

        $category = JobCategory::factory()->create([
            'name' => 'Software Engineering',
            'slug' => 'software-engineering',
            'query_name' => 'Software Engineer',
            'category_image' => 'tech.png',
            'num_page' => 1,
        ]);
        $category->countries()->attach($countries->pluck('id'));

        $mockApiResponse = [
            'data' => [
                [
                    'job_id' => 'ext-job-123',
                    'employer_name' => 'Tech Corp',
                    'employer_logo' => 'https://example.com/logo.png',
                    'employer_website' => 'https://techcorp.com',
                    'job_publisher' => 'TechJobs',
                    'job_employment_type' => 'Full-time',
                    'job_title' => 'Senior Software Engineer',
                    'job_apply_link' => 'https://techcorp.com/apply/123',
                    'job_description' => 'Build awesome software products...',
                    'job_is_remote' => true,
                    'job_city' => null,
                    'job_state' => null,
                    'job_country' => null,
                    'job_latitude' => null,
                    'job_longitude' => null,
                    'job_google_link' => null,
                    'job_posted_at_datetime_utc' => '2023-01-01T00:00:00Z',
                    'job_offer_expiration_datetime_utc' => '2023-02-01T00:00:00Z',
                    'job_min_salary' => 120000.0,
                    'job_max_salary' => 180000.0,
                    'job_salary_period' => 'yearly',
                    'job_highlights' => [
                        'Benefits' => ['Health insurance', 'Remote work'],
                        'Qualifications' => ['5+ years experience', 'Computer Science degree'],
                        'Responsibilities' => ['Design systems', 'Code reviews'],
                    ],
                    'apply_options' => [
                        [
                            'publisher' => 'LinkedIn',
                            'apply_link' => 'https://linkedin.com/apply/123',
                            'is_direct' => true,
                        ],
                        [
                            'publisher' => 'Indeed',
                            'apply_link' => 'https://indeed.com/apply/123',
                            'is_direct' => false,
                        ],
                    ],
                ],
                [
                    'job_id' => 'ext-job-456',
                    'employer_name' => 'Startup Inc',
                    'employer_logo' => null,
                    'employer_website' => 'https://startup.com',
                    'job_publisher' => 'StartupJobs',
                    'job_employment_type' => 'Contract',
                    'job_title' => 'Frontend Developer',
                    'job_apply_link' => 'https://startup.com/apply/456',
                    'job_description' => 'Create beautiful user interfaces...',
                    'job_is_remote' => false,
                    'job_city' => 'San Francisco',
                    'job_state' => 'CA',
                    'job_country' => 'USA',
                    'job_latitude' => 37.7749,
                    'job_longitude' => -122.4194,
                    'job_google_link' => 'https://maps.google.com/place/sf',
                    'job_posted_at_datetime_utc' => '2023-01-02T00:00:00Z',
                    'job_offer_expiration_datetime_utc' => null,
                    'job_min_salary' => 50.0,
                    'job_max_salary' => 75.0,
                    'job_salary_period' => 'hourly',
                    'job_highlights' => [
                        'Benefits' => ['Flexible hours'],
                        'Qualifications' => ['3+ years React'],
                        'Responsibilities' => ['Build UI components'],
                    ],
                    'apply_options' => null,
                ],
            ],
            'parameters' => [
                'query' => 'Software Engineer',
                'page' => 1,
                'num_pages' => 5,
            ],
        ];

        Http::fake([
            'jsearch.p.rapidapi.com/*' => Http::response($mockApiResponse, 200, [
                'X-RateLimit-Requests-Remaining' => '995',
                'X-RateLimit-Reset' => now()->addHour()->timestamp,
            ]),
        ]);

        $getJobDataJob = new GetJobData($category->id, $countries->first()->id, 5, false);

        $apiKeyService = new ApiKeyService;
        $jobStorageService = new JobStorageService;
        $jobFetchService = new JobFetchService($apiKeyService);

        $getJobDataJob->handle($jobFetchService);

        $apiKey->refresh();
        $this->assertEquals(15, $apiKey->sent_request);
        $this->assertNotNull($apiKey->rate_limit_reset);

        $this->assertEquals(2, JobListing::count());
        $job1 = JobListing::where('job_id', 'ext-job-123')->first();
        $this->assertNotNull($job1);
        $this->assertEquals('Tech Corp', $job1->employer_name);
        $this->assertEquals('Senior Software Engineer', $job1->job_title);
        $this->assertEquals($category->id, $job1->job_category);
        $this->assertEquals('tech.png', $job1->category_image);
        $this->assertTrue($job1->is_remote);
        $this->assertEquals(120000.0, $job1->min_salary);
        $this->assertEquals(180000.0, $job1->max_salary);
        $this->assertEquals('yearly', $job1->salary_period);

        $job2 = JobListing::where('job_id', 'ext-job-456')->first();
        $this->assertNotNull($job2);
        $this->assertEquals('Startup Inc', $job2->employer_name);
        $this->assertEquals('Frontend Developer', $job2->job_title);
        $this->assertEquals($category->id, $job2->job_category);
        $this->assertFalse($job2->is_remote);
        $this->assertEquals('San Francisco', $job2->city);
        $this->assertEquals('CA', $job2->state);
        $this->assertEquals('USA', $job2->country);
        $this->assertEquals(50.0, $job2->min_salary);
        $this->assertEquals(75.0, $job2->max_salary);
        $this->assertEquals('hourly', $job2->salary_period);

        $this->assertCount(2, $job1->apply_options);
        $this->assertEquals('LinkedIn', $job1->apply_options[0]['publisher']);
        $this->assertEquals('https://linkedin.com/apply/123', $job1->apply_options[0]['apply_link']);
        $this->assertTrue($job1->apply_options[0]['is_direct']);

        $this->assertEquals('Indeed', $job1->apply_options[1]['publisher']);
        $this->assertEquals('https://indeed.com/apply/123', $job1->apply_options[1]['apply_link']);
        $this->assertFalse($job1->apply_options[1]['is_direct']);

        Http::assertSentCount(5);

        Http::assertSent(function (Request $request) {
            return str_contains($request->url(), 'jsearch.p.rapidapi.com/search');
        });
    }

    public function test_handles_api_errors_gracefully_in_end_to_end_flow(): void
    {
        $apiKey = ApiKey::create([
            'api_key' => 'test-api-key-123',
            'api_name' => 'job',
            'sent_request' => 10,
            'request_remaining' => 990,
            'rate_limit_reset' => now()->addHour(),
        ]);

        $countries = collect([
            Country::create(['name' => 'United States', 'code' => 'US', 'is_active' => true]),
        ]);
        $category = JobCategory::factory()->create();
        $category->countries()->attach($countries->pluck('id'));

        Http::fake([
            'jsearch.p.rapidapi.com/*' => Http::response(['error' => 'API Error'], 500),
        ]);

        $getJobDataJob = new GetJobData($category->id, $countries->first()->id, 5, false);
        $apiKeyService = new ApiKeyService;
        $jobFetchService = new JobFetchService($apiKeyService);

        try {
            $getJobDataJob->handle($jobFetchService);
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail('Exception should not have been thrown: '.$e->getMessage());
        }

        $this->assertEquals(0, JobListing::count());

        $apiKey->refresh();
        $this->assertEquals(10, $apiKey->sent_request);
    }

    public function test_handles_empty_api_response_in_end_to_end_flow(): void
    {
        $apiKey = ApiKey::create([
            'api_key' => 'test-api-key-123',
            'api_name' => 'job',
            'sent_request' => 5,
            'request_remaining' => 995,
            'rate_limit_reset' => now()->addHour(),
        ]);

        $countries = collect([
            Country::create(['name' => 'United States', 'code' => 'US', 'is_active' => true]),
        ]);
        $category = JobCategory::factory()->create();
        $category->countries()->attach($countries->pluck('id'));

        Http::fake([
            'jsearch.p.rapidapi.com/*' => Http::response([
                'data' => [],
                'parameters' => ['query' => 'test', 'page' => 1],
            ], 200),
        ]);

        $getJobDataJob = new GetJobData($category->id, $countries->first()->id, 5, false);
        $apiKeyService = new ApiKeyService;
        $jobFetchService = new JobFetchService($apiKeyService);

        $getJobDataJob->handle($jobFetchService);

        $this->assertEquals(0, JobListing::count());

        $apiKey->refresh();
        $this->assertEquals(10, $apiKey->sent_request);
    }

    public function test_processes_large_dataset_efficiently_in_end_to_end_flow(): void
    {
        $apiKey = ApiKey::create([
            'api_key' => 'test-api-key-123',
            'api_name' => 'job',
            'sent_request' => 0,
            'request_remaining' => 1000,
            'rate_limit_reset' => now()->addHour(),
        ]);

        $countries = collect([
            Country::create(['name' => 'United States', 'code' => 'US', 'is_active' => true]),
        ]);
        $category = JobCategory::factory()->create([
            'name' => 'Engineering',
            'category_image' => 'engineering.png',
        ]);
        $category->countries()->attach($countries->pluck('id'));

        $largeJobData = [];
        for ($i = 1; $i <= 50; $i++) {
            $largeJobData[] = [
                'job_id' => "large-job-{$i}",
                'employer_name' => "Company {$i}",
                'employer_logo' => $i % 2 === 0 ? "https://example.com/logo{$i}.png" : null,
                'employer_website' => "https://company{$i}.com",
                'job_publisher' => "Publisher {$i}",
                'job_employment_type' => $i % 3 === 0 ? 'Full-time' : ($i % 3 === 1 ? 'Part-time' : 'Contract'),
                'job_title' => "Job Title {$i}",
                'job_apply_link' => "https://apply{$i}.com",
                'job_description' => "Description for job {$i}",
                'job_is_remote' => $i % 2 === 0,
                'job_city' => $i % 2 === 0 ? null : "City {$i}",
                'job_state' => $i % 2 === 0 ? null : "State {$i}",
                'job_country' => $i % 2 === 0 ? null : 'USA',
                'job_latitude' => $i % 2 === 0 ? null : 37.7749 + ($i * 0.001),
                'job_longitude' => $i % 2 === 0 ? null : -122.4194 + ($i * 0.001),
                'job_google_link' => $i % 3 === 0 ? "https://maps.google.com/place{$i}" : null,
                'job_posted_at_datetime_utc' => '2023-01-0'.($i % 9 + 1).'T00:00:00Z',
                'job_offer_expiration_datetime_utc' => $i % 4 === 0 ? null : '2023-02-0'.($i % 9 + 1).'T00:00:00Z',
                'job_min_salary' => $i % 3 === 0 ? 50000.0 + ($i * 1000) : null,
                'job_max_salary' => $i % 3 === 0 ? 80000.0 + ($i * 1000) : null,
                'job_salary_period' => $i % 3 === 0 ? 'yearly' : null,
                'job_highlights' => [
                    'Benefits' => $i % 2 === 0 ? ["Benefit {$i}A", "Benefit {$i}B"] : [],
                    'Qualifications' => ["Qualification {$i}"],
                    'Responsibilities' => ["Responsibility {$i}"],
                ],
                'apply_options' => $i % 4 === 0 ? [
                    [
                        'publisher' => "Apply Publisher {$i}",
                        'apply_link' => "https://applysite{$i}.com",
                        'is_direct' => $i % 2 === 0,
                    ],
                ] : null,
            ];
        }

        Http::fake([
            'jsearch.p.rapidapi.com/*' => Http::response([
                'data' => $largeJobData,
                'parameters' => ['query' => 'test', 'page' => 1],
            ], 200, [
                'X-RateLimit-Requests-Remaining' => '995',
                'X-RateLimit-Reset' => now()->addHour()->timestamp,
            ]),
        ]);

        $initialMemory = memory_get_usage(true);

        $getJobDataJob = new GetJobData($category->id, $countries->first()->id, 5, false);
        $apiKeyService = new ApiKeyService;
        $jobStorageService = new JobStorageService;
        $jobFetchService = new JobFetchService($apiKeyService);

        $getJobDataJob->handle($jobFetchService);

        $responseDTO = new JobResponseDTO(
            $largeJobData,
            $category->id,
            $category->category_image
        );

        $storeJobsJob = new StoreJobs($responseDTO);
        $storeJobsJob->handle($jobStorageService);

        $finalMemory = memory_get_usage(true);
        $memoryUsed = $finalMemory - $initialMemory;

        $this->assertEquals(50, JobListing::count());
        $this->assertLessThan(50 * 1024 * 1024, $memoryUsed);

        $randomJob = JobListing::where('job_id', 'large-job-25')->first();
        $this->assertNotNull($randomJob);
        $this->assertEquals('Company 25', $randomJob->employer_name);
        $this->assertEquals($category->id, $randomJob->job_category);
        $this->assertEquals('engineering.png', $randomJob->category_image);
    }
}
