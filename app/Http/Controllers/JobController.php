<?php

namespace App\Http\Controllers;

use App\Caches\JobListingCache;
use App\Caches\CountryAwareJobPageCache;
use App\Caches\JobPageCache;
use App\Caches\JobViewsCache;
use App\Caches\RelatedJobListingCache;
use App\Services\MetaTagGenerator;
use App\Traits\DetectsUserCountry;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobController extends Controller
{
    use DetectsUserCountry;

    public function index(Request $request)
    {
        try {
            $userCountry = $this->getUserCountry();
            
            $jobs = CountryAwareJobPageCache::get($request, $userCountry);
        } catch (\Throwable $e) {
            Log::warning('Country-aware cache failed, falling back to default', [
                'error' => $e->getMessage(),
                'memory_usage' => memory_get_usage(true),
                'memory_peak' => memory_get_peak_usage(true)
            ]);
            
            $jobs = JobPageCache::get($request);
            $userCountry = null;
        }

        $currentPage = $jobs->currentPage();

        return view('v2.job.index', [
            'jobs' => $jobs,
            'currentPage' => $currentPage,
            'userCountry' => $userCountry,
        ]);
    }

    public function job(MetaTagGenerator $metaTagGenerator, $slug): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $jobViews = JobViewsCache::get($slug, request()->ip());

        $job = JobListingCache::get($slug);

        $relatedJobs = RelatedJobListingCache::get($slug, $job);

        return view('v2.job.details', [
            'job' => $job,
            'relatedJobs' => $relatedJobs,
            'meta' => $metaTagGenerator->getJobDetailsMeta($job)
        ]);
    }
}
