<?php

namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class ImprovedRelatedJobListingCache
{
    public static function get(JobListing $job)
    {
        $cacheKey = 'related_jobs_v2_' . $job->slug;

        return Cache::remember($cacheKey, 60 * 24, function () use ($job) {
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
        $pattern = 'related_jobs_v2_*';
        
        // Get all cache keys matching the pattern
        $keys = Cache::getRedis()->keys(config('cache.prefix') . ':' . $pattern);
        
        if (!empty($keys)) {
            // Remove the cache prefix from keys
            $keys = array_map(function ($key) {
                return str_replace(config('cache.prefix') . ':', '', $key);
            }, $keys);
            
            foreach ($keys as $key) {
                $jobSlug = str_replace('related_jobs_v2_', '', $key);
                $job = JobListing::where('slug', $jobSlug)->first();
                
                if ($job && $job->job_category == $category) {
                    Cache::forget($key);
                }
            }
        }
    }
}