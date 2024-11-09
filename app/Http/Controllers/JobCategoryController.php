<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class JobCategoryController extends Controller
{
    public function __invoke(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $jobCategories = Cache::remember('jobCategoriesAll', 60 * 24, function () {
            return JobListing::selectRaw('job_category, country, (SELECT employer_logo FROM job_listings WHERE job_category = j.job_category LIMIT 1) as employer_logo, category_image, COUNT(*) as total_jobs')
                ->from('job_listings as j')
                ->groupBy('job_category', 'country', 'category_image')
                ->orderBy('job_category', 'asc')
                ->orderBy('country', 'asc')
                ->get();
        });

        return view('v2.job.categories', ['jobCategories' => $jobCategories]);
    }
}
