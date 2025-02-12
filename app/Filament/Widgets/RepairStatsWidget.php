<?php

namespace App\Filament\Widgets;

use App\Models\Repair;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class RepairStatsWidget extends BaseWidget
{
    protected function getCards(): array
    {
        if(!Auth::user()->is_admin) {
            return [];
        }
        return [
            Stat::make('Total Repairs', Repair::count()),
            Stat::make('Pending Repairs', Repair::whereNull('invoice_path')->count()),
            Stat::make('Total Earnings', '€' . Repair::sum('cost')),
        ];
    }
}
