<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return $next($request);
        }
        
        if ($user->onboarding_completed_at) {
            return $next($request);
        }
        
        $path = $request->getPathInfo();
        
        // Allow onboarding routes
        if (str_starts_with($path, '/onboarding/') || $request->routeIs('onboarding.*')) {
            return $next($request);
        }
        
        // Allow auth routes
        $authPaths = ['/login', '/register', '/forgot-password', '/reset-password', '/verify-email'];
        foreach ($authPaths as $authPath) {
            if (str_starts_with($path, $authPath)) {
                return $next($request);
            }
        }
        
        // Allow API routes
        if (str_starts_with($path, '/api/') || $request->routeIs(['api.*', 'logout', 'health', 'metrics'])) {
            return $next($request);
        }
        
        // For incomplete users accessing other routes, redirect to onboarding
        return redirect()->route('onboarding.welcome');
    }
}
