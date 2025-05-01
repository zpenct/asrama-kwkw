@extends('layouts.app')

@section('content')
    <div class="max-w-screen-xl mx-auto mt-16 space-y-6">
        @foreach ($booking as $transaction)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 flex justify-between items-center space-x-8 transition hover:shadow-md">
                <div class="flex-1 space-y-2">
                    <h3 class="text-base font-semibold text-gray-900">
                        Booking ID: <span class="text-blue-600">#{{ $transaction->id }}</span>
                    </h3>

                    <p class="text-sm text-gray-600">
                        Kode Kamar:
                        <span class="font-medium text-gray-800">{{ strtoupper(optional($transaction->room)->code) }}</span>
                    </p>

                    <p class="text-sm text-gray-600">
                        Check-in:
                        <span class="text-gray-800">{{ \Carbon\Carbon::parse($transaction->checkin_date)->format('d M Y') }}</span>
                    </p>

                    <p class="text-sm text-gray-600">
                        Check-out:
                        <span class="text-gray-800">{{ \Carbon\Carbon::parse($transaction->checkout_date)->format('d M Y') }}</span>
                    </p>
                </div>

                <div class="flex-1 space-y-2">
                    <p class="text-sm text-gray-600">
                        Status Booking:
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
                        Status Pembayaran:
                        <span class="font-medium text-gray-800">{{ ucfirst($transaction->payment_status ?? 'Belum ada') }}</span>
                    </p>

                    <p class="text-sm text-gray-600">
                        Jumlah yang harus dibayar:
                        <span class="font-medium text-red-600">
                            <!-- Rp{{ number_format($transaction->amount ?? 0, 0, ',', '.') }} -->Rp.1.000.000
                        </span>
                    </p>

                    <p class="text-sm text-gray-600">
                        Batas Waktu Pembayaran:
                        <span class="font-medium text-gray-800">
                            <!-- {{ $transaction->payment_deadline ? \Carbon\Carbon::parse($transaction->payment_deadline)->format('d M Y H:i') : '-' }} -->mantap
                        </span>
                    </p>
                </div>

                <div class="flex-shrink-0">
                    <a href="{{ route('booking.show', $transaction->id) }}"
                    class="inline-block text-sm bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition">
                        Detail
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
