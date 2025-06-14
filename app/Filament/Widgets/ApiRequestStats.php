<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\ApiName;
use App\Models\ApiKey;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ApiRequestStats extends BaseWidget
{
    protected static ?string $pollingInterval = '3s';
    
    protected function getStats(): array
    {
        $totalRemaining = ApiKey::sum('request_remaining');
        
        $totalSent = ApiKey::sum('sent_request');
        
        $lowestRemainingKey = ApiKey::orderBy('request_remaining', 'asc')
            ->first();
            
        $lowestRemainingDescription = $lowestRemainingKey 
            ? "Lowest: {$lowestRemainingKey->api_name} ({$lowestRemainingKey->request_remaining})" 
            : 'No API keys found';
        
        return [
            Stat::make('Total Remaining Requests', number_format((int)$totalRemaining))
                ->description('Across all API keys')
                ->descriptionIcon('heroicon-m-information-circle')
                ->color('success'),
                
            Stat::make('Total Requests Sent', number_format((int)$totalSent))
                ->description('Across all API keys')
                ->descriptionIcon('heroicon-m-paper-airplane')
                ->color('warning'),
                
            Stat::make('API Key Status', $lowestRemainingDescription)
                ->description('Key with lowest remaining requests')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color($lowestRemainingKey && $lowestRemainingKey->request_remaining < 10 ? 'danger' : 'info'),
        ];
    }
}