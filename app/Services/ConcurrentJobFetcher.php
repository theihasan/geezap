<?php

namespace App\Services;

use App\DTO\JobResponseDTO;
use App\Enums\ApiName;
use App\Events\ExceptionHappenEvent;
use App\Models\ApiKey;
use App\Models\JobCategory;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ConcurrentJobFetcher
{


    private Collection $apiKeys;

    public function __construct()
    {
        $this->apiKeys = $this->getActiveApiKeys();
    }


    public function fetch(JobCategory $category, int $page, string $countryCode, string $countryName): array
    {
        try {
            $responses = Http::pool(fn (Pool $pool) => [
                $this->apiKeys->get(ApiName::JOB->value) ?
                    $pool->as('jsearch')->job()->get('/search', [
                        'query' => $category->query_name,
                        'page' => $page,
                        'num_pages' => $category->num_page,
                        'date_posted' => $category->timeframe,
                        'country' => $countryCode
                    ]) : null,

                $this->apiKeys->get(ApiName::SEARCH_API->value) ?
                    $pool->as('searchapi')->searchapi()->get('', [
                        'q' => $category->query_name,
                        'page' => $page,
                        'location' => $countryName
                    ]) : null
            ]);

            $this->updateApiKeysRemaining($responses);

            return $this->processResponses($responses, $category);

        } catch (\Exception $e) {
            ExceptionHappenEvent::dispatch($e);
            logger()->error('Error in concurrent job fetching', [
                'message' => $e->getMessage(),
                'category' => $category->id,
                'page' => $page,
                'country' => $countryCode
            ]);
            throw $e;
        }
    }

    private function getActiveApiKeys(): Collection
    {
        return ApiKey::query()
            ->where('request_remaining', '>', 0)
            ->get()
            ->keyBy('api_name');
    }
    private function updateApiKeysRemaining($responses): void
    {
        if (isset($responses['jsearch']) && $responses['jsearch']->successful()) {
            $remaining = $responses['jsearch']->header('X-RateLimit-Requests-Remaining');
            if (is_numeric($remaining)) {
                $this->updateApiKey(ApiName::JOB, (int)$remaining);
            }
        }

        if (isset($responses['searchapi']) && $responses['searchapi']->successful()) {
            logger()->info('Search api header', [$responses['searchapi']->headers()]);
            $remaining = $responses['searchapi']->header('X-RateLimit-Remaining');
            if (is_numeric($remaining)) {
                $this->updateApiKey(ApiName::SEARCH_API, (int)$remaining);
            }
        }
    }

    private function updateApiKey(ApiName $apiName, ?int $remaining): void
    {
        if ($remaining !== null && $remaining >= 0 && $this->apiKeys->has($apiName->value)) {
            $this->apiKeys->get($apiName->value)->update([
                'request_remaining' => $remaining
            ]);
        }
    }

    private function processResponses($responses, JobCategory $category): array
    {
        try {
            $combinedJobs = [];

            if (isset($responses['jsearch']) && $responses['jsearch']->successful()) {
                $jsearchData = $responses['jsearch']->json();
                $combinedJobs = array_merge($combinedJobs, $jsearchData['data'] ?? []);
            }

            if (isset($responses['searchapi']) && $responses['searchapi']->successful()) {
                $searchApiData = $responses['searchapi']->json();
                $transformedJobs = $this->transformSearchApiResponse($searchApiData);
                $combinedJobs = array_merge($combinedJobs, $transformedJobs);
            }

            $uniqueJobs = collect($combinedJobs)->unique(function ($job) {
                return $job['job_title'] . $job['employer_name'];
            })->values()->all();

            $jsonEncoded = json_encode(['data' => $uniqueJobs], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
            if ($jsonEncoded === false) {
                throw new \RuntimeException('Failed to encode job data');
            }

            return json_decode($jsonEncoded, true);

        } catch (\Exception $e) {
            logger()->error('Error processing responses', [
                'message' => $e->getMessage(),
                'category' => $category->id
            ]);
            return ['data' => []];
        }
    }

    private function transformSearchApiResponse(array $response): array
    {
        return array_map(function($job) {
            return [
                'employer_name' => $this->sanitizeText($job['company_name'] ?? null),
                'employer_logo' => $this->sanitizeText($job['thumbnail'] ?? null),
                'employer_website' => null,
                'job_publisher' => $this->sanitizeText($job['via'] ?? null),
                'job_employment_type' => $this->extractEmploymentType($job['extensions'] ?? []),
                'job_title' => $this->sanitizeText($job['title'] ?? null),
                'job_description' => $this->sanitizeText($job['description'] ?? null),
                'job_apply_link' => $this->sanitizeText($job['apply_link'] ?? null),
                'job_is_remote' => $this->isRemoteJob($job['description'] ?? ''),
                'job_city' => $this->sanitizeText($this->extractCity($job['location'] ?? '')),
                'job_state' => $this->sanitizeText($this->extractState($job['location'] ?? '')),
                'job_country' => 'BD',
                'job_posted_at_datetime_utc' => $this->parsePostedDate($job['extensions'] ?? []),
                'job_min_salary' => null,
                'job_max_salary' => null,
                'job_salary_period' => null,
                'job_google_link' => $this->sanitizeText($job['sharing_link'] ?? null),
                'job_highlights' => [
                    'Benefits' => array_map([$this, 'sanitizeText'], $this->extractBenefits($job['description'] ?? '') ?? []),
                    'Qualifications' => array_map([$this, 'sanitizeText'], $this->extractQualifications($job['description'] ?? '') ?? []),
                    'Responsibilities' => array_map([$this, 'sanitizeText'], $this->extractResponsibilities($job['description'] ?? '') ?? [])
                ]
            ];
        }, $response['jobs'] ?? []);
    }

    private function extractSection(string $description, string $sectionTitle): ?array
    {
        try {
            if (empty($description)) {
                return null;
            }

            $pattern = "/{$sectionTitle}(?:s)?:?.*?(?:\n(.*?)(?:\n\n|\Z)|$)/si";
            if (preg_match($pattern, $description, $matches)) {
                // Ensure we have captured content
                $content = $matches[1] ?? '';
                if (empty($content)) {
                    return null;
                }

                // Split by bullet points and clean
                $items = preg_split('/[•\-\*]\s*/', $content);
                $items = array_map('trim', $items);
                $items = array_filter($items); // Remove empty items

                if (!empty($items)) {
                    return array_values($items);
                }
            }

            return null;
        } catch (\Exception $e) {
            // Log error but don't break processing
            logger()->error('Error extracting section', [
                'section' => $sectionTitle,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    private function extractBenefits(string $description): ?array
    {
        $benefits = $this->extractSection($description, 'Benefits?|Perks?|What we offer');
        if (empty($benefits)) {
            $benefits = $this->extractSection($description, 'Why Join Us');
        }
        return $benefits;
    }

    private function extractQualifications(string $description): ?array
    {
        return $this->extractSection($description, 'Qualifications?|Requirements?|What you\'ll need');
    }

    private function extractResponsibilities(string $description): ?array
    {
        return $this->extractSection($description, 'Responsibilities?|What you\'ll do|Job Description|Key Responsibilities');
    }

    private function extractState(string $location): ?string
    {
        $parts = explode(',', $location);
        return isset($parts[1]) ? trim($parts[1]) : null;
    }

    private function extractEmploymentType(array $extensions): ?string
    {
        foreach ($extensions as $ext) {
            if (stripos($ext, 'Full-time') !== false) return 'FULLTIME';
            if (stripos($ext, 'Part-time') !== false) return 'PARTTIME';
            if (stripos($ext, 'Contractor') !== false) return 'CONTRACTOR';
        }
        return null;
    }

    private function isRemoteJob(string $description): bool
    {
        return stripos($description, 'remote') !== false;
    }

    private function extractCity(string $location): ?string
    {
        $parts = explode(',', $location);
        return trim($parts[0]) ?? null;
    }

    private function parsePostedDate(array $extensions): ?string
    {
        foreach ($extensions as $ext) {
            if (preg_match('/(\d+)\s+(hour|day|month|week)s?\s+ago/', $ext, $matches)) {
                $number = (int)$matches[1];
                $unit = strtolower($matches[2]);

                return match($unit) {
                    'hour' => now()->subHours($number),
                    'day' => now()->subDays($number),
                    'week' => now()->subWeeks($number),
                    'month' => now()->subMonths($number),
                    default => null
                };
            }
        }
        return null;
    }

    private function sanitizeText(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }

        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

        $text = str_replace(["\u{0000}", "\u{FFFD}"], '', $text);

        return $text;
    }
}
