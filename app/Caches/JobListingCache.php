<?php

namespace App\Caches;

use App\Helpers\RedisCache;
use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class JobListingCache
{
    public static function get($slug)
    {
        return Cache::remember(self::key($slug), 60 * 24, function () use ($slug) {
            return JobListing::where('slug', $slug)
                ->firstOrFail();
        });
    }

    public static function invalidate($slug = null): bool | int
    {
        if ($slug) {
            return Cache::forget(self::key($slug));
        }

        return RedisCache::forgetPattern('job_*');
    }

    public static function key($slug)
    {
        return 'job_' . $slug;
    }
}
