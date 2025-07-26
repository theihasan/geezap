<?php

namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class CountryAwareMostViewedJobsCache
{
    public static function get(?string $userCountry = null, int $limit = 4)
    {
        return Cache::remember(self::key($userCountry), 60 * 24 * 7, function () use ($userCountry, $limit) {
            $query = JobListing::query()
                ->select('id',
                    'employer_name',
                    'slug',
                    'state',
                    'country',
                    'employment_type',
                    'job_title',
                    'views',
                    'min_salary',
                    'max_salary',
                    'salary_period',
                    'created_at',
                    'description'
                );

            if ($userCountry) {
                // Get country-specific jobs first, then others
                $countryJobs = (clone $query)
                    ->where('country', $userCountry)
                    ->orderByDesc('views')
                    ->take($limit)
                    ->get();

                if ($countryJobs->count() < $limit) {
                    $needed = $limit - $countryJobs->count();
                    $excludeIds = $countryJobs->pluck('id')->toArray();
                    
                    $globalJobs = $query
                        ->whereNotIn('id', $excludeIds)
                        ->orderByDesc('views')
                        ->take($needed)
                        ->get();
                    
                    return $countryJobs->merge($globalJobs);
                }
                
                return $countryJobs;
            }

            // Default behavior for users without country
            return $query
                ->orderByDesc('views')
                ->take($limit)
                ->get();
        });
    }

    public static function invalidate(?string $userCountry = null)
    {
        if ($userCountry) {
            Cache::forget(self::key($userCountry));
        }
        
        // Also invalidate global cache
        Cache::forget(self::key(null));
        
        // Clear all country-specific caches if needed
        Cache::tags(['most-viewed-jobs'])->flush();
    }

    public static function key(?string $userCountry = null)
    {
        return $userCountry 
            ? "mostViewedJobs_country_{$userCountry}" 
            : 'mostViewedJobs_global';
    }
} 