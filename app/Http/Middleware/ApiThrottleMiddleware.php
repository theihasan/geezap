<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ApiThrottleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'api:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 30)) {
            return response()->json([
                'success' => false,
                'message' => 'API rate limit exceeded. Please try again later.',
                'retry_after' => RateLimiter::availableIn($key),
            ], 429, [
                'Retry-After' => RateLimiter::availableIn($key),
                'X-RateLimit-Limit' => 30,
                'X-RateLimit-Remaining' => 0,
                'X-RateLimit-Reset' => now()->addSeconds(RateLimiter::availableIn($key))->timestamp,
            ]);
        }

        RateLimiter::hit($key, 60); // 60 seconds = 1 minute

        $response = $next($request);

        $remaining = RateLimiter::remaining($key, 30);
        $response->headers->set('X-RateLimit-Limit', 30);
        $response->headers->set('X-RateLimit-Remaining', $remaining);
        $response->headers->set('X-RateLimit-Reset', now()->addSeconds(RateLimiter::availableIn($key))->timestamp);

        return $response;
    }
}
