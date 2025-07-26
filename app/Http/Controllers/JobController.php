<?php

namespace App\Http\Controllers;

use App\Caches\JobListingCache;
use App\Caches\CountryAwareJobPageCache;
use App\Caches\JobViewsCache;
use App\Caches\RelatedJobListingCache;
use App\Services\MetaTagGenerator;
use App\Traits\DetectsUserCountry;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class JobController extends Controller
{
    use DetectsUserCountry;

    public function index(Request $request)
    {
        $userCountry = $this->getUserCountry();
        
        $jobs = CountryAwareJobPageCache::get($request, $userCountry);

        $currentPage = $jobs->currentPage();

        return view('v2.job.index', [
            'jobs' => $jobs,
            'currentPage' => $currentPage,
            'userCountry' => $userCountry, // Pass to view for UI indicators
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
