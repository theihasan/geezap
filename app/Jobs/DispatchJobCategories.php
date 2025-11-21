<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Exceptions\CategoryNotFoundException;
use App\Models\JobCategory;
use Illuminate\Bus\Batch;
use Illuminate\Bus\PendingBatch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;
use Throwable;

class DispatchJobCategories implements ShouldQueue
{
    use Queueable;

    public int $tries = 4;

    public array $backoff = [30, 45, 60];

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $batch = $this->createJobBatch();

            $batch->dispatch();

        } catch (CategoryNotFoundException|\Exception $e) {
            logger()->error('Something went wrong in Job dispatching in DispatchJobCategories class', [$e->getMessage()]);
        }
    }

    /**
     * Create a batch of jobs for processing categories and countries
     */
    private function createJobBatch(): PendingBatch
    {
        $jobs = [];
        $totalJobsCount = 0;

        // Calculate total jobs count for tracking last job
        JobCategory::query()->with('countries')->chunk(10, function ($categories) use (&$totalJobsCount) {
            foreach ($categories as $category) {
                $totalJobsCount += $category->countries->count();
            }
        });

        $currentJobIndex = 0;

        JobCategory::query()->with('countries')->chunk(10, function ($categories) use (&$jobs, &$currentJobIndex, $totalJobsCount) {
            foreach ($categories as $category) {
                foreach ($category->countries as $country) {
                    $currentJobIndex++;
                    $isLastJob = $currentJobIndex === $totalJobsCount;

                    $jobs[] = new GetJobData(
                        $category->id,
                        $country->id,
                        $category->page,
                        $isLastJob
                    );
                }
            }
        });

        return Bus::batch($jobs)
            ->name('Job Data Fetching')
            ->allowFailures()
            ->onQueue('default')
            ->then(function (Batch $batch) {
                logger()->debug('All job data fetching completed', [
                    'batch_id' => $batch->id,
                    'total_jobs' => $batch->totalJobs,
                ]);
            })
            ->catch(function (Batch $batch, Throwable $e) {
                logger()->error('Job batch has failed jobs', [
                    'batch_id' => $batch->id,
                    'failed_jobs' => $batch->failedJobs,
                    'error' => $e->getMessage(),
                ]);
            });
    }
}
