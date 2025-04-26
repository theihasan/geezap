<?php

namespace App\Jobs;

use App\DTO\JobResponseDTO;
use App\Enums\ApiName;
use App\Models\ApiKey;
use App\Models\Country;
use App\Models\JobCategory;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class JSearchJobDataJob extends GetJobDataJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
    }

    protected function getApiName(): string
    {
        return ApiName::JOB->value;
    }


    /**
     * @throws \Throwable
     */
    protected function makeApiRequest(ApiKey $apiKey, JobCategory $category, Country $country, int $page): array
    {
        $response = Http::job()->retry([100, 200])->get('/search', [
            'query' => $category->query_name,
            'page' => $page,
            'num_pages' => $category->num_page,
            'date_posted' => $category->timeframe,
            'country' => $country->code
        ]);

        throw_if($response->status() === 429, new RuntimeException('Rate limit exceeded'));
        throw_if(! $response->successful(), new RequestException($response));

        DB::table('api_keys')
            ->where('id', $apiKey->id)
            ->update(['request_remaining' => $response->header('X-RateLimit-Requests-Remaining')]);

        return $response->json();
    }


    protected function transformResponseToJobDTO(
        array $responseData,
        int $categoryId,
        string $categoryImage
    ): JobResponseDTO {
        return JobResponseDTO::fromResponse($responseData, $categoryId, $categoryImage);
    }

}
