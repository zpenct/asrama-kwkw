<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BookingStatsOverview;
use App\Filament\Widgets\DailyRevenueChart;
use App\Filament\Widgets\Stats;
use Filament\Pages\Dashboard as FilamentDashboard;

class Dashboard extends FilamentDashboard
{
    protected static string $view = 'filament.pages.dashboard';

    protected static ?int $navigationSort = 1;

    protected function getHeaderWidgets(): array
    {
        return [
            Stats::class,
            BookingStatsOverview::class,
            DailyRevenueChart::class,
        ];
    }
}
