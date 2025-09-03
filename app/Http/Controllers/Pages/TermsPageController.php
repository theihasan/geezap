<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Services\SeoMetaService;

class TermsPageController extends Controller
{
    public function __invoke(SeoMetaService $seoService)
    {
        $meta = $seoService->generateMeta();

        return view('v2.pages.terms-condition', [
            'meta' => $meta
        ]);
    }
}
