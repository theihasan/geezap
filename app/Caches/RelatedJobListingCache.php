<?php
namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class RelatedJobListingCache{

    public static function get($slug, $job)
    {
        return Cache::remember(self::key($slug), 60 * 24, function () use ($job) {
            return JobListing::query()
                ->where('job_category', $job->job_category)
                ->where('id', '!=', $job->id)
                ->inRandomOrder()
                ->limit(3)
                ->get();
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