<?php

namespace App\Jobs;

use App\Exceptions\CategoryNotFoundException;
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
                    GetJobData::dispatch($category->id,$category->page,$category->id === JobCategory::query()->max('id'));
                });
            });
        } catch (CategoryNotFoundException | \Exception $e) {
           logger($e->getMessage());
        }
    }
}
