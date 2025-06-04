<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AnnualRevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pendapatan Tahunan';
    protected static ?int $sort = 2;

    public ?string $yearFilter = null;

    protected function getData(): array
    {
        $selectedYear = $this->yearFilter ?? now()->year;

        $data = Transaction::selectRaw('MONTH(paid_at) as month, SUM(amount) as total')
            ->where('status', 'paid')
            ->whereYear('paid_at', $selectedYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $totals = [];

        // Init all months so chart doesn't skip empty ones
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::create()->month($i)->format('F');
            $totals[] = $data->firstWhere('month', $i)?->total ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => "Pendapatan $selectedYear (Rp)",
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

    protected function getFilters(): ?array
    {
        $years = Transaction::selectRaw('DISTINCT YEAR(paid_at) as year')
            ->orderByDesc('year')
            ->pluck('year', 'year')
            ->toArray();

        return $years;
    }

    protected function getFilterFormSchema(): array
    {
        return [
            \Filament\Forms\Components\Select::make('yearFilter')
                ->label('Pilih Tahun')
                ->options($this->getFilters())
                ->default(now()->year)
                ->reactive()
                ->afterStateUpdated(fn () => $this->updateChartData()),
        ];
    }
}