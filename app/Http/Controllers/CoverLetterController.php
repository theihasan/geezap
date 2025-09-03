<?php

namespace App\Http\Controllers;

use App\Services\SeoMetaService;
use Illuminate\View\View;

class CoverLetterController extends Controller
{
    public function __construct(private SeoMetaService $seoService) {}

    public function update(): View
    {
        $meta = $this->seoService->generateMeta();
        
        return view('v2.cover-letter.update', compact('meta'));
    }
}