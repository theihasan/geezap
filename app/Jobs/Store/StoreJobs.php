<?php

declare(strict_types=1);

namespace App\Jobs\Store;

use App\DTO\JobDTO;
use App\DTO\JobResponseDTO;
use App\Services\JobStorageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StoreJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 4;

    public array $backoff = [45, 90, 180];

    public function __construct(public JobResponseDTO $responseDTO) {}

    public function handle(JobStorageService $jobStorageService): void
    {
        try {
            $processedCount = 0;

            if (app()->environment('local')) {
                Log::info('StoreJobs starting', [
                    'jobs_in_batch' => count($this->responseDTO->data),
                    'category_id' => $this->responseDTO->jobCategory,
                ]);
            }

            foreach ($this->responseDTO->data as $jobData) {
                $jobData['job_category'] = $this->responseDTO->jobCategory;
                $jobData['category_image'] = $this->responseDTO->categoryImage;

                if (app()->environment('testing')) {
                    Log::info('Processing job in StoreJobs', [
                        'job_id' => $jobData['job_id'] ?? 'unknown',
                        'job_title' => $jobData['job_title'] ?? 'unknown',
                    ]);
                }

                $jobDTO = JobDTO::fromArray($jobData);
                $jobStorageService->storeJob($jobDTO);

                $processedCount++;

                // Process in smaller batches to prevent memory issues
                if ($processedCount % 25 === 0) {
                    $this->checkMemoryUsage();
                }
            }

            Log::info('StoreJobs completed', [
                'jobs_processed' => $processedCount,
                'category_id' => $this->responseDTO->jobCategory,
            ]);

        } catch (\PDOException|\Exception $e) {
            Log::error('Exception in StoreJobs', [
                'error' => $e->getMessage(),
                'category_id' => $this->responseDTO->jobCategory,
            ]);

            $this->release(60);
        }
    }

    private function checkMemoryUsage(): void
    {
        $memoryUsage = memory_get_usage(true);

        if ($memoryUsage > 100 * 1024 * 1024) { // 100MB threshold
            Log::warning('StoreJobs memory threshold exceeded, releasing job for retry', [
                'memory_usage_mb' => round($memoryUsage / 1024 / 1024, 2),
            ]);

            $this->release(60);

            return;
        }

        if (function_exists('gc_collect_cycles')) {
            gc_collect_cycles();
        }
    }
}
