<?php
namespace App\Observers;

use App\Caches\JobCategoryCache;
use App\Caches\JobFilterCache;
use App\Caches\JobListingCache;
use App\Caches\JobPageCache;
use App\Caches\JobsCountCache;
use App\Caches\LatestJobsCache;
use App\Caches\MostViewedJobsCache;
use App\Caches\CountryAwareLatestJobsCache;
use App\Caches\CountryAwareMostViewedJobsCache;
use App\Caches\CountryAwareJobPageCache;
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

    public function updating(JobListing $jobListing): void
    {

    }

    public function created(JobListing $jobListing): void
    {
        $this->clearCache();
        //$this->submitToGoogleIndexing($jobListing, 'URL_UPDATED');
    }
    
    public function updated(JobListing $jobListing): void
    {
        $this->clearCache();
        //$this->submitToGoogleIndexing($jobListing, 'URL_UPDATED');
    }

    public function deleted(JobListing $jobListing): void
    {
        $this->clearCache();
        //$this->submitToGoogleIndexing($jobListing, 'URL_DELETED');
    }

    private function submitToGoogleIndexing(JobListing $jobListing, string $type): void
    {
        if (!config('services.google_indexing.enabled')) {
            return;
        }

        try {
            $url = route('job.show', $jobListing->slug);
            SubmitUrlToGoogleIndexing::dispatch($url, $type);
        } catch (\Exception $e) {
            \Log::warning('Failed to dispatch Google Indexing job', [
                'job_id' => $jobListing->id,
                'slug' => $jobListing->slug,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
        }
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
        RelatedJobListingCache::invalidate();
        JobCategoryCache::invalidate();
        JobsCountCache::invalidateCategoriesCount();
        JobFilterCache::invalidate();
    }
}
