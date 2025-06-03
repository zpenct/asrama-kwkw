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
        $startMonth = $today->copy()->startOfMonth();

        return [
            Stat::make('Total Booking Bulan Ini', Booking::whereBetween('created_at', [$startMonth, $today])->count())
                ->description('Booking masuk sejak awal bulan')
                ->color('primary')
                ->icon('heroicon-o-calendar'),

            Stat::make('Total Transaksi Berhasil Bulan Ini', Transaction::where('status', 'paid')->whereBetween('paid_at', [$startMonth, $today])->count())
                ->description('Transaksi yang sudah dibayar')
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make('Total Pendapatan Bulan Ini', number_format(Transaction::where('status', 'paid')->whereBetween('paid_at', [$startMonth, $today])->sum('amount')))
                ->description('Akumulasi nilai transaksi')
                ->color('warning')
                ->icon('heroicon-o-currency-dollar'),
        ];
    }
}
