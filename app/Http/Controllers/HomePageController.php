<?php

namespace App\Http\Controllers;

use App\Caches\CountriesCache;
use App\Caches\CountryJobCountCache;
use App\Caches\JobCategoryCache;
use App\Caches\JobsCountCache;
use App\Caches\CountryAwareLatestJobsCache;
use App\Caches\CountryAwareMostViewedJobsCache;
use App\Services\MetaTagGenerator;
use App\Traits\DetectsUserCountry;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class HomePageController extends Controller
{
    use DetectsUserCountry;

    public function __invoke(MetaTagGenerator $metaGenerator): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $userCountry = $this->getUserCountry();

        $mostViewedJobs = CountryAwareMostViewedJobsCache::get($userCountry);

        $jobCategories = JobCategoryCache::getTopCategories();

        $todayAddedJobsCount = JobsCountCache::todayAdded();

        $lastWeekAddedJobsCount = JobsCountCache::lastWeekAdded();

        $jobCategoriesCount = JobsCountCache::categoriesCount();

        $availableJobs = JobsCountCache::availableJobsCount();

        $latestJobs = CountryAwareLatestJobsCache::get(
            $mostViewedJobs->pluck('id')->toArray(),
            $userCountry
        );

        $topCountries = CountryJobCountCache::getTopCountries(10);

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
            'topCountries',
            'userCountry', // Pass user country to view for UI indicators
        ]));
    }
}
