<?php

namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class JobListingCache{

    public static function get($slug)
    {
        return Cache::remember(self::key($slug), 60 * 24, function () use ($slug) {
            return JobListing::where('slug', $slug)
                ->firstOrFail();
        });
    }

    public static function invalidate()
    {
        return Cache::forget(self::key('*'));
    }

    public static function key($slug)
    {
        return 'job_' . $slug;
    }
    
}