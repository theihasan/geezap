<?php

namespace App\Http\Controllers;

use App\Caches\JobCategoryCache;
use App\Services\MetaTagGenerator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class JobCategoryController extends Controller
{
    public function __invoke(MetaTagGenerator $metaTagGenerator): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $jobCategories = JobCategoryCache::get();

        return view('v2.job.categories', [
            'jobCategories' => $jobCategories,
            'meta' => $metaTagGenerator->getCategoriesMeta($jobCategories)
        ]);
    }
}
