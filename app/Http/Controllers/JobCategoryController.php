<?php

namespace App\Http\Controllers;

use App\Caches\JobCategoryCache;
use App\Services\SeoMetaService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class JobCategoryController extends Controller
{
    public function __invoke(SeoMetaService $seoService): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $jobCategories = JobCategoryCache::get();
        $meta = $seoService->generateCategoriesMeta($jobCategories);

        return view('v2.job.categories', [
            'jobCategories' => $jobCategories,
            'meta' => $meta
        ]);
    }
}
