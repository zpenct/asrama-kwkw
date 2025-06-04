<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-4">
        @foreach ($this->getHeaderWidgets() as $widget)
            @if ($widget::class === \App\Filament\Widgets\AnnualRevenueChart::class)
                <div class="col-span-1 sm:col-span-1 lg:col-span-3">
                    {{ $widget }}
                </div>
            @else
                <div class="col-span-1 sm:col-span-1 lg:col-span-1">
                    {{ $widget }}
                </div>
            @endif
        @endforeach
    </div>
</x-filament-panels::page>
