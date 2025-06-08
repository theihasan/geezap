<?php
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
                
                if (!$query->exists()) {
                    JobListing::query()->create($jobDTO->toArray());
                }
            }
        } catch (\PDOException|\Exception $e){
            $errorMessage = is_array($e->getMessage()) ? json_encode($e->getMessage()) : $e->getMessage();
            logger()->debug('Exception sent from store job class', ['error' => $errorMessage]);
            $this->release(60);
        }
    }
}
