<?php
namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Pagination\LengthAwarePaginator;

class JobPageCache{

    public static function get($request)
    {
        return Cache::remember(self::key($request), 60 * 24, function () use ($request) {
            $jobsQuery = JobListing::query()
                ->with(['category']);

            $jobsQuery = app()->make(Pipeline::class)
                ->send($jobsQuery)
                ->through([
                    \App\Pipelines\JobFilter::class,
                ])
                ->thenReturn();

            // Get the total count for pagination
            $totalCount = $jobsQuery->count();
            
            // Get current page from request
            $page = $request->get('page', 1);
            $perPage = 6;
            
            // Calculate offset for the current page
            $offset = ($page - 1) * $perPage;
            
            // Get jobs for the current page ordered by posted_at
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
                
                $paginator = new LengthAwarePaginator(
                    $randomJobs,
                    $totalCount,
                    $perPage,
                    $page,
                    ['path' => $request->url(), 'query' => $request->query()]
                );
                
                return $paginator;
            }
            
            return new LengthAwarePaginator(
                $jobs,
                $totalCount,
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        });
    }

    public static function invalidate()
    {
        return Cache::forget(self::keyPrefix());
    }

    public static function key($request)
    {
        return 'jobs_page_' . $request->get('page', 1) . '_' . md5(serialize($request->all()));
    }

    public static function keyPrefix()
    {
        return 'jobs_*' ;
    }

}
