<?php

namespace App\Jobs;

use App\DTO\JobResponseDTO;
use App\Events\ExceptionHappenEvent;
use App\Events\NotifyUserAboutNewJobsEvent;
use App\Jobs\Store\StoreJobs;
use App\Models\JobCategory;
use App\Services\ConcurrentJobFetcher;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GetJobData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 4;
    public array $backoff = [30, 45, 60];

    public function __construct(
        private readonly int $categoryId,
        private readonly int $totalPages,
        private readonly bool $isLastCategory
    ) {}

    public function handle(): void
    {
        try {
            $category = JobCategory::with('countries')->findOrFail($this->categoryId);
            $jobFetcher = app(ConcurrentJobFetcher::class);

            $this->fetchAndStoreJobs($jobFetcher, $category);

        } catch (Exception $e) {
            ExceptionHappenEvent::dispatch($e);
            Log::error('Error on job fetching', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            $this->release(60);
        }
    }

    protected function fetchAndStoreJobs(ConcurrentJobFetcher $jobFetcher, JobCategory $category): void
    {
        foreach ($category->countries as $country) {
            for ($page = 1; $page <= $this->totalPages; $page++) {
                try {
                    logger('Processing', [
                        'category_id' => $category->id,
                        'category_name' => $category->name,
                        'page' => $page,
                        'country' => $country->code
                    ]);


                    $combinedResponse = $jobFetcher->fetch(
                        $category,
                        $page,
                        $country->code,
                        $country->name
                    );

                    $jobResponseDTO = JobResponseDTO::fromResponse(
                        $combinedResponse,
                        $category->id,
                        $category->category_image
                    );
                    StoreJobs::dispatch($jobResponseDTO);

                    if ($this->isLastCategory && $page === $this->totalPages && $country->is($category->countries->last())) {
                        NotifyUserAboutNewJobsEvent::dispatch();
                        return;
                    }

                } catch (Exception $e) {
                    ExceptionHappenEvent::dispatch($e);
                    static::dispatch($this->categoryId, $this->totalPages, $this->isLastCategory)
                        ->delay(now()->addMinutes(1));
                    throw $e;
                }
            }
        }
    }
}
