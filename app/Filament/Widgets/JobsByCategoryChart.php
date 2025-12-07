<?php

namespace App\Filament\Widgets;

use App\Models\JobCategory;
use App\Models\JobListing;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class JobsByCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Jobs by Category (Per Week)';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        try {
            $topCategories = JobCategory::withCount('jobs')
                ->orderByDesc('jobs_count')
                ->get();

            $datasets = [];
            $colors = [
                '#3b82f6', '#ef4444', '#10b981', '#f59e0b',
                '#8b5cf6', '#06b6d4', '#ec4899', '#84cc16',
            ];

            // Generate data for each category over the last 8 weeks
            foreach ($topCategories as $index => $category) {
                $data = Trend::query(
                    JobListing::where('job_category', $category->id)
                )
                    ->between(
                        start: now()->subWeek(),
                        end: now(),
                    )
                    ->perWeek()
                    ->count();

                $datasets[] = [
                    'label' => $category->name,
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate)->toArray(),
                    'borderColor' => $colors[$index % count($colors)],
                    'backgroundColor' => $colors[$index % count($colors)].'20',
                    'tension' => 0.3,
                ];
            }

            // Get labels for the last 8 weeks
            $labels = Trend::query(JobListing::class)
                ->between(
                    start: now()->subWeek(),
                    end: now(),
                )
                ->perWeek()
                ->count()
                ->map(function (TrendValue $value) {
                    try {
                        if (is_string($value->date)) {
                            return \Carbon\Carbon::parse($value->date)->format('M j');
                        }

                        return $value->date->format('M j');
                    } catch (\Exception $e) {
                        return 'N/A';
                    }
                })->toArray();

            return [
                'datasets' => $datasets,
                'labels' => $labels,
            ];
        } catch (\Exception $e) {
            return $this->getFallbackData();
        }
    }

    private function getFallbackData(): array
    {
        // Get top 8 categories by job count
        $topCategories = JobCategory::withCount('jobs')
            ->orderByDesc('jobs_count')
            ->get();

        $labels = [];
        $datasets = [];
        $colors = [
            '#3b82f6', '#ef4444', '#10b981', '#f59e0b',
            '#8b5cf6', '#06b6d4', '#ec4899', '#84cc16',
        ];

        // Generate labels for the last 8 weeks
        for ($i = 1; $i >= 0; $i--) {
            $startOfWeek = now()->subWeeks($i)->startOfWeek();
            $labels[] = $startOfWeek->format('M j');
        }

        // Generate data for each category
        foreach ($topCategories as $index => $category) {
            $data = [];

            for ($i = 1; $i >= 0; $i--) {
                $startOfWeek = now()->subWeeks($i)->startOfWeek();
                $endOfWeek = now()->subWeeks($i)->endOfWeek();

                $count = JobListing::where('job_category', $category->id)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->count();

                $data[] = $count;
            }

            $datasets[] = [
                'label' => $category->name,
                'data' => $data,
                'borderColor' => $colors[$index % count($colors)],
                'backgroundColor' => $colors[$index % count($colors)].'20',
                'tension' => 0.3,
            ];
        }

        return [
            'datasets' => $datasets,
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
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
            'interaction' => [
                'intersect' => false,
            ],
            'maintainAspectRatio' => false,
            'responsive' => true,
        ];
    }
}
