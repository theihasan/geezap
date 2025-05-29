<?php declare(strict_types=1);

namespace App\Caches;

use App\Models\Country;
use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class CountryJobCountCache
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

    public static function getTopCountries($limit = 5)
    {
        return Cache::remember("top_countries_$limit", 60 * 60 * 24, function () use ($limit) {
            return Country::join('job_listings', 'countries.code', '=', 'job_listings.country')
                ->groupBy('countries.id', 'countries.code', 'countries.name')
                ->selectRaw('countries.id, countries.name, countries.code, count(job_listings.id) as jobs_count')
                ->orderBy('jobs_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }
}
