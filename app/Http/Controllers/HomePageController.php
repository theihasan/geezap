<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomePageController extends Controller
{
    public function __invoke()
    {
        $latestJobs = Cache::remember('latestJobs', 60 * 24, function () {
            return JobListing::orderBy('posted_at', 'desc')->limit(10)->get();
        });

        $jobCategories = Cache::remember('jobCategories', 24 * 60, function () {
            return JobListing::selectRaw('job_category, country, (SELECT employer_logo FROM job_listings WHERE job_category = j.job_category LIMIT 1) as employer_logo, category_image, COUNT(*) as total_jobs')
                ->from('job_listings as j')
                ->groupBy('job_category', 'country', 'category_image')
                ->orderBy('job_category', 'asc')
                ->orderBy('country', 'asc')
                ->take(8)
                ->get();
        });

        $todayAddedJobsCount = Cache::remember('todayAddedJobsCount', 24 * 60, function () {
            return JobListing::whereDate('created_at', today())->count();
        });

        $lastWeekAddedJobsCount = Cache::remember('lastWeekAddedJobsCount', 24 * 60, function () {
            return JobListing::whereBetween('created_at', [now()->subWeek(), now()])->count();
        });

        $jobCategoriesCount = Cache::remember('jobCategoriesCount', 24 * 60, function () {
            return JobListing::distinct()->count('job_category');
        });
        $availableJobs = Cache::remember('availableJobs', 24 * 60, function () {
            return JobListing::count();
        });

        return view('v2.index', [
            'todayAddedJobsCount' => $todayAddedJobsCount,
            'lastWeekAddedJobsCount' =>  $lastWeekAddedJobsCount,
            'jobCategoriesCount' => $jobCategoriesCount,
            'latestJobs' => $latestJobs,
            'jobCategories' => $jobCategories,
            'availableJobs' => $availableJobs,
        ]);
    }
}
