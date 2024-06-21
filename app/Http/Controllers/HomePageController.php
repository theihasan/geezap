<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function __invoke()
    {
        $latestJobs = JobListing::orderBy('posted_at', 'desc')->limit(10)->get();
        $jobCategories = JobListing::selectRaw('job_category, country, (SELECT employer_logo FROM job_listings WHERE job_category = j.job_category LIMIT 1) as employer_logo, category_image, COUNT(*) as total_jobs')
            ->from('job_listings as j')
            ->groupBy('job_category', 'country', 'category_image')
            ->orderBy('job_category', 'asc')
            ->orderBy('country', 'asc')
            ->take(8)
            ->get();

        $todayAddedJobsCount = JobListing::whereDate('created_at', today())->count();

        $jobCategoriesJobsCount = JobListing::count();

        $jobCategoriesCount = JobListing::distinct()->count('job_category');

        return view('index', [
            'todayAddedJobsCount' => $todayAddedJobsCount,
            'jobCategoriesJobsCount' => $jobCategoriesJobsCount,
            'jobCategoriesCount' => $jobCategoriesCount,
            'latestJobs' => $latestJobs,
            'jobCategories' => $jobCategories
        ]);
    }
}
