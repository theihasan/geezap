<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Prometheus\CollectorRegistry;
use Symfony\Component\HttpFoundation\Response;

class PrometheusMiddleware
{
    private $counter;
    private $durationGauge;
    private $activeRequestsGauge;
    private $memoryUsageBytesGauge;

    public function __construct(private CollectorRegistry $registry)
    {
        $this->initializeMetrics();
    }

    private function initializeMetrics(): void
    {
        $this->initializeCounterMetrics();
        $this->initializeDurationGaugeMetrics();
        $this->initializeActiveRequestsGaugeMetrics();
        $this->initializeMemoryUsageBytesGaugeMetrics();
    }

    private function initializeCounterMetrics(): void
    {
        $this->counter = $this->registry->getOrRegisterCounter(
            'geezap',
            'http_requests_total',
            'Total number of HTTP requests',
            ['method', 'path', 'status']
        );
    }

    private function initializeDurationGaugeMetrics(): void
    {
        $this->durationGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'http_requests_duration_seconds',
            'Duration of HTTP requests',
            ['method', 'path', 'status']
        );
    }

    private function initializeActiveRequestsGaugeMetrics(): void
    {
        $this->activeRequestsGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'http_active_requests',
            'Number of active HTTP requests',
            ['method', 'path']
        );
    }

    private function initializeMemoryUsageBytesGaugeMetrics(): void
    {
        $this->memoryUsageBytesGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'memory_usage_bytes',
            'Memory usage in bytes',
            ['type']
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
        $this->memoryUsageBytesGauge->set(memory_get_usage(true), ['real']);
        $this->memoryUsageBytesGauge->set(memory_get_peak_usage(true), ['peak']);
        $this->memoryUsageBytesGauge->set(memory_get_usage(false), ['allocated']);

        return $response;
    }
}
