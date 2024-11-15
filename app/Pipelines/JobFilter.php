<?php

namespace App\Pipelines;

use App\Enums\JobType;
use Closure;

class JobFilter
{
    public function handle($jobs, Closure $next)
    {

        if (request()->filled('search')) {
            //dd($jobs->where('job_title', 'like', '%' . request('search') . '%')->get());
            $jobs->where('job_title', 'like', '%' . request('search') . '%');
            //dd($jobs->get());
        }

        if (request()->filled('location')) {
            $jobs->where('city', 'like', '%' . request('location') . '%');
        }

        if (request()->filled('category')) {
            $jobs->whereHas('category', function($query) {
                $query->where('id', request('category'));
            });
            //dd($jobs->get());
        }

        $jobTypes = [];
        if (request()->filled('fulltime')) {
            $jobTypes[] = JobType::FULL_TIME->value;
        }

        if (request()->filled('contractor')) {
            $jobTypes[] = JobType::CONTRACTOR->value;
        }

        if (request()->filled('parttime')) {
            $jobTypes[] = JobType::PART_TIME->value;
        }

        if (!empty($jobTypes)) {
            $jobs->whereIn('employment_type', $jobTypes);
        }

        return $next($jobs);
    }
}
