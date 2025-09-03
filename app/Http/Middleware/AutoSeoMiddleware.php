<?php

namespace App\Http\Middleware;

use App\Services\SeoMetaService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AutoSeoMiddleware
{
    public function __construct(private SeoMetaService $seoService) {}

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Check if response is a view and doesn't already have meta
        if ($this->shouldInjectMeta($response)) {
            $meta = $this->seoService->generateMeta();
            $response->with('meta', $meta);
        }

        return $response;
    }

    private function shouldInjectMeta($response): bool
    {
        return $response instanceof View && 
               !$response->offsetExists('meta') &&
               !$this->isApiRoute() &&
               !$this->isAdminRoute();
    }

    private function isApiRoute(): bool
    {
        return request()->is('api/*');
    }

    private function isAdminRoute(): bool
    {
        return request()->is('admin/*') || 
               request()->is('geezap/*') ||
               request()->is('horizon/*');
    }
}