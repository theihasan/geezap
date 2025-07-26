<?php

namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class CountryAwareLatestJobsCache
{
    public static function get(array $excludeIds = [], ?string $userCountry = null, int $limit = 4)
    {
        return Cache::remember(self::key($userCountry, $excludeIds), 60 * 24, function () use ($excludeIds, $userCountry, $limit) {
            $query = JobListing::query()
                ->whereNotIn('id', $excludeIds);

            if ($userCountry) {
                // Get country-specific jobs first
                $countryJobs = (clone $query)
                    ->where('country', $userCountry)
                    ->latest()
                    ->take($limit)
                    ->get();

                // If we don't have enough country-specific jobs, fill with global jobs
                if ($countryJobs->count() < $limit) {
                    $needed = $limit - $countryJobs->count();
                    $countryExcludeIds = array_merge($excludeIds, $countryJobs->pluck('id')->toArray());
                    
                    $globalJobs = $query
                        ->whereNotIn('id', $countryExcludeIds)
                        ->latest()
                        ->take($needed)
                        ->get();
                    
                    return $countryJobs->merge($globalJobs);
                }
                
                return $countryJobs;
            }

            // Default behavior for users without country
            return $query
                ->latest()
                ->take($limit)
                ->get();
        });
    }

    public static function invalidate(?string $userCountry = null)
    {
        // Clear country-specific cache
        if ($userCountry) {
            Cache::tags(["latest-jobs-{$userCountry}"])->flush();
        }
        
        // Also clear global cache
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