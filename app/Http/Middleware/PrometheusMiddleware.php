<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Prometheus\CollectorRegistry;
use Symfony\Component\HttpFoundation\Response;

class PrometheusMiddleware
{
    private $counter;
    private $durationGauge;
    private $activeRequestsGauge;

    public function __construct(private CollectorRegistry $registry)
    {
        $this->counter = $this->registry->getOrRegisterCounter(
            'geezap',
            'http_requests_total',
            'Total number of HTTP requests',
            ['method', 'path', 'status']
        );

        $this->durationGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'http_requests_duration_seconds',
            'Duration of HTTP requests',
            ['method', 'path', 'status']
        );

        $this->activeRequestsGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'http_active_requests',
            'Number of active HTTP requests',
            ['method', 'path']
        );
        
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        $labels = [
            'method' => $request->method(),
            'path' => $request->path(),
        ];

        $this->activeRequestsGauge->inc($labels);
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $duration = $endTime - $startTime;
        
        $labelsWithStatus = array_merge($labels, [
            'status' => $response->getStatusCode()
        ]);


        $this->counter->inc($labelsWithStatus);
        $this->durationGauge->set($duration, $labelsWithStatus);

        $this->activeRequestsGauge->dec($labels);

        return $response;
    }
}
