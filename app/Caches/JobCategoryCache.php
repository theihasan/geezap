<?php

namespace App\Caches;

use App\Models\JobCategory;
use Illuminate\Support\Facades\Cache;

class JobCategoryCache{

    public static function get()
    {
        return Cache::remember('jobCategories', 24 * 60, function () {
            return JobCategory::withCount('jobs')
                ->orderByDesc('jobs_count')
                ->get();
        });
    }

    public static function getTopCategories()
    {
        return self::get()->take(8);
    }

    public static function invalidate(): bool
    {
        return Cache::forget(self::key());
    }

    public static function key(): string
    {
        return 'jobCategories';
    }

}
