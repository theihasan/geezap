<?php

namespace App\Filament\Widgets;

use App\Models\ApiKey;
use App\Models\JobCategory;
use App\Models\JobListing;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class TotalUser extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', \App\Models\User::count()),
            Stat::make('Total Jobs', \App\Models\JobListing::count()),
            Stat::make('Total API KEY', ApiKey::count() ),
            Stat::make('Total Category', JobCategory::count()),
        ];
    }
}
