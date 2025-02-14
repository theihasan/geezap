<?php

namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class MostViewedJobsCache{

    public static function get()
    {
        return Cache::remember(self::key(), 60 * 24, function () {
            return JobListing::query()
                ->latest('views')
                ->limit(10)
                ->get();
        });
    }

    public static function invalidate()
    {
        return Cache::forget(self::key());
    }

    public static function key()
    {
        return 'mostViewedJobs';
    }

}