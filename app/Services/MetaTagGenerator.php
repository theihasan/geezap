<?php

namespace App\Services;

use App\DTO\DiscordCardDTO;
use App\DTO\MetaTagDTO;
use App\DTO\OpenGraphDTO;
use App\DTO\StructuredMetaDataDTO;
use App\DTO\TwitterCardDTO;
use App\Models\JobListing;
use App\Models\JobCategory;
use Illuminate\Database\Eloquent\Collection;

class MetaTagGenerator
{
    public function getHomePageMeta(
        int $availableJobs,
        int $todayAddedJobsCount,
        int $jobCategoriesCount,
        int $lastWeekAddedJobsCount,
        Collection $jobCategories
    ): MetaTagDTO {
        $topCategories = $jobCategories->take(3)->pluck('name')->implode(', ');

        $title = "Find Your Next Tech Job | {$availableJobs}+ Opportunities Available | Geezap";

        $description = "Discover your dream tech role from {$availableJobs}+ opportunities, " .
            "including {$todayAddedJobsCount} jobs added today and {$lastWeekAddedJobsCount} this week. " .
            "Browse {$jobCategoriesCount} categories including {$topCategories}. " .
            "AI-powered job matching from LinkedIn, Upwork, Indeed, and ZipRecruiter with smart cover letter generation.";

        return new MetaTagDTO(
            title: $title,
            description: $description,
            keywords: "job search, AI job matching, career platform, {$topCategories}, remote jobs, tech jobs, LinkedIn jobs, Upwork, Indeed, ZipRecruiter",
            og: new OpenGraphDTO(
                title: $title,
                description: $description,
                type: 'website'
            ),
            twitter: new TwitterCardDTO(
                title: $title,
                description: $description
            ),
            discord: new DiscordCardDTO(
                title: $title,
                description: $description
            )
        );
    }

    public function getJobDetailsMeta(JobListing $job): MetaTagDTO
    {
        $location = $job->is_remote ? 'Remote' : $job->city;
        $salary = ($job->min_salary && $job->max_salary)
            ? " • \${$job->min_salary}-\${$job->max_salary}/{$job->salary_period}"
            : '';

        $title = "{$job->job_title} at {$job->employer_name} • {$location}{$salary} | Geezap";

        $description = "{$job->job_title} position at {$job->employer_name}. " .
            ($job->is_remote ? "Remote position" : "Location: {$job->city}") . ". " .
            "{$job->employment_type} role" .
            ($job->min_salary ? " with salary \${$job->min_salary}-\${$job->max_salary}" : "") . ". " .
            substr(strip_tags($job->description), 0, 150) . "...";

        $image = $job->employer_logo ?? asset('assets/images/favicon.ico');

        return new MetaTagDTO(
            title: $title,
            description: $description,
            keywords: "{$job->job_title}, {$job->employer_name}, {$job->job_category}, {$location} jobs, tech jobs",
            og: new OpenGraphDTO(
                title: $title,
                description: $description,
                type: 'article',
                image: $image
            ),
            twitter: new TwitterCardDTO(
                title: $title,
                description: $description,
                image: $image
            ),
            discord: new DiscordCardDTO(
                title: $title,
                description: $description,
                image: $image
            ),
            structuredData: new StructuredMetaDataDTO([
                '@context' => 'https://schema.org',
                '@type' => 'JobPosting',
                'title' => $job->job_title,
                'description' => strip_tags($job->description),
                'datePosted' => $job->created_at->toISO8601String(),
                'employmentType' => $job->employment_type,
                'hiringOrganization' => [
                    '@type' => 'Organization',
                    'name' => $job->employer_name,
                    'logo' => $image,
                ],
                'jobLocation' => [
                    '@type' => 'Place',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressLocality' => $job->city,
                        'addressCountry' => $job->country ?? 'US'
                    ]
                ],
                'baseSalary' => [
                    '@type' => 'MonetaryAmount',
                    'currency' => 'USD',
                    'value' => [
                        '@type' => 'QuantitativeValue',
                        'minValue' => $job->min_salary,
                        'maxValue' => $job->max_salary,
                        'unitText' => strtoupper($job->salary_period)
                    ]
                ]
            ])
        );
    }
}
