<?php

namespace App\Http\Controllers;

use App\Services\SeoMetaService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function viewWithMeta(string $view, array $data = [], ?SeoMetaService $seoService = null): View
    {
        if (!isset($data['meta'])) {
            $seoService = $seoService ?? app(SeoMetaService::class);
            $data['meta'] = $seoService->generateMeta();
        }
        
        return view($view, $data);
    }

    protected function viewWithCustomMeta(
        string $view, 
        array $data = [], 
        ?string $title = null,
        ?string $description = null,
        ?string $keywords = null,
        ?string $image = null
    ): View {
        $seoService = app(SeoMetaService::class);
        $data['meta'] = $seoService->generateMeta($title, $description, $keywords, $image);
        
        return view($view, $data);
    }
}
