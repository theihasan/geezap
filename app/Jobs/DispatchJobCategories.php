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
            
            $maxCategoryId = JobCategory::query()->max('id');
            
            $batch = $this->createJobBatch($maxCategoryId);
            
            $batch->dispatch();
            
        } catch (CategoryNotFoundException | \Exception $e) {
            logger()->error('Something went wrong in Job dispatching in DispatchJobCategories class', [$e->getMessage()]);
        }
    }
    
    /**
     * Create a batch of jobs for processing categories
     */
    private function createJobBatch(int $maxCategoryId): PendingBatch
    {
        $jobs = [];
        
        JobCategory::query()->chunk(10, function ($categories) use (&$jobs, $maxCategoryId) {
            foreach ($categories as $category) {
                $isLastCategory = $category->id === $maxCategoryId;
                $jobs[] = new GetJobData($category->id, $category->page, $isLastCategory);
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
