<?php
namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class RelatedJobListingCache{

    public static function get($slug, $job)
    {
        return Cache::remember(self::key($slug), 60 * 24, function () use ($job) {
            // Get all jobs in the same category except the current one
            $allJobs = JobListing::query()
                ->where('job_category', $job->job_category)
                ->where('id', '!=', $job->id)
                ->select('id', 'employer_name', 'slug', 'state', 'country', 'employment_type', 'job_title', 'min_salary', 'max_salary', 'salary_period', 'created_at', 'description', 'employer_logo', 'posted_at')
                ->get();
                
            if ($allJobs->count() <= 3) {
                return $allJobs;
            }
            
            // Use a more efficient randomization approach
            // Get a random sample of 3 jobs from the collection
            return $allJobs->random(min(3, $allJobs->count()));
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