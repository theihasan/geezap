<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use App\Models\JobListing;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;

class HomePageController extends Controller
{
    public function __invoke(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $latestJobs = Cache::remember('mostViewedJobs', 60 * 24, function () {
            return JobListing::query()
                ->latest('views')
                ->limit(10)
                ->get();
        });

        $jobCategories = JobCategory::getTopCategories();

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
