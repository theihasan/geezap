<?php

namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class CountryAwareMostViewedJobsCache
{
    public static function get(?string $userCountry = null, int $limit = 4)
    {
        return Cache::remember(self::key($userCountry), 60 * 24 * 7, function () use ($userCountry, $limit) {
            if ($userCountry) {
                $countryJobs = JobListing::query()
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
                    )
                    ->where('country', $userCountry)
                    ->orderByDesc('views')
                    ->limit($limit)
                    ->get();

                if ($countryJobs->count() < $limit) {
                    $needed = $limit - $countryJobs->count();
                    $excludeIds = $countryJobs->pluck('id')->toArray();
                    
                    $globalJobs = JobListing::query()
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
                        )
                        ->whereNotIn('id', $excludeIds)
                        ->orderByDesc('views')
                        ->limit($needed)
                        ->get();
                    
                    return $countryJobs->merge($globalJobs);
                }
                
                return $countryJobs;
            }

            return JobListing::query()
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
                )
                ->orderByDesc('views')
                ->limit($limit)
                ->get();
        });
    }

    public static function invalidate(?string $userCountry = null)
    {
        if ($userCountry) {
            Cache::forget(self::key($userCountry));
        }
        
        Cache::forget(self::key(null));
    }

    public static function key(?string $userCountry = null)
    {
        return $userCountry 
            ? "mostViewedJobs_country_{$userCountry}" 
            : 'mostViewedJobs_global';
    }
} 