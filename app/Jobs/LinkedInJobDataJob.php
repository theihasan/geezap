<?php

namespace App\Jobs;

use App\DTO\JobResponseDTO;
use App\Enums\ApiName;
use App\Models\ApiKey;
use App\Models\Country;
use App\Models\JobCategory;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class LinkedInJobDataJob extends GetJobDataJob
{
    public function __construct(
        int $categoryId,
        int $totalPages,
        bool $isLastCategory
    ) {
        parent::__construct($categoryId, $totalPages, $isLastCategory);
    }

    protected function getApiName(): string
    {
        return ApiName::LINKEDIN->value;
    }

    protected function makeApiRequest(ApiKey $apiKey, JobCategory $category, Country $country, int $page): array
    {
        $response = Http::linkedinjob()->retry([100, 200])->get('/active-jb-24h', [
            'offset' => ($page - 1) * 10,
            'title_filter' => $category->query_name,
            'location_filter' => $country->name,
        ]);

        throw_if($response->status() === 429, new RuntimeException('Rate limit exceeded'));
        throw_if(! $response->successful(), new RequestException($response));

        DB::table('api_keys')
            ->where('id', $apiKey->id)
            ->update(['request_remaining' => $response->header('X-RateLimit-Requests-Remaining')]);

        return $this->transformLinkedInResponse($response->json());
    }

    private function transformLinkedInResponse(array $response): array
    {
        return [
            'data' => array_map(function ($job) {
                $location = $job['locations_derived'][0] ?? '';
                $parts = explode(', ', $location);
                $city = $parts[0] ?? null;
                $state = $parts[1] ?? null;
                $country = $parts[2] ?? ($job['countries_derived'][0] ?? null);

                $employmentType = $job['employment_type'][0] ?? null;
                if ($employmentType) {
                    $employmentType = str_replace('_', ' ', strtolower($employmentType));
                    $employmentType = ucwords($employmentType);
                }

                return [
                    'employer_name' => $job['organization'] ?? '',
                    'employer_logo' => $job['organization_logo'] ?? null,
                    'employer_website' => $job['linkedin_org_url'] ?? $job['organization_url'] ?? null,
                    'job_publisher' => 'LinkedIn',
                    'job_employment_type' => $employmentType,
                    'job_title' => $job['title'] ?? '',
                    'job_apply_link' => $job['url'] ?? '',
                    'job_description' => $job['linkedin_org_description'] ?? '',
                    'job_is_remote' => $job['remote_derived'] ?? false,
                    'job_city' => $city,
                    'job_state' => $state,
                    'job_country' => $country,
                    'job_google_link' => null,
                    'job_posted_at_datetime_utc' => $job['date_posted'] ?? null,
                    'job_offer_expiration_datetime_utc' => $job['date_validthrough'] ?? null,
                    'job_min_salary' => null,
                    'job_max_salary' => null,
                    'job_salary_period' => null,
                    'job_highlights' => [
                        'Benefits' => null,
                        'Qualifications' => null,
                        'Responsibilities' => null
                    ],
                ];
            }, $response)
        ];
    }

    protected function transformResponseToJobDTO(
        array $responseData,
        int $categoryId,
        string $categoryImage
    ): JobResponseDTO {
        return JobResponseDTO::fromResponse($responseData, $categoryId, $categoryImage);
    }
}
