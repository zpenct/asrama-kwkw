<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TodayBookingStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();

        return [
            Stat::make('Pending Booking Today', Booking::whereDate('created_at', $today)->where('status', 'pending')->count())
                ->description('Booking belum dikonfirmasi')
                ->icon('heroicon-o-clock')
                ->color('gray'),

            Stat::make('Transaction Created Today', Transaction::whereDate('created_at', $today)->count())
                ->description('Transaksi yang baru dibuat')
                ->icon('heroicon-o-document')
                ->color('gray'),
        ];
    }

    protected function getColumns(): int
    {
        return 2;
    }
}
