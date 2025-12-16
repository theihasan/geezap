<?php

namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class ImprovedRelatedJobListingCache
{
    public static function get(JobListing $job)
    {
        $cacheKey = 'related_jobs_v2_' . $job->slug;
        $tags = ['related_jobs_v2', 'related_jobs_v2_category_' . $job->job_category];

        return Cache::tags($tags)->remember($cacheKey, 60 * 24, function () use ($job) {
            return self::getRelatedJobsRandomSampling($job);
        });
    }

    /**
     * Fast random sampling using MySQL RAND() function
     */
    private static function getRelatedJobsRandomSampling(JobListing $job)
    {
        // Use MySQL's RAND() function with seed for deterministic but fast randomization
        $seed = crc32($job->slug . date('Y-m-d')) % 1000000;

        return JobListing::query()
            ->where('job_category', (int) $job->job_category) // Ensure integer comparison
            ->where('id', '!=', $job->id)
            ->select('id', 'employer_name', 'slug', 'state', 'country', 'employment_type', 'job_title', 'min_salary', 'max_salary', 'salary_period', 'created_at', 'description', 'employer_logo', 'posted_at')
            ->orderByRaw('RAND(?)', [$seed]) // Use seeded random for consistency
            ->limit(3)
            ->get();
    }

    public static function invalidateForCategory($category)
    {
        Cache::tags('related_jobs_v2_category_' . $category)->flush();
    }
}