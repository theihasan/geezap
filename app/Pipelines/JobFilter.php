<?php

namespace App\Pipelines;

use App\Enums\JobType;
use App\Models\JobListing as Job;
use Closure;

class JobFilter
{
    public function handle($jobs, Closure $next)
    {
        if (request()->has('search')) {
            $jobs = $jobs->where('job_title', 'like', '%' . request('search') . '%');
        }

        if (request()->has('category')) {
            $jobs = $jobs->where('job_category', request('category'));
        }

        $jobTypes = collect([JobType::FULL_TIME->value, JobType::CONTRACTOR->value, JobType::PART_TIME->value])->filter(function ($type) {
            return request()->has($type);
        })->toArray();

        if (!empty($jobTypes)) {
            $jobs = $jobs->whereIn('employment_type', $jobTypes);
        }

        return $next($jobs);
    }
}
