<?php

namespace App\Jobs;

use App\DTO\JobResponseDTO;
use App\Enums\ApiName;
use App\Events\ExceptionHappenEvent;
use App\Events\NotifyUserAboutNewJobsEvent;
use App\Exceptions\ApiKeyNotFoundException;
use App\Exceptions\CountryNotFoundException;
use App\Jobs\Store\StoreJobs;
use App\Models\ApiKey;
use App\Models\JobCategory;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use RuntimeException;

class GetJobData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public array $backoff = [60];
    public int $maxExceptions = 1;
    public function __construct(
        private readonly int $categoryId,
        private readonly int $totalPages,
        private readonly bool $isLastCategory
    ) {}

    public function handle(): void
    {
        try {
            $apiKey = ApiKey::query()
                ->where('api_name', ApiName::JOB)
                ->where('request_remaining', '>', 0)
                ->orderBy('sent_request')
                ->first();

            $category = JobCategory::with('countries')->findOrFail($this->categoryId);

            $this->fetchAndStoreJobs($apiKey, $category);

        } catch (ValidationException | InvalidArgumentException| CountryNotFoundException | ModelNotFoundException  | Exception $e) {
            ExceptionHappenEvent::dispatch($e);
            Log::error('Error on job fetching', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }

    protected function fetchAndStoreJobs($apiKey, JobCategory $category): void
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

                    $response = Http::job()->retry([100, 200])->get('/search', [
                        'query' => $category->query_name,
                        'page' => $page,
                        'num_pages' => $category->num_page,
                        'date_posted' => $category->timeframe,
                        'country' => $country->code
                    ]);

                    throw_if($response->status() === 429, new RuntimeException('Rate limit exceeded'));

                    throw_if(!$response->successful(), new RequestException($response));

                    DB::table('api_keys')
                        ->where('id', $apiKey->id)
                        ->update(['request_remaining' => $response->header('X-RateLimit-Requests-Remaining')]);

                    if ($response->ok()) {
                        $jobResponseDTO = JobResponseDTO::fromResponse(
                            $response->json(),
                            $category->id,
                            $category->category_image
                        );
                        StoreJobs::dispatch($jobResponseDTO);
                    }

                    if ($this->isLastCategory && $page === $this->totalPages && $country->is($category->countries->last())) {
                        return;
                    }
                } catch (RequestException | RuntimeException  | Exception $e) {
                    ExceptionHappenEvent::dispatch($e);
                    throw $e;
                }
            }
        }
    }
}
