<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Exceptions\CountryNotFoundException;
use App\Models\Country;
use App\Models\JobCategory;
use App\Services\JobFetchService;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class GetJobData implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public array $backoff = [60];

    public int $maxExceptions = 1;

    public function __construct(
        private readonly int $categoryId,
        private readonly int $countryId,
        private readonly int $totalPages,
        private readonly bool $isLastJob
    ) {}

    public function handle(JobFetchService $jobFetchService): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        try {
            $category = JobCategory::findOrFail($this->categoryId);
            $country = Country::findOrFail($this->countryId);

            $jobFetchService->fetchJobsForCountry($category, $country, $this->totalPages);

        } catch (ValidationException|InvalidArgumentException|CountryNotFoundException|ModelNotFoundException|Exception $e) {
            Log::error('Error on job fetching', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'category_id' => $this->categoryId,
                'country_id' => $this->countryId,
            ]);

            $this->fail($e);
        }
    }
}
