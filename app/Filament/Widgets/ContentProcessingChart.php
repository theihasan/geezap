<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Geezap\ContentFormatter\Models\Package;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ContentProcessingChart extends ChartWidget
{
    protected static ?string $heading = 'Content Processing Overview';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        try {
            // Get processing stats for the last 30 days
            $data = Trend::model(Package::class)
                ->between(
                    start: now()->subDays(30),
                    end: now(),
                )
                ->perDay()
                ->count();

            return [
                'datasets' => [
                    [
                        'label' => 'Daily Submissions',
                        'data' => $data->map(fn (TrendValue $value) => $value->aggregate)->toArray(),
                        'borderColor' => '#3b82f6',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'fill' => true,
                    ],
                ],
                'labels' => $data->map(function (TrendValue $value) {
                    // Handle both string dates and Carbon instances safely
                    try {
                        if (is_string($value->date)) {
                            return \Carbon\Carbon::parse($value->date)->format('M j');
                        }
                        return $value->date->format('M j');
                    } catch (\Exception $e) {
                        return 'N/A';
                    }
                })->toArray(),
            ];
        } catch (\Exception $e) {
            // Fallback to manual data aggregation if Trend fails
            return $this->getFallbackData();
        }
    }

    private function getFallbackData(): array
    {
        $labels = [];
        $data = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M j');
            $data[] = Package::whereDate('created_at', $date)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Daily Submissions',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}