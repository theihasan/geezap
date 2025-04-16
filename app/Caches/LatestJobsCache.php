<?php
namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class LatestJobsCache{

    public static function get(array $ids)
    {
        return Cache::remember('latestJobs', 60 * 24, function () use($ids) {
            return JobListing::latest()
                ->whereNotIn('id', $ids)
                ->limit(4)
                ->get();
        });
    }

    public static function invalidate()
    {
        return Cache::forget(self::key());
    }

    public static function key()
    {
        return 'latestJobs';
    }

}
