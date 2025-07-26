<?php

namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Pagination\LengthAwarePaginator;

class CountryAwareJobPageCache
{
    public static function get($request, ?string $userCountry = null)
    {
        return Cache::remember(self::key($request, $userCountry), 60 * 24, function () use ($request, $userCountry) {
            $page = $request->get('page', 1);
            $perPage = 6;
            $offset = ($page - 1) * $perPage;

            if ($userCountry) {
                // Memory-efficient country-aware job fetching
                return self::getCountryPrioritizedJobsEfficient($request, $userCountry, $page, $perPage, $offset);
            }
            
            // Default behavior (original logic)
            return self::getDefaultJobs($request, $page, $perPage, $offset);
        });
    }

    private static function getCountryPrioritizedJobsEfficient($request, $userCountry, $page, $perPage, $offset)
    {
        // Build base query with filters
        $baseQuery = JobListing::query()->with(['category']);
        
        $baseQuery = app()->make(Pipeline::class)
            ->send($baseQuery)
            ->through([
                \App\Pipelines\JobFilter::class,
            ])
            ->thenReturn();

        // Get total count for pagination (only count, don't load data)
        $totalCount = $baseQuery->count();

        // Calculate how many country jobs and global jobs we need
        $countryJobsQuery = clone $baseQuery;
        $countryJobsCount = $countryJobsQuery->where('country', $userCountry)->count();
        
        // Strategy: Try to get 70% country jobs, 30% international jobs per page
        $preferredCountryJobs = max(1, (int)($perPage * 0.7));
        $preferredGlobalJobs = $perPage - $preferredCountryJobs;

        // Adjust based on available country jobs
        if ($countryJobsCount < $preferredCountryJobs) {
            $countryJobsToTake = min($countryJobsCount, $perPage);
            $globalJobsToTake = $perPage - $countryJobsToTake;
        } else {
            $countryJobsToTake = $preferredCountryJobs;
            $globalJobsToTake = $preferredGlobalJobs;
        }

        $finalJobs = collect();

        // Get country jobs first (database-level pagination)
        if ($countryJobsToTake > 0) {
            $countryOffset = (int)($offset * 0.7); // Proportional offset for country jobs
            $countryJobs = (clone $baseQuery)
                ->where('country', $userCountry)
                ->latest('posted_at')
                ->skip($countryOffset)
                ->take($countryJobsToTake)
                ->get();
            
            $finalJobs = $finalJobs->merge($countryJobs);
        }

        // Get global jobs to fill remaining slots
        if ($globalJobsToTake > 0 && $finalJobs->count() < $perPage) {
            $globalOffset = (int)($offset * 0.3); // Proportional offset for global jobs
            $excludeIds = $finalJobs->pluck('id')->toArray();
            
            $globalJobs = (clone $baseQuery)
                ->where('country', '!=', $userCountry)
                ->whereNotIn('id', $excludeIds)
                ->latest('posted_at')
                ->skip($globalOffset)
                ->take($globalJobsToTake)
                ->get();
            
            $finalJobs = $finalJobs->merge($globalJobs);
        }

        // Ensure we don't exceed perPage limit
        $finalJobs = $finalJobs->take($perPage);

        return new LengthAwarePaginator(
            $finalJobs,
            $totalCount,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    private static function getDefaultJobs($request, $page, $perPage, $offset)
    {
       
        $jobsQuery = JobListing::query()->with(['category']);
        
        $jobsQuery = app()->make(Pipeline::class)
            ->send($jobsQuery)
            ->through([
                \App\Pipelines\JobFilter::class,
            ])
            ->thenReturn();

        $totalCount = $jobsQuery->count();
        
        $jobs = $jobsQuery
            ->latest('posted_at')
            ->skip($offset)
            ->take($perPage)
            ->get();
        
        return new LengthAwarePaginator(
            $jobs,
            $totalCount,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    public static function invalidate(?string $userCountry = null)
    {
        // Clear specific country cache if provided
        if ($userCountry) {
            $countryKeys = [
                "jobs_page_{$userCountry}_1_*",
                "jobs_page_{$userCountry}_2_*", 
                "jobs_page_{$userCountry}_3_*",
                "jobs_page_{$userCountry}_4_*",
                "jobs_page_{$userCountry}_5_*"
            ];
            
            foreach ($countryKeys as $pattern) {
                Cache::forget($pattern);
            }
        }
        
        $globalKeys = [
            'jobs_page_global_1_*',
            'jobs_page_global_2_*',
            'jobs_page_global_3_*',
            'jobs_page_global_4_*',
            'jobs_page_global_5_*'
        ];
        
        foreach ($globalKeys as $pattern) {
            Cache::forget($pattern);
        }
    }

    public static function key($request, ?string $userCountry = null)
    {
        $queryHash = md5(serialize($request->all()));
        $country = $userCountry ?? 'global';
        
        return "jobs_page_{$country}_{$request->get('page', 1)}_{$queryHash}";
    }
} 