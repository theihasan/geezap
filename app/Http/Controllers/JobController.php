<?php

namespace App\Http\Controllers;

use App\Caches\JobListingCache;
use App\Caches\JobPageCache;
use App\Caches\JobViewsCache;
use App\Caches\RelatedJobListingCache;
use App\Services\MetaTagGenerator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $jobs = JobPageCache::get($request);

        $currentPage = $jobs->currentPage();

        return view('v2.job.index', [
            'jobs' => $jobs,
            'currentPage' => $currentPage
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
