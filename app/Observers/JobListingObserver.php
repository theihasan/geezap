<?php

namespace App\Observers;

use App\Caches\CountryAwareJobPageCache;
use App\Caches\CountryAwareLatestJobsCache;
use App\Caches\CountryAwareMostViewedJobsCache;
use App\Caches\JobCategoryCache;
use App\Caches\JobFilterCache;
use App\Caches\JobListingCache;
use App\Caches\JobPageCache;
use App\Caches\JobsCountCache;
use App\Caches\LatestJobsCache;
use App\Caches\MostViewedJobsCache;
use App\Caches\RelatedJobListingCache;
use App\Jobs\SubmitUrlToGoogleIndexing;
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

    public function updating(JobListing $jobListing): void {}

    public function created(JobListing $jobListing): void
    {
        $this->clearCache();
        // Only invalidate related jobs cache for the same category
        RelatedJobListingCache::invalidateForCategory($jobListing->job_category);

        // Dispatch Google indexing job (disabled via config)
        //$this->dispatchGoogleIndexingJob($jobListing, 'URL_UPDATED');
    }

    public function updated(JobListing $jobListing): void
    {
        $this->clearCache();
        // Only invalidate related jobs cache for the same category
        RelatedJobListingCache::invalidateForCategory($jobListing->job_category);

        // Dispatch Google indexing job (disabled via config)
        //$this->dispatchGoogleIndexingJob($jobListing, 'URL_UPDATED');
    }

    public function deleted(JobListing $jobListing): void
    {
        $this->clearCache();
        // Only invalidate related jobs cache for the same category
        RelatedJobListingCache::invalidateForCategory($jobListing->job_category);

        // Dispatch Google indexing job for deletion (disabled via config)
        //$this->dispatchGoogleIndexingJob($jobListing, 'URL_DELETED');
    }

    protected function clearCache(): void
    {
        Cache::forget('jobCategoriesJobsCount');
        Cache::forget('jobCategoriesAll');
        JobListingCache::invalidate();
        JobPageCache::invalidate();
        MostViewedJobsCache::invalidate();
        LatestJobsCache::invalidate();

        CountryAwareMostViewedJobsCache::invalidate();
        CountryAwareLatestJobsCache::invalidate();
        CountryAwareJobPageCache::invalidate();

        JobsCountCache::invalidateLastWeekAdded();
        JobsCountCache::invalidateTodayAdded();
        JobsCountCache::invalidateAvailableJobsCount();
        // Only invalidate related jobs cache for the same category, not all
        // RelatedJobListingCache::invalidate(); // Commented out to reduce aggressive cache clearing
        JobCategoryCache::invalidate();
        JobsCountCache::invalidateCategoriesCount();
        JobFilterCache::invalidate();
    }

    protected function dispatchGoogleIndexingJob(JobListing $jobListing, string $type): void
    {
        if (! config('services.google_indexing.enabled', false)) {
            return;
        }

        if (empty($jobListing->slug)) {
            return;
        }

        try {
            $url = route('job.show', $jobListing->slug);
            SubmitUrlToGoogleIndexing::dispatch($url, $type);
        } catch (\Exception $e) {
            \Log::warning('Failed to dispatch Google indexing job', [
                'job_listing_id' => $jobListing->id,
                'slug' => $jobListing->slug,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
