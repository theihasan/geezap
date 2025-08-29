<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Geezap\ContentFormatter\Models\Package;

class ProcessingStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Processing Status Breakdown';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $completed = Package::completed()->count();
        $failed = Package::failed()->count();
        $processing = Package::processing()->count();
        $pending = Package::pending()->count();

        return [
            'datasets' => [
                [
                    'label' => 'Status Distribution',
                    'data' => [$completed, $failed, $processing, $pending],
                    'backgroundColor' => [
                        '#10b981', // success/green
                        '#ef4444', // danger/red
                        '#3b82f6', // primary/blue
                        '#f59e0b', // warning/yellow
                    ],
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => ['Completed', 'Failed', 'Processing', 'Pending'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => false,
            'responsive' => true,
        ];
    }
}