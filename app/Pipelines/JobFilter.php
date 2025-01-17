<?php

namespace App\Pipelines;

use App\Enums\JobType;
use Closure;

class JobFilter
{
    public function handle($jobs, Closure $next)
    {
        // Search job by keywords
        $jobs->when(request()->get('search'), function ($query, $keyword) {
            $query->where('job_title', 'like', '%' . $keyword . '%');
        });

        // Filter by city
        $jobs->when(request()->get('location'), function ($query, $location) {
            $query->where('city', 'like', '%' . $location . '%');
        });

        // Filter by category
        $jobs->when(request()->get('category'), function ($query, $category) {
            $query->whereRelation('category', 'id', $category);
        });

        // Filter by job type
        $jobs->when(request()->filled('types'), function ($query) {
            $jobTypes = explode(',', request()->get('types', []));
            $query->whereIn('employment_type', $jobTypes);
        });

        return $next($jobs);
    }
}
