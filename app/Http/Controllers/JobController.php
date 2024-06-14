<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $currentPage = $request->query('page', 1);

        $jobs = JobListing::cursorPaginate(10);

        return view('job.index', [
            'jobs' => $jobs,
            'currentPage' => $currentPage
        ]);

    }

    public function job($slug)
    {
        $job = JobListing::where('slug', $slug)->firstOrFail();
        $relatedJobs = JobListing::where('job_category', $job->job_category)
            ->where('id', '!=', $job->id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('job.details', [
            'job' => $job,
            'relatedJobs' => $relatedJobs
        ]);
    }
}
