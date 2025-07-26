<?php

namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class CountryAwareLatestJobsCache
{
    public static function get(array $excludeIds = [], ?string $userCountry = null, int $limit = 4)
    {
        return Cache::remember(self::key($userCountry, $excludeIds), 60 * 24, function () use ($excludeIds, $userCountry, $limit) {
            if ($userCountry) {
                $countryJobs = JobListing::query()
                    ->whereNotIn('id', $excludeIds)
                    ->where('country', $userCountry)
                    ->latest()
                    ->limit($limit)
                    ->get();

                if ($countryJobs->count() < $limit) {
                    $needed = $limit - $countryJobs->count();
                    $countryExcludeIds = array_merge($excludeIds, $countryJobs->pluck('id')->toArray());
                    
                    $globalJobs = JobListing::query()
                        ->whereNotIn('id', $countryExcludeIds)
                        ->latest()
                        ->limit($needed)
                        ->get();
                    
                    return $countryJobs->merge($globalJobs);
                }
                
                return $countryJobs;
            }

            return JobListing::query()
                ->whereNotIn('id', $excludeIds)
                ->latest()
                ->limit($limit)
                ->get();
        });
    }

    public static function invalidate(?string $userCountry = null)
    {
        if ($userCountry) {
            Cache::tags(["latest-jobs-{$userCountry}"])->flush();
        }
        
        Cache::tags(['latest-jobs'])->flush();
    }

    public static function key(?string $userCountry = null, array $excludeIds = [])
    {
        $excludeHash = md5(serialize($excludeIds));
        return $userCountry 
            ? "latestJobs_country_{$userCountry}_exclude_{$excludeHash}" 
            : "latestJobs_global_exclude_{$excludeHash}";
    }
} 