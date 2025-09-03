<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Services\SeoMetaService;

class ContactPageController extends Controller
{
    public function __invoke(SeoMetaService $seoService)
    {
        $meta = $seoService->generateMeta();

        return view("v2.pages.contact", [
            "meta" => $meta
        ]);
    }
}
