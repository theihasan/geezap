<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Prometheus\CollectorRegistry;
use Symfony\Component\HttpFoundation\Response;

class PrometheusMiddleware
{
    private $counter;

    public function __construct(private CollectorRegistry $registry)
    {
        $this->counter = $this->registry->getOrRegisterCounter(
            'geezap',
            'http_requests_total',
            'Total number of HTTP requests',
            ['method', 'path', 'status']
        );
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $this->counter->inc([
            'method' => $request->method(),
            'path' => $request->path(),
            'status' => $response->getStatusCode()
        ]);

        return $response;
    }
}
