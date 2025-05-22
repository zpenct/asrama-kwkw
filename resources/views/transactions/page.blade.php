<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
.inter-base {
    font-family: "Inter", sans-serif;
    font-weight: 400;
    font-style: normal;
}
.inter-bold {
    font-family: "Inter", sans-serif;
    font-weight: 700;
    font-style: normal;
}
</style>

@extends('layouts.app')

@section('content')
    <div class="max-w-screen-xl px-4 mt-8 mb-7 mx-auto inter-base">
        <h3 class="inter-bold text-5xl">Bookings</h3>
        <p class="font-light text-xl">Riwayat Booking yang telah dilakukan</p>
    </div>

    <div class="max-w-screen-xl px-4 my-5 mx-auto space-y-6 text-in inter-base">
        @foreach ($booking as $transaction)
            @php
                $checkin = \Carbon\Carbon::parse($transaction->checkin_date);
                $checkout = \Carbon\Carbon::parse($transaction->checkout_date);
                $durationInYears = $checkin->floatDiffInRealYears($checkout);

                $totalAmount = optional(optional($transaction->room)->floor)->price * $transaction->total_guest * $durationInYears;
            @endphp
            <div class="bg-white border h-48 border-gray-200 rounded-2xl shadow-sm p-4 flex justify-between items-center space-x-8 transition hover:shadow-md">
                <div class="flex gap-5 h-full">
                    @if(optional($transaction->room)->floor)
                        <img src="{{ Storage::disk('s3')->url($transaction->room->floor->image_url) }}" 
                            class="w-72 h-full object-cover rounded-md">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500 rounded-md">
                            Gambar tidak tersedia
                        </div>
                    @endif

                    <div class="flex flex-col justify-between">
                        <p class="text-sm font-bold text-gray-800 inter-bold">
                            {{ strtoupper(optional($transaction->room->building)->name) }},
                            {{ strtoupper(optional($transaction->room)->code) }}
                        </p>

                        <hr class=" border-1 my-2">

                        <p class="text-sm text-gray-600">
                            Check-in :
                            <span class="text-gray-800">{{ \Carbon\Carbon::parse($transaction->checkin_date)->format('d M Y') }}</span>
                        </p>

                        <p class="text-sm text-gray-600">
                            Status :
                            <span class="inline-block text-xs px-3 py-1 rounded-full 
                                {{ [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ][$transaction->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </p>

                        <p class="text-sm text-gray-600">
                            Amount :
                            <span class="font-medium text-red-600">
                                Rp{{ number_format($totalAmount ?? 0, 0, ',', '.') }}
                            </span>
                        </p>
        
                        <p class="text-sm text-gray-600">
                            Payment Deadline  :
                            <span class="font-medium text-gray-800">
                                {{ $transaction->expired_at }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="h-1/3 content-center">
                    <a href="{{ route('booking.show', $transaction->id) }}"
                    class="inline-flex items-center justify-center h-full text-sm bg-blue-600 hover:bg-blue-700 text-white font-base px-4 py-2 rounded-lg transition">
                        Detail
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
