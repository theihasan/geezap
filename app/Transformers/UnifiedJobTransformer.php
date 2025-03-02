<?php

namespace App\Transformers;

use Carbon\Carbon;

class UnifiedJobTransformer
{
    public static function transform(array $response): array
    {
        $apiVersion = self::detectApiVersion($response);

        return match($apiVersion) {
            'api1' => self::transformApi1Response($response),
            'api2' => $response,
            default => throw new \InvalidArgumentException('Unknown API format'),
        };
    }

    private static function detectApiVersion(array $response): string
    {
        if (isset($response['jobs']) && isset($response['search_metadata'])) {
            return 'api1';
        }

        if (isset($response['data']) && isset($response['data'][0]['job_title'])) {
            return 'api2';
        }

        throw new \InvalidArgumentException('Unknown API format');
    }

    private static function transformApi1Response(array $response): array
    {
        return [
            'data' => array_map(function ($job) {
                return [
                    'employer_name' => $job['company_name'] ?? null,
                    'employer_logo' => $job['thumbnail'] ?? null,
                    'employer_website' => null,
                    'job_publisher' => $job['via'] ?? null,
                    'job_employment_type' => self::extractEmploymentType($job['extensions'] ?? []),
                    'job_title' => $job['title'] ?? null,
                    'job_description' => $job['description'] ?? null,
                    'job_is_remote' => self::isRemoteJob($job['description'] ?? ''),
                    'job_city' => self::extractCity($job['location'] ?? ''),
                    'job_state' => null,
                    'job_country' => 'BD',
                    'job_apply_link' => $job['apply_link'] ?? null,
                    'job_google_link' => $job['sharing_link'] ?? null,
                    'job_posted_at_datetime_utc' => self::parsePostedDate($job['extensions'] ?? []),
                    'job_offer_expiration_datetime_utc' => null,
                    'job_min_salary' => self::extractSalary($job['description'] ?? ''),
                    'job_max_salary' => self::extractMaxSalary($job['description'] ?? ''),
                    'job_salary_period' => '',
                    'job_highlights' => [
                        'Benefits' => self::extractBenefits($job['description'] ?? ''),
                        'Qualifications' => self::extractQualifications($job['description'] ?? ''),
                        'Responsibilities' => self::extractResponsibilities($job['description'] ?? '')
                    ]
                ];
            }, $response['jobs'] ?? [])
        ];
    }

    private static function extractEmploymentType(array $extensions): ?string
    {
        $typeMap = [
            'Full-time' => 'FULLTIME',
            'Part-time' => 'PARTTIME',
            'Contractor' => 'CONTRACTOR',
            'Internship' => 'INTERNSHIP',
            'Temporary' => 'TEMPORARY'
        ];

        foreach ($extensions as $ext) {
            foreach ($typeMap as $key => $value) {
                if (stripos($ext, $key) !== false) {
                    return $value;
                }
            }
        }

        return null;
    }

    private static function isRemoteJob(string $description): bool
    {
        $remoteIndicators = ['remote', 'work from home', 'wfh'];
        foreach ($remoteIndicators as $indicator) {
            if (stripos($description, $indicator) !== false) {
                return true;
            }
        }
        return false;
    }

    private static function extractCity(string $location): ?string
    {
        $parts = explode(',', $location);
        return trim($parts[0]) ?? null;
    }

    private static function parsePostedDate(array $extensions): ?string
    {
        foreach ($extensions as $ext) {
            if (preg_match('/(\d+)\s+(hour|day|month|week)s?\s+ago/', $ext, $matches)) {
                $number = (int) $matches[1];
                $unit = strtolower($matches[2]);

                return match($unit) {
                    'hour' => now()->subHours($number)->toIso8601String(),
                    'day' => now()->subDays($number)->toIso8601String(),
                    'week' => now()->subWeeks($number)->toIso8601String(),
                    'month' => now()->subMonths($number)->toIso8601String(),
                    default => null
                };
            }
        }
        return null;
    }

    private static function extractSalary(string $description): ?float
    {
        if (preg_match('/salary.*?(\d[\d,]*)/i', $description, $matches)) {
            return (float) str_replace(',', '', $matches[1]);
        }
        return null;
    }

    private static function extractMaxSalary(string $description): ?float
    {
        if (preg_match('/salary.*?(\d[\d,]*)\s*-\s*(\d[\d,]*)/i', $description, $matches)) {
            return (float) str_replace(',', '', $matches[2]);
        }
        return null;
    }

    private static function extractSection(string $description, string $sectionName): ?array
    {
        $items = [];
        if (preg_match("/$sectionName:.*?(?=\n\n|\Z)/is", $description, $matches)) {
            $text = $matches[0];
            preg_match_all('/[•\-\*]\s*([^•\-\*\n]+)/', $text, $matches);
            if (!empty($matches[1])) {
                $items = array_map('trim', $matches[1]);
                return array_filter($items);
            }
        }
        return !empty($items) ? $items : null;
    }

    private static function extractBenefits(string $description): ?array
    {
        return self::extractSection($description, 'Benefits?');
    }

    private static function extractQualifications(string $description): ?array
    {
        return self::extractSection($description, 'Qualifications?|Requirements?');
    }

    private static function extractResponsibilities(string $description): ?array
    {
        return self::extractSection($description, 'Responsibilities?|Duties?');
    }
}
