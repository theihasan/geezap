<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Services\SeoMetaService;

class PrivacyPolicyPageController extends Controller
{
    public function __invoke(SeoMetaService $seoService)
    {
        $meta = $seoService->generateMeta();

        return view('v2.pages.privacy-policy', [
            'meta' => $meta
        ]);
    }
}
