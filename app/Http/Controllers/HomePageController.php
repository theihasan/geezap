<?php

namespace App\Http\Controllers;

use App\Caches\JobCategoryCache;
use App\Caches\JobsCountCache;
use App\Caches\LatestJobsCache;
use App\Caches\MostViewedJobsCache;
use App\Services\MetaTagGenerator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class HomePageController extends Controller
{
    public function __invoke(MetaTagGenerator $metaGenerator): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {

        $mostViewedJobs = MostViewedJobsCache::get();

        $jobCategories = JobCategoryCache::getTopCategories();

        $todayAddedJobsCount = JobsCountCache::todayAdded();

        $lastWeekAddedJobsCount = JobsCountCache::lastWeekAdded();

        $jobCategoriesCount = JobsCountCache::categoriesCount();

        $availableJobs = JobsCountCache::availableJobsCount();

        $latestJobs = LatestJobsCache::get($mostViewedJobs->pluck('id')->toArray());

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
