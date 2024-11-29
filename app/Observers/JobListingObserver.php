<?php
namespace App\Observers;

use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class JobListingObserver
{
    public function creating(JobListing $jobListing): void
    {
        $jobListing->uuid = Str::uuid();
        $jobListing->slug = Str::slug($jobListing->job_title.'-'.time());
    }

    public function updating(JobListing $jobListing): void
    {

    }

    public function created(JobListing $jobListing): void
    {
        $this->clearCache();
    }

    public function updated(JobListing $jobListing): void
    {
        $this->clearCache();
    }

    public function deleted(JobListing $jobListing): void
    {
        $this->clearCache();
    }

    protected function clearCache(): void
    {
        Cache::forget('jobs_page_*');

        Cache::forget('job_*');
        Cache::forget('related_jobs_*');

        Cache::forget('latestJobs');
        Cache::forget('jobCategories');
        Cache::forget('todayAddedJobsCount');
        Cache::forget('jobCategoriesJobsCount');
        Cache::forget('jobCategoriesCount');
        Cache::forget('jobCategoriesAll');
    }
}
