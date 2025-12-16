<?php

namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class SmartRelatedJobListingCache
{
    public static function get(JobListing $job)
    {
        $cacheKey = 'smart_related_jobs_' . $job->slug;
        $tags = ['smart_related_jobs', 'smart_related_jobs_category_' . $job->job_category];

        return Cache::tags($tags)->remember($cacheKey, 60 * 24, function () use ($job) {
            return self::getSmartRelatedJobs($job);
        });
    }

    /**
     * Smart algorithm combining multiple similarity factors
     */
    private static function getSmartRelatedJobs(JobListing $job)
    {
        $seed = crc32($job->slug . date('Y-m-d')) % 1000000;

        return JobListing::query()
            ->where('job_category', (int) $job->job_category)
            ->where('id', '!=', $job->id)
            ->select([
                'id', 'employer_name', 'slug', 'state', 'country', 'employment_type', 
                'job_title', 'min_salary', 'max_salary', 'salary_period', 'created_at', 
                'description', 'employer_logo', 'posted_at'
            ])
            ->selectRaw('
                (
                    CASE WHEN state = ? THEN 15 ELSE 0 END +
                    CASE WHEN country = ? THEN 10 ELSE 0 END +
                    CASE WHEN employment_type = ? THEN 8 ELSE 0 END +
                    CASE WHEN employer_name = ? THEN 5 ELSE 0 END +
                    CASE 
                        WHEN (min_salary BETWEEN ? AND ?) OR (max_salary BETWEEN ? AND ?) THEN 5 
                        ELSE 0 
                    END +
                    (RAND(?) * 20)
                ) as relevance_score
            ', [
                $job->state,
                $job->country, 
                $job->employment_type,
                $job->employer_name,
                max(0, ($job->min_salary ?? 0) * 0.8),
                ($job->max_salary ?? 999999) * 1.2,
                max(0, ($job->min_salary ?? 0) * 0.8),
                ($job->max_salary ?? 999999) * 1.2,
                $seed
            ])
            ->orderBy('relevance_score', 'DESC')
            ->limit(3)
            ->get();
    }

    public static function invalidateForCategory($category)
    {
        Cache::tags('smart_related_jobs_category_' . $category)->flush();
    }
}