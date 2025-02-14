<?php
namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pipeline\Pipeline;

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

            return $jobsQuery
                ->latest('posted_at')
                ->inRandomOrder()
                ->paginate(20)
                ->withQueryString();
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
