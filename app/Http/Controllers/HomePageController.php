<?php

namespace App\Http\Controllers;

use App\Caches\CountriesCache;
use App\Caches\CountryJobCountCache;
use App\Caches\JobCategoryCache;
use App\Caches\JobsCountCache;
use App\Caches\CountryAwareLatestJobsCache;
use App\Caches\CountryAwareMostViewedJobsCache;
use App\Caches\LatestJobsCache;
use App\Caches\MostViewedJobsCache;
use App\Services\SeoMetaService;
use App\Traits\DetectsUserCountry;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Log;

class HomePageController extends Controller
{
    use DetectsUserCountry;

    public function __invoke(SeoMetaService $seoService): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $userCountry = $this->getUserCountry();

            $mostViewedJobs = CountryAwareMostViewedJobsCache::get($userCountry);

            $latestJobs = CountryAwareLatestJobsCache::get(
                $mostViewedJobs->pluck('id')->toArray(),
                $userCountry
            );
        } catch (\Throwable $e) {
            Log::warning('Country-aware cache failed on homepage, falling back to default', [
                'error' => $e->getMessage(),
                'memory_usage' => memory_get_usage(true),
                'memory_peak' => memory_get_peak_usage(true)
            ]);
            
            $mostViewedJobs = MostViewedJobsCache::get();
            $latestJobs = LatestJobsCache::get($mostViewedJobs->pluck('id')->toArray());
            $userCountry = null;
        }

        $jobCategories = JobCategoryCache::getTopCategories();
        $todayAddedJobsCount = JobsCountCache::todayAdded();
        $lastWeekAddedJobsCount = JobsCountCache::lastWeekAdded();
        $jobCategoriesCount = JobsCountCache::categoriesCount();
        $availableJobs = JobsCountCache::availableJobsCount();
        $topCountries = CountryJobCountCache::getTopCountries(10);

        $meta = $seoService->generateHomePageMeta(
            availableJobs: $availableJobs,
            todayAddedJobsCount: $todayAddedJobsCount,
            jobCategoriesCount: $jobCategoriesCount,
            lastWeekAddedJobsCount: $lastWeekAddedJobsCount,
            jobCategories: $jobCategories
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
            'userCountry',
        ]));
    }
}
