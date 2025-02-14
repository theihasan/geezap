<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use App\Models\JobListing;
use App\Services\MetaTagGenerator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;

class HomePageController extends Controller
{
    public function __invoke(MetaTagGenerator $metaGenerator): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $mostViewedJobs = Cache::remember('mostViewedJobs', 60 * 24, function () {
            return JobListing::query()
                ->latest('views')
                ->limit(3)
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

        $latestJobs = Cache::remember('latestJobs', 60 * 24, function () use($mostViewedJobs) {
            return JobListing::latest()
                ->whereNotIn('id',$mostViewedJobs->pluck('id')->toArray())
                ->limit(3)
                ->get();
        });

        $meta = $metaGenerator->getHomePageMeta(
            $availableJobs,
            $todayAddedJobsCount,
            $jobCategoriesCount,
            $lastWeekAddedJobsCount,
            $jobCategories
        );



        return view('v2.index', compact([
            'todayAddedJobsCount',
            'lastWeekAddedJobsCount',
            'jobCategoriesCount',
            'mostViewedJobs',
            'jobCategories',
            'availableJobs',
            'latestJobs',
            'meta',
        ]));
    }
}
