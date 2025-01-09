<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Services\MetaTagGenerator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Cache;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = 'jobs_page_' . $request->get('page', 1) . '_' . md5(serialize($request->all()));
        $jobs = Cache::remember($cacheKey, 60 * 24, function () use ($request) {
            $jobsQuery = JobListing::query()
                ->with(['category']);

            $jobsQuery = app(Pipeline::class)
                ->send($jobsQuery)
                ->through([
                    \App\Pipelines\JobFilter::class,
                ])
                ->thenReturn();

            return $jobsQuery->latest()->paginate(1)->withQueryString();
        });

        $currentPage = $jobs->currentPage();
        return view('v2.job.index', [
            'jobs' => $jobs,
            'currentPage' => $currentPage
        ]);
    }

    public function job(MetaTagGenerator $metaTagGenerator, $slug): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $jobCacheKey = 'job_' . $slug;
        $relatedJobsCacheKey = 'related_jobs_' . $slug;
        $viewKey = $jobCacheKey . '_view_' . request()->ip() . '_' . now()->format('Y-m-d-H-i');

        $jobViews = Cache::remember($viewKey, 1, function () use ($slug) {
            Cache::forget('mostViewedJobs');
            return JobListing::query()
                ->where('slug', $slug)
                ->firstOrFail()
                ->increment('views', 20);
        });

        $job = Cache::remember($jobCacheKey, 60 * 24, function () use ($slug) {
            return JobListing::query()
                ->where('slug', $slug)
                ->firstOrFail();
        });

        $relatedJobs = Cache::remember($relatedJobsCacheKey, 60 * 24, function () use ($job) {
            return JobListing::query()
                ->where('job_category', $job->job_category)
                ->where('id', '!=', $job->id)
                ->inRandomOrder()
                ->limit(3)
                ->get();
        });

        return view('v2.job.details', [
            'job' => $job,
            'relatedJobs' => $relatedJobs,
            'meta' => $metaTagGenerator->getJobDetailsMeta($job)
        ]);
    }
}
