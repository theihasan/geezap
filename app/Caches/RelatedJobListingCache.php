<?php
namespace App\Caches;

use App\Helpers\RedisCache;
use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;

class RelatedJobListingCache{

    public static function get($slug, $job)
    {
        return Cache::remember(self::key($slug), 60 * 24, function () use ($job) {
            $count = JobListing::query()
                ->where('job_category', $job->job_category)
                ->where('id', '!=', $job->id)
                ->count();
                
            if ($count > 3) {
                $randomOffsets = array_rand(range(0, $count - 1), min(3, $count));
                $result = collect();
                
                foreach ((array)$randomOffsets as $offset) {
                    $randomJob = JobListing::query()
                        ->where('job_category', $job->job_category)
                        ->where('id', '!=', $job->id)
                        ->skip($offset)
                        ->take(1)
                        ->first();
                        
                    if ($randomJob) {
                        $result->push($randomJob);
                    }
                }
                
                return $result;
            }
            
            return JobListing::query()
                ->where('job_category', $job->job_category)
                ->where('id', '!=', $job->id)
                ->get();
        });
    }

    public static function invalidate()
    {
        return RedisCache::forgetPattern('related_jobs_*');
    }

    public static function key($slug)
    {
        return 'related_jobs_' . $slug;
    }

}