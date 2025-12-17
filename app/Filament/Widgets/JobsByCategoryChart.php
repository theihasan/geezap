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

            $startDate = now()->subWeek();
            $endDate = now();

            // Get all data in a single optimized query to prevent N+1
            $jobData = JobListing::selectRaw("
                    job_category,
                    date_format(created_at, '%Y-%u') as date_week,
                    count(*) as count
                ")
                ->whereIn('job_category', $topCategories->pluck('id'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy(['job_category', 'date_week'])
                ->orderBy('date_week')
                ->get()
                ->groupBy('job_category');

            // Get labels for the date range
            $labels = Trend::query(JobListing::query())
                ->between(
                    start: $startDate,
                    end: $endDate,
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

            // Build data array matching the trend format (moved outside the loop to prevent N+1)
            $trendData = Trend::query(JobListing::query())
                ->between(
                    start: $startDate,
                    end: $endDate,
                )
                ->perWeek()
                ->count();

            // Generate datasets for each category using the pre-fetched data
            foreach ($topCategories as $index => $category) {
                $categoryData = $jobData->get($category->id, collect());

                // Create a map of week -> count for this category
                $weekCounts = $categoryData->pluck('count', 'date_week');

                $data = $trendData->map(function (TrendValue $value) use ($weekCounts) {
                    $week = \Carbon\Carbon::parse($value->date)->format('Y-W');

                    return $weekCounts->get($week, 0);
                })->toArray();

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

        // Generate labels for the last 2 weeks
        for ($i = 1; $i >= 0; $i--) {
            $startOfWeek = now()->subWeeks($i)->startOfWeek();
            $labels[] = $startOfWeek->format('M j');
        }

        // Get all job data in a single optimized query
        $startDate = now()->subWeeks(1)->startOfWeek();
        $endDate = now()->endOfWeek();

        $jobData = JobListing::selectRaw('
                job_category,
                WEEK(created_at, 3) as week_num,
                YEAR(created_at) as year_num,
                count(*) as count
            ')
            ->whereIn('job_category', $topCategories->pluck('id'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(['job_category', 'week_num', 'year_num'])
            ->get()
            ->groupBy('job_category');

        // Generate data for each category using pre-fetched data
        foreach ($topCategories as $index => $category) {
            $categoryData = $jobData->get($category->id, collect());
            $data = [];

            for ($i = 1; $i >= 0; $i--) {
                $weekStart = now()->subWeeks($i)->startOfWeek();
                $weekNum = $weekStart->week;
                $yearNum = $weekStart->year;

                $weekData = $categoryData->where('week_num', $weekNum)
                    ->where('year_num', $yearNum)
                    ->first();

                $data[] = $weekData ? (int) $weekData->count : 0;
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
