<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Prometheus\CollectorRegistry;

class PrometheusMiddleware
{
    private $registry;
    private $counter;

    /**
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    public function __construct(CollectorRegistry $registry)
    {
        $this->registry = $registry;
        $this->counter = $this->registry->getOrRegisterCounter(
            'geezap',
            'http_requests_total',
            'Total number of HTTP requests',
            ['method', 'path', 'status']
        );
    }
    public function handle(Request $request, Closure $next)
    {

        $response = $next($request);
        $this->counter->inc([
            'method' => $request->method(),
            'path' => $request->path(),
            'status' => $response->getStatusCode(),
        ]);

        return $response;
    }
}
