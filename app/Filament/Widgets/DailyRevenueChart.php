<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DailyRevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pendapatan Harian';

    protected function getData(): array
    {
        $data = Transaction::selectRaw('DATE(paid_at) as date, SUM(amount) as total')
            ->where('status', 'paid')
            ->whereBetween('paid_at', [now()->subDays(6)->startOfDay(), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $totals = [];

        foreach ($data as $row) {
            $labels[] = Carbon::parse($row->date)->format('d M');
            $totals[] = $row->total;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $totals,
                    'backgroundColor' => '#3b82f6',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
