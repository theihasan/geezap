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
                return self::getCountryPrioritizedJobs($request, $userCountry, $page, $perPage, $offset);
            }
            
            return self::getDefaultJobs($request, $page, $perPage, $offset);
        });
    }

    private static function getCountryPrioritizedJobs($request, $userCountry, $page, $perPage, $offset)
    {
        // Build base query with filters
        $baseQuery = JobListing::query()->with(['category']);
        
        $baseQuery = app()->make(Pipeline::class)
            ->send($baseQuery)
            ->through([
                \App\Pipelines\JobFilter::class,
            ])
            ->thenReturn();

        // Get total count for pagination
        $totalCount = $baseQuery->count();

        // Simple approach: Get country jobs first, then fill with global jobs
        $finalJobs = collect();
        
        // Strategy 1: Get country jobs for this page
        $countryJobs = (clone $baseQuery)
            ->where('country', $userCountry)
            ->latest('posted_at')
            ->skip($offset)
            ->take($perPage)
            ->get();
        
        $finalJobs = $countryJobs;
        
        // Strategy 2: If we don't have enough country jobs, fill with international jobs
        if ($finalJobs->count() < $perPage) {
            $needed = $perPage - $finalJobs->count();
            $excludeIds = $finalJobs->pluck('id')->toArray();
            
            // Calculate offset for global jobs based on how many country jobs we skipped
            $countryJobsCount = (clone $baseQuery)->where('country', $userCountry)->count();
            $countryJobsUsed = min($offset + $finalJobs->count(), $countryJobsCount);
            $globalOffset = max(0, $offset - $countryJobsUsed);
            
            $globalJobs = (clone $baseQuery)
                ->where('country', '!=', $userCountry)
                ->whereNotIn('id', $excludeIds)
                ->latest('posted_at')
                ->skip($globalOffset)
                ->take($needed)
                ->get();
            
            $finalJobs = $finalJobs->merge($globalJobs);
        }

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