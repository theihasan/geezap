<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\DTO\JobDTO;
use App\Models\JobApplyOption;
use App\Models\JobListing;
use App\Services\JobStorageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class JobStorageServiceTest extends TestCase
{
    use RefreshDatabase;

    private JobStorageService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new JobStorageService;
    }

    private function createJobDTO(array $overrides = []): JobDTO
    {
        // Handle apply options explicitly to respect null values
        if (array_key_exists('applyOptions', $overrides)) {
            $applyOptions = $overrides['applyOptions'];
        } else {
            $applyOptions = [
                [
                    'publisher' => 'Indeed',
                    'apply_link' => 'https://indeed.com/apply/123',
                    'is_direct' => true,
                ],
            ];
        }

        // Use a unique title suffix to prevent slug conflicts unless explicitly overridden
        $uniqueId = uniqid();
        $defaultTitle = "Software Engineer {$uniqueId}";

        // Allow exact title override for matching tests
        if (isset($overrides['jobTitle'])) {
            $jobTitle = $overrides['jobTitle'];
        } else {
            $jobTitle = $defaultTitle;
        }

        // Allow unique job IDs for each test
        $uniqueJobId = "test-job-{$uniqueId}";

        // Handle state explicitly to respect null values
        if (array_key_exists('state', $overrides)) {
            $state = $overrides['state'];
        } else {
            $state = 'CA';
        }

        return new JobDTO(
            jobId: $overrides['jobId'] ?? $uniqueJobId,
            employerName: $overrides['employerName'] ?? 'Tech Company',
            employerLogo: $overrides['employerLogo'] ?? null,
            employerWebsite: $overrides['employerWebsite'] ?? null,
            publisher: $overrides['publisher'] ?? 'Indeed',
            employmentType: $overrides['employmentType'] ?? 'Full-time',
            jobTitle: $jobTitle,
            jobCategory: $overrides['jobCategory'] ?? 1,
            categoryImage: $overrides['categoryImage'] ?? 'tech.jpg',
            applyLink: $overrides['applyLink'] ?? 'https://indeed.com/apply/123',
            description: $overrides['description'] ?? 'Test job description',
            isRemote: $overrides['isRemote'] ?? false,
            city: $overrides['city'] ?? 'San Francisco',
            state: $state,
            country: $overrides['country'] ?? 'US',
            latitude: $overrides['latitude'] ?? null,
            longitude: $overrides['longitude'] ?? null,
            googleLink: $overrides['googleLink'] ?? null,
            postedAt: $overrides['postedAt'] ?? null,
            expiredAt: $overrides['expiredAt'] ?? null,
            minSalary: $overrides['minSalary'] ?? null,
            maxSalary: $overrides['maxSalary'] ?? null,
            salaryPeriod: $overrides['salaryPeriod'] ?? null,
            benefits: $overrides['benefits'] ?? null,
            qualifications: $overrides['qualifications'] ?? null,
            responsibilities: $overrides['responsibilities'] ?? null,
            applyOptions: $applyOptions
        );
    }

    #[Test]
    public function it_creates_new_job_when_none_exists(): void
    {
        $jobDTO = $this->createJobDTO([
            'jobId' => 'test-job-123',
            'jobTitle' => 'Software Engineer',
            'employerName' => 'Tech Company',
            'city' => 'San Francisco',
            'state' => 'CA',
            'country' => 'US',
            'publisher' => 'Indeed',
        ]);

        $result = $this->service->storeJob($jobDTO);

        $this->assertInstanceOf(JobListing::class, $result);
        $this->assertEquals('test-job-123', $result->job_id);
        $this->assertEquals('Software Engineer', $result->job_title);
        $this->assertEquals('Tech Company', $result->employer_name);

        $this->assertDatabaseHas('job_listings', [
            'job_id' => 'test-job-123',
            'job_title' => 'Software Engineer',
            'employer_name' => 'Tech Company',
        ]);

        $this->assertDatabaseHas('job_apply_options', [
            'job_listing_id' => $result->id,
            'publisher' => 'Indeed',
            'apply_link' => 'https://indeed.com/apply/123',
            'is_direct' => true,
        ]);
    }

    #[Test]
    public function it_updates_existing_job_when_found_by_job_id(): void
    {
        $existingJob = JobListing::factory()->create([
            'job_id' => 'test-job-123',
            'job_title' => 'Old Title',
            'employer_name' => 'Old Company',
        ]);

        $jobDTO = $this->createJobDTO([
            'jobId' => 'test-job-123',
            'jobTitle' => 'Updated Software Engineer',
            'employerName' => 'Updated Tech Company',
            'applyOptions' => null,
        ]);

        $result = $this->service->storeJob($jobDTO);

        $this->assertEquals($existingJob->id, $result->id);
        $this->assertEquals('Updated Software Engineer', $result->job_title);
        $this->assertEquals('Updated Tech Company', $result->employer_name);

        $this->assertEquals(1, JobListing::count());
    }

    #[Test]
    public function it_finds_existing_job_by_multiple_criteria_when_no_job_id(): void
    {
        $existingJob = JobListing::factory()->create([
            'job_id' => null,
            'job_title' => 'Software Engineer',
            'employer_name' => 'Tech Company',
            'city' => 'San Francisco',
            'state' => 'CA',
            'country' => 'US',
            'publisher' => 'Indeed',
        ]);

        $jobDTO = $this->createJobDTO([
            'jobId' => null,
            'jobTitle' => 'Software Engineer',
            'employerName' => 'Tech Company',
            'city' => 'San Francisco',
            'state' => 'CA',
            'country' => 'US',
            'publisher' => 'Indeed',
            'applyOptions' => null,
        ]);

        $result = $this->service->storeJob($jobDTO);

        $this->assertEquals($existingJob->id, $result->id);
        $this->assertEquals(1, JobListing::count());
    }

    #[Test]
    public function it_handles_partial_matching_criteria(): void
    {
        $existingJob = JobListing::factory()->create([
            'job_title' => 'Software Engineer',
            'employer_name' => 'Tech Company',
            'city' => 'San Francisco',
            'state' => null,
            'country' => 'US',
            'publisher' => 'Indeed',
        ]);

        $jobDTO = $this->createJobDTO([
            'jobId' => null,
            'jobTitle' => 'Software Engineer', // Exact match
            'employerName' => 'Tech Company', // Exact match
            'city' => 'San Francisco', // Exact match
            'state' => null, // Must match existing job's null state
            'country' => 'US', // Exact match
            'publisher' => 'Indeed', // Exact match
            'applyOptions' => null,
        ]);

        $result = $this->service->storeJob($jobDTO);

        $this->assertEquals($existingJob->id, $result->id);
        $this->assertEquals(1, JobListing::count());
    }

    #[Test]
    public function it_creates_multiple_apply_options(): void
    {
        $jobDTO = $this->createJobDTO([
            'jobId' => 'test-job-123',
            'applyOptions' => [
                [
                    'publisher' => 'Indeed',
                    'apply_link' => 'https://indeed.com/apply/123',
                    'is_direct' => true,
                ],
                [
                    'publisher' => 'LinkedIn',
                    'apply_link' => 'https://linkedin.com/apply/123',
                    'is_direct' => false,
                ],
                [
                    'publisher' => 'Company Website',
                    'apply_link' => 'https://company.com/careers/123',
                    'is_direct' => true,
                ],
            ],
        ]);

        $result = $this->service->storeJob($jobDTO);

        $this->assertEquals(3, JobApplyOption::where('job_listing_id', $result->id)->count());

        $this->assertDatabaseHas('job_apply_options', [
            'job_listing_id' => $result->id,
            'publisher' => 'Indeed',
            'apply_link' => 'https://indeed.com/apply/123',
            'is_direct' => true,
        ]);

        $this->assertDatabaseHas('job_apply_options', [
            'job_listing_id' => $result->id,
            'publisher' => 'LinkedIn',
            'apply_link' => 'https://linkedin.com/apply/123',
            'is_direct' => false,
        ]);
    }

    #[Test]
    public function it_upserts_apply_options_correctly(): void
    {
        $job = JobListing::factory()->create(['job_id' => 'test-job-123']);
        JobApplyOption::factory()->create([
            'job_listing_id' => $job->id,
            'publisher' => 'Indeed',
            'apply_link' => 'https://old-link.com',
            'is_direct' => false,
        ]);

        $jobDTO = $this->createJobDTO([
            'jobId' => 'test-job-123',
            'applyOptions' => [
                [
                    'publisher' => 'Indeed', // This should update existing
                    'apply_link' => 'https://new-link.com',
                    'is_direct' => true,
                ],
                [
                    'publisher' => 'LinkedIn', // This should create new
                    'apply_link' => 'https://linkedin.com/apply/123',
                    'is_direct' => false,
                ],
            ],
        ]);

        $result = $this->service->storeJob($jobDTO);

        $this->assertEquals(2, JobApplyOption::where('job_listing_id', $result->id)->count());

        // Check updated option
        $this->assertDatabaseHas('job_apply_options', [
            'job_listing_id' => $result->id,
            'publisher' => 'Indeed',
            'apply_link' => 'https://new-link.com',
            'is_direct' => true,
        ]);

        // Check new option
        $this->assertDatabaseHas('job_apply_options', [
            'job_listing_id' => $result->id,
            'publisher' => 'LinkedIn',
            'apply_link' => 'https://linkedin.com/apply/123',
            'is_direct' => false,
        ]);

        // Old link should not exist
        $this->assertDatabaseMissing('job_apply_options', [
            'apply_link' => 'https://old-link.com',
        ]);
    }

    #[Test]
    public function it_handles_empty_apply_options(): void
    {
        $jobDTO = $this->createJobDTO([
            'jobId' => 'test-job-123',
            'applyOptions' => [],
        ]);

        $result = $this->service->storeJob($jobDTO);

        $this->assertInstanceOf(JobListing::class, $result);
        $this->assertEquals(0, JobApplyOption::where('job_listing_id', $result->id)->count());
    }

    #[Test]
    public function it_handles_null_apply_options(): void
    {
        $jobDTO = $this->createJobDTO([
            'jobId' => 'test-job-123',
            'applyOptions' => null,
        ]);

        $result = $this->service->storeJob($jobDTO);

        $this->assertInstanceOf(JobListing::class, $result);
        $this->assertEquals(0, JobApplyOption::where('job_listing_id', $result->id)->count());
    }

    #[Test]
    public function it_prefers_job_id_matching_over_criteria_matching(): void
    {
        $jobWithMatchingId = JobListing::factory()->create([
            'job_id' => 'target-job-123',
            'job_title' => 'Different Title',
            'employer_name' => 'Different Company',
        ]);

        $jobWithMatchingCriteria = JobListing::factory()->create([
            'job_id' => 'other-job-456',
            'job_title' => 'Software Engineer',
            'employer_name' => 'Tech Company',
            'city' => 'San Francisco',
        ]);

        $jobDTO = $this->createJobDTO([
            'jobId' => 'target-job-123', // This should match the first job
            'jobTitle' => 'Software Engineer',
            'employerName' => 'Tech Company',
            'city' => 'San Francisco',
            'state' => 'CA',
            'country' => 'US',
            'publisher' => 'Indeed',
            'applyOptions' => null,
        ]);

        $result = $this->service->storeJob($jobDTO);

        $this->assertEquals($jobWithMatchingId->id, $result->id);
        $this->assertNotEquals($jobWithMatchingCriteria->id, $result->id);

        // Verify the job was updated with new data
        $this->assertEquals('Software Engineer', $result->job_title);
        $this->assertEquals('Tech Company', $result->employer_name);
    }
}
