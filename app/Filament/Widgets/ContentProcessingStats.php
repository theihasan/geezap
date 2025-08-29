<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Geezap\ContentFormatter\Models\Package;

class ContentProcessingStats extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $totalPackages = Package::count();
        $completedPackages = Package::completed()->count();
        $failedPackages = Package::failed()->count();
        $processingPackages = Package::processing()->count();
        $pendingPackages = Package::pending()->count();
        
        $todayPackages = Package::whereDate('created_at', today())->count();
        $weekPackages = Package::where('created_at', '>=', now()->subDays(7))->count();
        
        $successRate = $totalPackages > 0 ? round(($completedPackages / $totalPackages) * 100, 1) : 0;

        return [
            Stat::make('Total Processed', $totalPackages)
                ->description('All time submissions')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Success Rate', $successRate . '%')
                ->description('Completed vs Total')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($successRate >= 90 ? 'success' : ($successRate >= 70 ? 'warning' : 'danger')),

            Stat::make('This Week', $weekPackages)
                ->description('Last 7 days submissions')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Processing Queue', $processingPackages + $pendingPackages)
                ->description($pendingPackages . ' pending, ' . $processingPackages . ' processing')
                ->descriptionIcon('heroicon-m-clock')
                ->color($processingPackages + $pendingPackages > 0 ? 'warning' : 'success'),
        ];
    }
}