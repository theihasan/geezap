<?php

declare(strict_types=1);

namespace App\Jobs\Store;

use App\DTO\JobDTO;
use App\DTO\JobResponseDTO;
use App\Events\ExceptionHappenEvent;
use App\Models\JobListing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 4;
    public $backoff = [45, 90, 180];

    public function __construct(public JobResponseDTO $responseDTO)
    {
    }

    public function handle(): void
    {
        try {
            foreach ($this->responseDTO->data as $jobData) {
                $jobData['job_category'] = $this->responseDTO->jobCategory;
                $jobData['category_image'] = $this->responseDTO->categoryImage;

                $jobDTO = JobDTO::fromArray($jobData);

        
                $query = JobListing::query();
                
                if ($jobDTO->jobId) {
                    $query->where('job_id', $jobDTO->jobId);
                } else {
                    $query->where('job_title', $jobDTO->jobTitle);
                }
                
                $existingJob = $query->first();
                
                if (!$existingJob) {
                    $jobListing = JobListing::query()->create($jobDTO->toArray());
                } else {
                    $jobListing = $existingJob;
                }
                
                if ($jobDTO->applyOptions && is_array($jobDTO->applyOptions)) {
                    foreach ($jobDTO->applyOptions as $option) {
                        $jobListing->applyOptions()->updateOrCreate(
                            ['publisher' => $option['publisher']],
                            [
                                'apply_link' => $option['apply_link'],
                                'is_direct' => $option['is_direct'],
                            ]
                        );
                    }
                }
            }
        } catch (\PDOException|\Exception $e) {
            $errorMessage = is_array($e->getMessage()) ? json_encode($e->getMessage()) : $e->getMessage();
            logger()->debug('Exception sent from store job class', ['error' => $errorMessage]);
            $this->release(60);
        }
    }
}
