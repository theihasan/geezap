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
            $jobsQuery = JobListing::query()
                ->with(['category']);

            $jobsQuery = app()->make(Pipeline::class)
                ->send($jobsQuery)
                ->through([
                    \App\Pipelines\JobFilter::class,
                ])
                ->thenReturn();

            
            $totalCount = $jobsQuery->count();
            
            $page = $request->get('page', 1);
            $perPage = 6;
            
            
            $offset = ($page - 1) * $perPage;
            
            if ($userCountry) {
                return self::getCountryPrioritizedJobs($jobsQuery, $userCountry, $request, $page, $perPage, $totalCount, $offset);
            }
            
            return self::getDefaultJobs($jobsQuery, $request, $page, $perPage, $totalCount, $offset);
        });
    }

    private static function getCountryPrioritizedJobs($jobsQuery, $userCountry, $request, $page, $perPage, $totalCount, $offset)
    {
        $countryJobsQuery = clone $jobsQuery;
        $countryJobs = $countryJobsQuery
            ->where('country', $userCountry)
            ->latest('posted_at')
            ->get();

        $globalJobsQuery = clone $jobsQuery;
        $globalJobs = $globalJobsQuery
            ->where('country', '!=', $userCountry)
            ->latest('posted_at')
            ->get();

        $allJobs = $countryJobs->merge($globalJobs);
        
        $paginatedJobs = $allJobs->slice($offset, $perPage);
        
        if ($paginatedJobs->count() > $perPage * 0.5) {
            $countryJobsInPage = $paginatedJobs->where('country', $userCountry);
            $otherJobsInPage = $paginatedJobs->where('country', '!=', $userCountry);
            
            $shuffledOthers = $otherJobsInPage->shuffle();
            $finalJobs = $countryJobsInPage->merge($shuffledOthers)->take($perPage);
        } else {
            $finalJobs = $paginatedJobs;
        }

        return new LengthAwarePaginator(
            $finalJobs,
            $totalCount,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    private static function getDefaultJobs($jobsQuery, $request, $page, $perPage, $totalCount, $offset)
    {
        $jobs = $jobsQuery
            ->latest('posted_at')
            ->skip($offset)
            ->take($perPage * 2) 
            ->get();
        
        if ($jobs->count() > $perPage) {
            $jobsArray = $jobs->all();
            $randomKeys = array_rand($jobsArray, min($perPage, count($jobsArray)));
            
            $randomJobs = collect();
            foreach ((array)$randomKeys as $key) {
                $randomJobs->push($jobsArray[$key]);
            }
            
            return new LengthAwarePaginator(
                $randomJobs,
                $totalCount,
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }
        
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
            Cache::tags(["jobs-page-{$userCountry}"])->flush();
        }
        
        Cache::tags(['jobs-page'])->flush();
    }

    public static function key($request, ?string $userCountry = null)
    {
        $queryHash = md5(serialize($request->all()));
        $country = $userCountry ?? 'global';
        
        return "jobs_page_{$country}_{$request->get('page', 1)}_{$queryHash}";
    }
} 