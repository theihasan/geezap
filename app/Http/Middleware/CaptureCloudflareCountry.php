<?php

namespace App\Http\Middleware;

use App\Jobs\UpdateUserCountryFromCloudflare;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Symfony\Component\HttpFoundation\Response;

class CaptureCloudflareCountry
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && request()->route()->getName() === 'home') {
            UpdateUserCountryFromCloudflare::dispatch(
                userId: auth()->id(),
                cfCountry: $request->header('CF-IPCountry')
            );
        }

        return $next($request);
    }
}
