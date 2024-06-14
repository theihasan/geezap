<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class JobController extends Controller
{
    public function index(Request $request)
    {

        $jobs = JobListing::query();

        // Use Pipeline to apply filters
        $jobs = app(Pipeline::class)
            ->send($jobs)
            ->through([
                \App\Pipelines\JobFilter::class,
            ])
            ->thenReturn();

        $jobs = $jobs->paginate(10);
        $currentPage = $jobs->currentPage();
        return view('job.index', [
            'jobs' => $jobs,
            'currentPage' => $currentPage
        ]);

    }

    public function job($slug)
    {
        $job = JobListing::where('slug', $slug)->firstOrFail();

        return view('job.details', [
            'job' => $job
        ]);
    }
}
