<?php

namespace App\Caches;

use App\Models\Country;
use App\Models\JobCategory;
use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

final class JobFilterCache
{
    public static function getCategories()
    {
        return Cache::remember(self::key('categories'), now()->addDay(), function () {
            return JobCategory::all();
        });
    }

    public static function getPublishers()
    {
        return Cache::remember(self::key('publishers'), now()->addHour(), function () {
            return JobListing::distinct()->pluck('publisher');
        });
    }

    public static function getCountries()
    {
        return Cache::remember(self::key('countries'), now()->addDay(), function () {
            $countryCodes = JobListing::distinct()
                ->whereNotNull('country')
                ->pluck('country');

            return Country::whereIn('code', $countryCodes)
                ->get()
                ->keyBy('code');
        });
    }

    public static function invalidate($key = null)
    {
        if ($key) {
            return Cache::forget(self::key($key));
        }

        Cache::forget(self::key('categories'));
        Cache::forget(self::key('publishers'));
        Cache::forget(self::key('countries'));
    }

    private static function key($type)
    {
        return "job_filter_{$type}";
    }
}
