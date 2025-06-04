<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class BookingStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $startYear = $today->copy()->startOfYear();

        return [
            Stat::make('Total Booking Tahun Ini', Booking::whereBetween('created_at', [$startYear, $today])->count())
                ->description('Booking masuk selama tahun ini')
                ->icon('heroicon-o-calendar')
                ->color('primary'),

            Stat::make('Transaksi Diterima Tahun Ini', Transaction::where('status', 'paid')->whereBetween('created_at', [$startYear, $today])->count())
                ->description('Transaksi yang sudah divalidasi admin')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Total Pendapatan Tahun Ini', 'Rp '.number_format(
                Transaction::where('status', 'paid')
                    ->whereBetween('created_at', [$startYear, $today])
                    ->sum('amount'),
                0,
                ',',
                '.'
            ))
                ->description('Akumulasi nilai transaksi valid')
                ->icon('heroicon-o-currency-dollar')
                ->color('warning'),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }
}
