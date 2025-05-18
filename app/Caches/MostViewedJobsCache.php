<?php

namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class MostViewedJobsCache{

    public static function get()
    {
        return Cache::remember(self::key(), 60 * 24 * 7, function () {
            return JobListing::query()
                ->select('id',
                    'employer_name',
                    'slug','state',
                    'employment_type',
                    'job_title',
                    'views',
                    'min_salary',
                    'max_salary',
                    'salary_period',
                    'created_at',
                    'description'
                )
                ->orderByDesc('views')
                ->take(4)
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
