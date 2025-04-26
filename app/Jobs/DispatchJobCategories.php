<?php

namespace App\Jobs;

use App\Models\JobCategory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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
            JobCategory::query()->chunk(5, function ($categories) {
                $categories->each(function ($category) {
                    $isLast = $category->id === JobCategory::query()->max('id');

                    JSearchJobDataJob::dispatch($category->id, $category->page, $isLast);
                    LinkedInJobDataJob::dispatch($category->id, $category->page, $isLast);
                });
            });
        } catch (Exception $e) {
            logger($e->getMessage());
        }

    }
}
