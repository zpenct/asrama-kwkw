<x-filament-panels::page>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($this->getHeaderWidgets() as $widget)
            @php
                $isFullWidth = in_array($widget::class, [
                    \App\Filament\Widgets\AnnualRevenueChart::class,
                ]);
            @endphp

            <div class="{{ $isFullWidth ? 'col-span-1 sm:col-span-2 lg:col-span-3' : 'col-span-1' }}">
                {{ $widget }}
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
