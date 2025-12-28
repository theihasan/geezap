<?php declare(strict_types=1);

namespace App\Http\Controllers;

final class MetricsController
{

    public function __invoke()
    {
        $registry = app(\Prometheus\CollectorRegistry::class);
        $renderer = new \Prometheus\RenderTextFormat();
        $metrics = $registry->getMetricFamilySamples();
        $result = $renderer->render($metrics);

        return response($result, 200, ['Content-Type' => \Prometheus\RenderTextFormat::MIME_TYPE]);
    }

}
