<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Exceptions\CountryNotFoundException;
use App\Jobs\GetJobData;
use App\Models\Country;
use App\Models\JobCategory;
use App\Services\JobFetchService;
use Illuminate\Bus\Batch;
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
        $country = Country::factory()->create();
        $category = JobCategory::factory()->create();

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->expects($this->once())
            ->method('fetchJobsForCountry')
            ->with(
                $this->callback(function ($cat) use ($category) {
                    return $cat->id === $category->id;
                }),
                $this->callback(function ($ctry) use ($country) {
                    return $ctry->id === $country->id;
                }),
                5
            );

        $job = new GetJobData($category->id, $country->id, 5, false);

        // Act & Assert
        $job->handle($jobFetchService);
    }

    #[Test]
    public function it_returns_early_when_batch_is_cancelled(): void
    {
        // Arrange
        $category = JobCategory::factory()->create();
        $country = Country::factory()->create();

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->expects($this->never())
            ->method('fetchJobsForCountry');

        // Create a batch that will be cancelled
        $batch = \Illuminate\Support\Facades\Bus::batch([])
            ->name('test-batch')
            ->dispatch();

        $batch->cancel(); // Cancel the batch

        $job = new GetJobData($category->id, $country->id, 5, false);
        $job->withBatchId($batch->id);

        // Act
        $job->handle($jobFetchService);

        // Assert - No exception thrown and service not called
    }

    #[Test]
    public function it_handles_model_not_found_exception(): void
    {
        // Arrange
        $nonExistentCategoryId = 99999;
        $nonExistentCountryId = 99999;

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->expects($this->never())
            ->method('fetchJobsForCountry');

        Log::shouldReceive('error')
            ->once()
            ->with('Error on job fetching', \Mockery::subset([
                'category_id' => $nonExistentCategoryId,
                'country_id' => $nonExistentCountryId,
            ]));

        $job = new GetJobData($nonExistentCategoryId, $nonExistentCountryId, 5, false);

        // Act - Job should handle the exception internally and not throw
        $job->handle($jobFetchService);

        // Assert - If we get here without an exception, the test passes
        $this->assertTrue(true);
    }

    #[Test]
    public function it_handles_validation_exception(): void
    {
        // Arrange
        $category = JobCategory::factory()->create();
        $country = Country::factory()->create();

        $jobFetchService = $this->createMock(JobFetchService::class);

        // Create a proper ValidationException
        $validator = \Illuminate\Support\Facades\Validator::make([], ['required_field' => 'required']);
        $validationException = new ValidationException($validator);

        $jobFetchService
            ->method('fetchJobsForCountry')
            ->willThrowException($validationException);

        Log::shouldReceive('error')
            ->once()
            ->with('Error on job fetching', \Mockery::subset([
                'category_id' => $category->id,
                'country_id' => $country->id,
            ]));

        $job = new GetJobData($category->id, $country->id, 5, false);

        // Act - Job should handle the exception internally and not throw
        $job->handle($jobFetchService);

        // Assert - If we get here without an exception, the test passes
        $this->assertTrue(true);
    }

    #[Test]
    public function it_handles_invalid_argument_exception(): void
    {
        // Arrange
        $category = JobCategory::factory()->create();
        $country = Country::factory()->create();

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->method('fetchJobsForCountry')
            ->willThrowException(new InvalidArgumentException('Invalid argument'));

        Log::shouldReceive('error')
            ->once()
            ->with('Error on job fetching', \Mockery::subset([
                'message' => 'Invalid argument',
                'category_id' => $category->id,
                'country_id' => $country->id,
            ]));

        $job = new GetJobData($category->id, $country->id, 5, false);

        // Act - Job should handle the exception internally and not throw
        $job->handle($jobFetchService);

        // Assert - If we get here without an exception, the test passes
        $this->assertTrue(true);
    }

    #[Test]
    public function it_handles_country_not_found_exception(): void
    {
        // Arrange
        $category = JobCategory::factory()->create();
        $country = Country::factory()->create();

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->method('fetchJobsForCountry')
            ->willThrowException(new CountryNotFoundException('Country not found'));

        Log::shouldReceive('error')
            ->once()
            ->with('Error on job fetching', \Mockery::subset([
                'message' => 'Country not found',
                'category_id' => $category->id,
                'country_id' => $country->id,
            ]));

        $job = new GetJobData($category->id, $country->id, 5, false);

        // Act - Job should handle the exception internally and not throw
        $job->handle($jobFetchService);

        // Assert - If we get here without an exception, the test passes
        $this->assertTrue(true);
    }

    #[Test]
    public function it_handles_generic_exception(): void
    {
        // Arrange
        $category = JobCategory::factory()->create();
        $country = Country::factory()->create();

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->method('fetchJobsForCountry')
            ->willThrowException(new \Exception('Something went wrong'));

        Log::shouldReceive('error')
            ->once()
            ->with('Error on job fetching', \Mockery::subset([
                'message' => 'Something went wrong',
                'category_id' => $category->id,
                'country_id' => $country->id,
            ]));

        $job = new GetJobData($category->id, $country->id, 5, false);

        // Act - Job should handle the exception internally and not throw
        $job->handle($jobFetchService);

        // Assert - If we get here without an exception, the test passes
        $this->assertTrue(true);
    }

    #[Test]
    public function it_loads_category_and_country_models(): void
    {
        // Arrange
        $country = Country::factory()->create();
        $category = JobCategory::factory()->create();

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->expects($this->once())
            ->method('fetchJobsForCountry')
            ->with(
                $this->callback(function ($cat) use ($category) {
                    return $cat->id === $category->id;
                }),
                $this->callback(function ($ctry) use ($country) {
                    return $ctry->id === $country->id;
                }),
                5
            );

        $job = new GetJobData($category->id, $country->id, 5, false);

        // Act
        $job->handle($jobFetchService);
    }

    #[Test]
    public function it_passes_correct_parameters_to_service(): void
    {
        // Arrange
        $category = JobCategory::factory()->create();
        $country = Country::factory()->create();
        $totalPages = 10;
        $isLastJob = true;

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->expects($this->once())
            ->method('fetchJobsForCountry')
            ->with(
                $this->callback(function ($cat) use ($category) {
                    return $cat->id === $category->id;
                }),
                $this->callback(function ($ctry) use ($country) {
                    return $ctry->id === $country->id;
                }),
                $totalPages
            );

        $job = new GetJobData($category->id, $country->id, $totalPages, $isLastJob);

        // Act
        $job->handle($jobFetchService);
    }

    #[Test]
    public function it_has_correct_job_configuration(): void
    {
        // Arrange
        $job = new GetJobData(1, 1, 5, false);

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
        $country = Country::factory()->create();
        $exception = new \Exception('Detailed error message');

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->method('fetchJobsForCountry')
            ->willThrowException($exception);

        Log::shouldReceive('error')
            ->once()
            ->with('Error on job fetching', [
                'message' => 'Detailed error message',
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
                'category_id' => $category->id,
                'country_id' => $country->id,
            ]);

        $job = new GetJobData($category->id, $country->id, 5, false);

        // Act - Job should handle the exception internally and not throw
        $job->handle($jobFetchService);

        // Assert - If we get here without an exception, the test passes
        $this->assertTrue(true);
    }

    #[Test]
    public function it_handles_null_batch_gracefully(): void
    {
        // Arrange
        $category = JobCategory::factory()->create();
        $country = Country::factory()->create();

        $jobFetchService = $this->createMock(JobFetchService::class);
        $jobFetchService
            ->expects($this->once())
            ->method('fetchJobsForCountry');

        $job = new GetJobData($category->id, $country->id, 5, false);
        // Don't set a batch - should be null by default

        // Act & Assert - Should not throw exception
        $job->handle($jobFetchService);
    }
}
