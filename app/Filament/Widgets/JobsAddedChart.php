<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\JobListing;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class JobsAddedChart extends ChartWidget
{
    protected static ?string $heading = 'Daily Job Counts';
    
    protected static string $color = 'success';
    
    protected static ?string $pollingInterval = '3s';
    
    protected static ?string $maxHeight = '300px';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 999;
    
    public ?string $filter = '7d';
    
    protected function getFilters(): ?array
    {
        return [
            '24h' => 'Last 24 hours',
            '7d' => 'Last 7 days',
            '14d' => 'Last 14 days',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        
        $startDate = match ($activeFilter) {
            '24h' => now()->subDay(),
            '7d' => now()->subDays(7),
            '14d' => now()->subDays(14),
            default => now()->subDays(7),
        };
        
        $interval = match ($activeFilter) {
            '24h' => 'perHour',
            '7d', '14d' => 'perDay',
            default => 'perDay',
        };
        
        $data = Trend::model(JobListing::class)
            ->between(
                start: $startDate,
                end: now(),
            )
            ->$interval()
            ->count();
            
        return [
            'datasets' => [
                [
                    'label' => 'Jobs Added',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#059669',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => match ($activeFilter) {
                '24h' => Carbon::parse($value->date)->format('H:i'),
                '7d', '14d' => Carbon::parse($value->date)->format('M d'),
                default => $value->date,
            }),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}