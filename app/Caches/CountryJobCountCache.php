<?php declare(strict_types=1);

namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

final class CountryJobCountCache
{
    public static function get($country)
    {
        return Cache::remember("job_count_$country", 60 * 60 * 24, function () use ($country) {
            return JobListing::where('country', $country)->count();
        });
    }

    public static function getAll()
    {
        return Cache::remember('all_country_job_counts', 60 * 60 * 24, function () {
            return JobListing::groupBy('country')
                ->selectRaw('country, count(*) as count')
                ->pluck('count', 'country')
                ->toArray();
        });
    }
}
