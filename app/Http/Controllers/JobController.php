<?php
namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Cache;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = 'jobs_page_' . $request->get('page', 1) . '_' . md5(serialize($request->all()));
        $jobs = Cache::remember($cacheKey, 60 * 24, function () use ($request) {
            $jobsQuery = JobListing::query();

            $jobsQuery = app(Pipeline::class)
                ->send($jobsQuery)
                ->through([
                    \App\Pipelines\JobFilter::class,
                ])
                ->thenReturn();

            return $jobsQuery->paginate(10);
        });

        $currentPage = $jobs->currentPage();
        return view('job.index', [
            'jobs' => $jobs,
            'currentPage' => $currentPage
        ]);
    }

    public function job($slug)
    {
        $jobCacheKey = 'job_' . $slug;
        $relatedJobsCacheKey = 'related_jobs_' . $slug;

        $job = Cache::remember($jobCacheKey, 60 * 24, function () use ($slug) {
            return JobListing::where('slug', $slug)->firstOrFail();
        });

        $relatedJobs = Cache::remember($relatedJobsCacheKey, 60 * 24, function () use ($job) {
            return JobListing::where('job_category', $job->job_category)
                ->where('id', '!=', $job->id)
                ->inRandomOrder()
                ->limit(3)
                ->get();
        });

        return view('job.details', [
            'job' => $job,
            'relatedJobs' => $relatedJobs
        ]);
    }
}
