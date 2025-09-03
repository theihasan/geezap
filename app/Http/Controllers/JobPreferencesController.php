<?php

namespace App\Http\Controllers;

use App\Services\SeoMetaService;
use Illuminate\View\View;

class JobPreferencesController extends Controller
{
    public function __construct(private SeoMetaService $seoService) {}

    public function index(): View
    {
        $meta = $this->seoService->generateMeta(
            title: 'Job Preferences | Customize Your Experience',
            description: 'Set your job search preferences to find the perfect opportunities. Customize location, salary range, remote work options, and get personalized job recommendations.',
            keywords: 'job preferences, job search filters, personalized jobs, remote work preferences'
        );
        
        return view('v2.pages.job-preferences', compact('meta'));
    }
}