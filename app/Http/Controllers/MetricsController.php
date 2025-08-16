<?php

namespace App\Http\Controllers;

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Illuminate\Http\Response;
use App\Services\MetricsService;

class MetricsController extends Controller
{
    public function __construct(
        private CollectorRegistry $registry,
        private MetricsService $metricsService
    ) {
    }

    public function index(): Response
    {
        // Collect fresh business metrics before rendering
        $this->metricsService->collectBusinessMetrics();
        
        $renderer = new RenderTextFormat();
        $result = $renderer->render($this->registry->getMetricFamilySamples());

        return response($result, 200, [
            'Content-Type' => RenderTextFormat::MIME_TYPE,
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }

    public function health(): Response
    {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'metrics_endpoint' => route('metrics')
        ]);
    }
}