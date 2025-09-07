<?php
namespace App\Caches;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class RelatedJobListingCache{

    public static function get($slug, $job)
    {
        return Cache::remember(self::key($slug), 60 * 24, function () use ($job) {
            $count = JobListing::query()
                ->where('job_category', $job->job_category)
                ->count();
                
            if ($count > 3) {
                $maxOffset = min($count - 1, 100);
                $randomOffsets = (array)array_rand(range(0, $maxOffset), min(3, $maxOffset + 1));
                
                $result = collect($randomOffsets)
                    ->map(function ($offset) use ($job) {
                        return JobListing::query()
                            ->where('job_category', $job->job_category)
                            ->skip($offset)
                            ->take(1)
                            ->first();
                    })
                    ->reject(function ($jobListing) use ($job) {
                        return !$jobListing || $jobListing->id === $job->id;
                    })
                    ->values();
                
                return $result;
            }
            
            return JobListing::query()
                ->where('job_category', $job->job_category)
                ->get()
                ->reject(function ($jobListing) use ($job) {
                    return $jobListing->id === $job->id;
                });
        });
    }

    public static function invalidate()
    {
        return Cache::forget(self::keyPrefix());
    }

    public static function key($slug)
    {
        return 'related_jobs_' . $slug;
    }

    public static function keyPrefix()
    {
        return 'related_jobs_*';
    }

}