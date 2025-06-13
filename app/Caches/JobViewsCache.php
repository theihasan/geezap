<?php
namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class JobViewsCache{

    public static function get($slug, $ip)
    {
        return Cache::remember(self::key($slug, $ip), 5 * 60, function () use ($slug) {
            MostViewedJobsCache::invalidate();
            return JobListing::query()
                ->where('slug', $slug)
                ->firstOrFail()
                ->increment('views', 20);
        });
    }

    public static function invalidate($slug, $ip)
    {
        return Cache::forget(self::key($slug, $ip));
    }

    public static function key($slug, $ip)
    {
        return 'job_' . $slug . '_view_' . $ip . '_' . now()->format('Y-m-d-H-i');
    }

}