<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
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
        $jobCategories = JobCategory::getAllCategories();

        return view('v2.job.categories', ['jobCategories' => $jobCategories]);
    }
}
