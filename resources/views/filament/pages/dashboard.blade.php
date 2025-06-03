<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($this->getHeaderWidgets() as $widget)
            {{ $widget }}
        @endforeach
    </div>
</x-filament-panels::page>
