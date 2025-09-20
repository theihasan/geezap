<?php
namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class RelatedJobListingCache{

    public static function get($slug, $job)
    {
        return Cache::remember(self::key($slug), 60 * 24, function () use ($job) {
            // Get count of jobs in the same category (excluding current job)
            $totalCount = JobListing::query()
                ->where('job_category', $job->job_category)
                ->where('id', '!=', $job->id)
                ->count();
                
            if ($totalCount <= 3) {
                // If 3 or fewer jobs, get them all
                return JobListing::query()
                    ->where('job_category', $job->job_category)
                    ->where('id', '!=', $job->id)
                    ->select('id', 'employer_name', 'slug', 'state', 'country', 'employment_type', 'job_title', 'min_salary', 'max_salary', 'salary_period', 'created_at', 'description', 'employer_logo', 'posted_at')
                    ->get();
            }
            
            // For large datasets, use hash-based randomization for consistent performance
            // This avoids ORDER BY RAND() while still providing good randomization
            $randomSeed = crc32($job->slug . date('Y-m-d')); // Daily rotation for variety
            $randomOffset = $randomSeed % max(1, $totalCount - 2);
            
            return JobListing::query()
                ->where('job_category', $job->job_category)
                ->where('id', '!=', $job->id)
                ->select('id', 'employer_name', 'slug', 'state', 'country', 'employment_type', 'job_title', 'min_salary', 'max_salary', 'salary_period', 'created_at', 'description', 'employer_logo', 'posted_at')
                ->orderBy('id') // Use deterministic ordering for consistent results
                ->offset($randomOffset)
                ->limit(3)
                ->get();
        });
    }

    public static function invalidate()
    {
        return Cache::forget(self::keyPrefix());
    }

    public static function invalidateForCategory($jobCategory)
    {
        // Only invalidate related jobs cache for jobs in the same category
        // This is more targeted than invalidating all related job caches
        $pattern = 'related_jobs_*';
        $keys = Cache::getRedis()->keys($pattern);
        
        foreach ($keys as $key) {
            // Get the job slug from the cache key
            $slug = str_replace('related_jobs_', '', $key);
            $job = \App\Models\JobListing::where('slug', $slug)->first();
            
            if ($job && $job->job_category === $jobCategory) {
                Cache::forget($key);
            }
        }
    }

    public static function key($slug)
    {
        return 'related_jobs_' . $slug;
    }

    public static function keyPrefix()
    {
        return 'related_jobs_*';
    }

}