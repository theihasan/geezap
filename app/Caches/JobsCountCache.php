<?php
namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class JobsCountCache{

    public static function todayAdded()
    {
        return Cache::remember(self::todayAddedKey(), 24 * 60, function () {
            return JobListing::whereDate('created_at', today())->count();
        });
    }

    public static function lastWeekAdded()
    {
        return Cache::remember(self::lastWeekAddedKey(), 24 * 60, function () {
            return JobListing::whereBetween('created_at', [now()->subWeek(), now()])->count();
        });
    }

    public static function availableJobsCount()
    {
        return Cache::remember(self::availableJobsKey(), 24 * 60, function () {
            return JobListing::count();
        });
    }

    public static function categoriesCount()
    {
        return Cache::remember(self::categoriesCountKey(), 24 * 60, function () {
            return JobListing::distinct()->count('job_category');
        });
    }

    public static function invalidateTodayAdded()
    {
        Cache::forget(self::todayAdded());
    }

    public static function invalidateLastWeekAdded()
    {
        Cache::forget(self::lastWeekAdded());
    }

    public static function invalidateCategoriesCount()
    {
        Cache::forget(self::categoriesCountKey());
    }

    public static function invalidateAvailableJobsCount()
    {
        Cache::forget(self::availableJobsKey());
    }

    public static function todayAddedKey()
    {
        return 'todayAddedJobsCount';
    }

    public static function lastWeekAddedKey()
    {
        return 'lastWeekAddedJobsCount';
    }

    public static function availableJobsKey()
    {
        return 'availableJobs';
    }

    public static function categoriesCountKey()
    {
        return 'jobCategoriesCount';
    }

}