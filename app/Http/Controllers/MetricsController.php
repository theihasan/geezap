<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;

final class MetricsController
{
    public function __invoke(): Response
    {
        try {
            $registry = app(CollectorRegistry::class);
            $renderer = new RenderTextFormat;
            $metrics = $registry->getMetricFamilySamples();
            $result = $renderer->render($metrics);

            return response($result, 200, ['Content-Type' => RenderTextFormat::MIME_TYPE]);
        } catch (\Exception $e) {
            report($e);
            return response(
                "# Redis connection error - no metrics available\n",
                503,
                ['Content-Type' => RenderTextFormat::MIME_TYPE]
            );
        }
    }
}
