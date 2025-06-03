<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Stats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count()),
            Stat::make('Pending Booking Today', Booking::whereDate('created_at', today())->count()),
            Stat::make('Transaction Created Today', Transaction::whereDate('created_at', today())->count()),
            // Stat::make('Unique views', '192.1k')
            //     ->description('32k increase')
            //     ->descriptionIcon('heroicon-m-arrow-trending-up')
            //     ->chart([7, 2, 10, 3, 15, 4, 17])
            //     ->color('success'),
        ];
    }
}
