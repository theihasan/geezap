<?php
namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class RelatedJobListingCache{

    public static function get($slug, $job)
    {
        return Cache::remember(self::key($slug), 60 * 24, function () use ($job) {
            // Fetch up to 3 random related jobs in a single query, excluding the current job
            return JobListing::query()
                ->where('job_category', $job->job_category)
                ->where('id', '!=', $job->id)
                ->inRandomOrder()
                ->limit(3)
                ->get([
                    'id',
                    'employer_name',
                    'slug',
                    'state',
                    'country',
                    'employment_type',
                    'job_title',
                    'min_salary',
                    'max_salary',
                    'salary_period',
                    'created_at',
                    'description',
                ]);
        });
    }

    public static function invalidate()
    {
        return Cache::forget(self::keyPrefix());
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