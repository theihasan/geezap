<?php
namespace App\Caches;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
class CountriesCache
{
    public static function get(): Collection
    {
        return Cache::remember('countries', 24 * 60, function () {
            return \App\Models\Country::all();
        });
    }
}