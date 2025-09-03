<?php

namespace App\Http\Controllers;

use App\Services\SeoMetaService;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function __construct(private SeoMetaService $seoService) {}

    public function index(): View
    {
        $meta = $this->seoService->generateMeta();
        
        return view('v2.profile.my-application', compact('meta'));
    }
}