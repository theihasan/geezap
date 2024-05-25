<?php

namespace App\Observers;

use App\Models\JobListing;
use Illuminate\Support\Str;

class JobListingObserver
{
    public function creating(JobListing $jobListing)
    {
        $jobListing->uuid = Str::uuid();
        $jobListing->slug = Str::slug($jobListing->job_title);
    }

    public function updating(JobListing $jobListing)
    {
        $jobListing->slug = Str::slug($jobListing->job_title);
    }
}
