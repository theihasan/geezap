<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Exceptions\CountryNotFoundException;
use App\Jobs\GetJobData;
use App\Models\Country;
use App\Models\JobCategory;
use App\Services\JobFetchService;
use Illuminate\Bus\Batch;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GetJobDataTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_handles_job_execution_successfully(): void
    {
        // Arrange
        $category = JobCategory::factory()
            ->has(Country::factory()->count(2))
            ->create();

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->expects($this->once())
            ->method('fetchJobsForCategory')
            ->with(
                $this->callback(function ($cat) use ($category) {
                    return $cat->id === $category->id && $cat->countries->count() === 2;
                }),
                5
            );

        $job = new GetJobData($category->id, 5, false);

        // Act & Assert
        $job->handle($jobFetchService);
    }

    #[Test]
    public function it_returns_early_when_batch_is_cancelled(): void
    {
        // Arrange
        $category = JobCategory::factory()->create();

        $batch = $this->createMock(Batch::class);
        $batch->method('cancelled')->willReturn(true);

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->expects($this->never())
            ->method('fetchJobsForCategory');

        $job = new GetJobData($category->id, 5, false);
        $job->setBatch($batch);

        // Act
        $job->handle($jobFetchService);

        // Assert - No exception thrown and service not called
    }

    #[Test]
    public function it_handles_model_not_found_exception(): void
    {
        // Arrange
        $nonExistentCategoryId = 99999;

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->expects($this->never())
            ->method('fetchJobsForCategory');

        Log::shouldReceive('error')
            ->once()
            ->with('Error on job fetching', \Mockery::subset([
                'category_id' => $nonExistentCategoryId,
            ]));

        $job = new GetJobData($nonExistentCategoryId, 5, false);

        // Act & Assert
        $this->expectException(ModelNotFoundException::class);
        $job->handle($jobFetchService);
    }

    #[Test]
    public function it_handles_validation_exception(): void
    {
        // Arrange
        $category = JobCategory::factory()->create();

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->method('fetchJobsForCategory')
            ->willThrowException(new ValidationException(\Illuminate\Validation\Validator::make([], [])));

        Log::shouldReceive('error')
            ->once()
            ->with('Error on job fetching', \Mockery::subset([
                'category_id' => $category->id,
            ]));

        $job = new GetJobData($category->id, 5, false);

        // Act & Assert
        $this->expectException(ValidationException::class);
        $job->handle($jobFetchService);
    }

    #[Test]
    public function it_handles_invalid_argument_exception(): void
    {
        // Arrange
        $category = JobCategory::factory()->create();

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->method('fetchJobsForCategory')
            ->willThrowException(new InvalidArgumentException('Invalid argument'));

        Log::shouldReceive('error')
            ->once()
            ->with('Error on job fetching', \Mockery::subset([
                'message' => 'Invalid argument',
                'category_id' => $category->id,
            ]));

        $job = new GetJobData($category->id, 5, false);

        // Act & Assert
        $this->expectException(InvalidArgumentException::class);
        $job->handle($jobFetchService);
    }

    #[Test]
    public function it_handles_country_not_found_exception(): void
    {
        // Arrange
        $category = JobCategory::factory()->create();

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->method('fetchJobsForCategory')
            ->willThrowException(new CountryNotFoundException('Country not found'));

        Log::shouldReceive('error')
            ->once()
            ->with('Error on job fetching', \Mockery::subset([
                'message' => 'Country not found',
                'category_id' => $category->id,
            ]));

        $job = new GetJobData($category->id, 5, false);

        // Act & Assert
        $this->expectException(CountryNotFoundException::class);
        $job->handle($jobFetchService);
    }

    #[Test]
    public function it_handles_generic_exception(): void
    {
        // Arrange
        $category = JobCategory::factory()->create();

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->method('fetchJobsForCategory')
            ->willThrowException(new \Exception('Something went wrong'));

        Log::shouldReceive('error')
            ->once()
            ->with('Error on job fetching', \Mockery::subset([
                'message' => 'Something went wrong',
                'category_id' => $category->id,
            ]));

        $job = new GetJobData($category->id, 5, false);

        // Act & Assert
        $this->expectException(\Exception::class);
        $job->handle($jobFetchService);
    }

    #[Test]
    public function it_loads_category_with_countries_relationship(): void
    {
        // Arrange
        $countries = Country::factory()->count(3)->create();
        $category = JobCategory::factory()->create();
        $category->countries()->attach($countries);

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->expects($this->once())
            ->method('fetchJobsForCategory')
            ->with(
                $this->callback(function ($cat) {
                    // Verify that countries relationship is loaded
                    return $cat->relationLoaded('countries') && $cat->countries->count() === 3;
                }),
                5
            );

        $job = new GetJobData($category->id, 5, false);

        // Act
        $job->handle($jobFetchService);
    }

    #[Test]
    public function it_passes_correct_parameters_to_service(): void
    {
        // Arrange
        $category = JobCategory::factory()->create();
        $totalPages = 10;
        $isLastCategory = true;

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->expects($this->once())
            ->method('fetchJobsForCategory')
            ->with(
                $this->callback(function ($cat) use ($category) {
                    return $cat->id === $category->id;
                }),
                $totalPages // Should pass the totalPages parameter
            );

        $job = new GetJobData($category->id, $totalPages, $isLastCategory);

        // Act
        $job->handle($jobFetchService);
    }

    #[Test]
    public function it_has_correct_job_configuration(): void
    {
        // Arrange
        $job = new GetJobData(1, 5, false);

        // Assert
        $this->assertEquals(2, $job->tries);
        $this->assertEquals([60], $job->backoff);
        $this->assertEquals(1, $job->maxExceptions);
    }

    #[Test]
    public function it_logs_error_with_complete_exception_details(): void
    {
        // Arrange
        $category = JobCategory::factory()->create();
        $exception = new \Exception('Detailed error message');

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->method('fetchJobsForCategory')
            ->willThrowException($exception);

        Log::shouldReceive('error')
            ->once()
            ->with('Error on job fetching', [
                'message' => 'Detailed error message',
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
                'category_id' => $category->id,
            ]);

        $job = new GetJobData($category->id, 5, false);

        // Act & Assert
        $this->expectException(\Exception::class);
        $job->handle($jobFetchService);
    }

    #[Test]
    public function it_handles_null_batch_gracefully(): void
    {
        // Arrange
        $category = JobCategory::factory()->create();

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->expects($this->once())
            ->method('fetchJobsForCategory');

        $job = new GetJobData($category->id, 5, false);
        // Don't set a batch - should be null by default

        // Act & Assert - Should not throw exception
        $job->handle($jobFetchService);
    }
}
