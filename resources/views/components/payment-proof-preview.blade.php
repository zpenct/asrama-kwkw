@php
    $record = $getRecord();
@endphp

@if ($record?->payment_proof)
    <img src="{{ Storage::disk('s3')->url($record->payment_proof) }}" class="rounded-lg shadow max-w-full h-auto" />
@else
    <p class="text-sm text-gray-500 italic">Belum ada bukti pembayaran.</p>
@endif
