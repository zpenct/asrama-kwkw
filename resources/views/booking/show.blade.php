@extends('layouts.app')

@section('content')
    @php
        use App\Models\Booking;

        $room = $booking->room;
        $floor = $room->floor;
        $maxCapacity = $floor->max_capacity;

        $usedGuests = Booking::where('room_id', $room->id)
            ->where('status', 'booked')
            ->where('id', '!=', $booking->id)
            ->sum('total_guest');

        $totalGuest = old('total_guest', $booking->total_guest ?? 1);
        $availableGuests = max($maxCapacity - $usedGuests, 0);
        $pricePerPersonPerYear = $floor->price;

        $checkin = \Carbon\Carbon::parse($booking->checkin_date);
        $checkout = \Carbon\Carbon::parse($booking->checkout_date);
        $durationInYears = $checkin->floatDiffInRealYears($checkout);
        $durationInMonths = $checkin->floatDiffInMonths($checkout);

        $totalAmount = $pricePerPersonPerYear * $totalGuest * $durationInYears;

        $latestTransaction = $booking->transactions->last();
        $transactionStatus = $latestTransaction->status ?? null;

        $showGuestInput = !$latestTransaction || ($booking->status === 'pending' && $transactionStatus === 'expired');

        $hasActiveTransaction =
            $latestTransaction && in_array($transactionStatus, ['waiting_payment', 'waiting_verification']);
    @endphp

    <div class="max-w-lg mx-auto mt-6 p-6 bg-white rounded-lg shadow-sm">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Booking Details</h2>

        <div class="space-y-3 mb-6">
            <div class="flex justify-between">
                <span class="text-gray-600">Booking ID</span>
                <span class="font-medium">{{ $booking->id }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-600">Room Code</span>
                <span class="font-medium">{{ $room->code ?? '-' }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-600">Requested By</span>
                <span class="font-medium">{{ $booking->user->name ?? 'Guest' }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-600">Check-in Date</span>
                <span class="font-medium">{{ $checkin->format('d M Y') }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-600">Check-out Date</span>
                <span class="font-medium">{{ $checkout->format('d M Y') }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-600">Booking Status</span>
                <span class="capitalize font-medium">{{ $booking->status }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-600">Transaction Status</span>
                <span class="capitalize font-medium">{{ str_replace('_', ' ', $transactionStatus) ?? '-' }}</span>
            </div>
        </div>

        {{-- Total Guest Form or Text --}}
        <div class="mt-6 border-t pt-6">
            @if ($showGuestInput)
                <form action="{{ route('booking.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="total_guest" class="block text-sm font-medium text-gray-700 mb-1">Total Guest</label>
                        <input type="number" id="total_guest" name="total_guest" value="{{ $totalGuest }}" min="1"
                            max="{{ $availableGuests }}"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                            data-price="{{ $pricePerPersonPerYear }}" data-duration="{{ $durationInYears }}">
                        <p class="mt-1 text-xs text-gray-500">Available capacity: {{ $availableGuests }} guests</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="font-medium text-gray-700 mb-3">Price Calculation</h3>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Price per person/year</span>
                                <span>Rp{{ number_format($pricePerPersonPerYear, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duration</span>
                                <span>{{ round($durationInMonths, 1) }} months</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Number of guests</span>
                                <span id="guest_count">{{ $totalGuest }}</span>
                            </div>
                            <div class="border-t border-gray-200 my-2"></div>
                            <div class="flex justify-between font-medium">
                                <span class="text-gray-700">Total Amount</span>
                                <span class="text-blue-600">
                                    Rp<span id="total_amount">{{ number_format($totalAmount, 0, ',', '.') }}</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    @if ($booking->status === 'pending' && !$hasActiveTransaction)
                        <button type="submit" id="proceed-payment-btn"
                            class="w-full py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Proceed to Payment
                        </button>
                    @endif
                </form>
            @else
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Guest</span>
                        <span class="font-medium">{{ $booking->total_guest }}</span>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-700 mb-3">Price Breakdown</h3>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Price per person/year</span>
                                <span>Rp{{ number_format($pricePerPersonPerYear, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duration</span>
                                <span>{{ round($durationInMonths, 1) }} months</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Number of guests</span>
                                <span>{{ $booking->total_guest }}</span>
                            </div>
                            <div class="border-t border-gray-200 my-2"></div>
                            <div class="flex justify-between font-medium">
                                <span class="text-gray-700">Total Amount</span>
                                <span class="text-blue-600">
                                    Rp{{ number_format($booking->total_guest * $pricePerPersonPerYear * $durationInYears, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if ($hasActiveTransaction)
                        <a href="{{ route('transactions.upload', $latestTransaction->id) }}"
                            class="block text-center py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mt-4">
                            View Transaction Details
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Script untuk hitung ulang Total Amount --}}
    @if ($showGuestInput)
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const guestInput = document.getElementById('total_guest');
                const totalAmountSpan = document.getElementById('total_amount');
                const guestCountSpan = document.getElementById('guest_count');

                function updateAmount() {
                    const guest = parseInt(guestInput.value) || 0;
                    const price = parseFloat(guestInput.dataset.price);
                    const duration = parseFloat(guestInput.dataset.duration);
                    const total = Math.round(price * guest * duration);

                    totalAmountSpan.textContent = total.toLocaleString('id-ID');
                    guestCountSpan.textContent = guest;
                }

                guestInput.addEventListener('input', updateAmount);
                updateAmount();
            });
        </script>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const proceedBtn = document.getElementById("proceed-payment-btn");
            const parentForm = proceedBtn?.closest('form');

            if (proceedBtn && parentForm) {
                proceedBtn.addEventListener("click", function() {
                    if (proceedBtn.disabled) return;

                    // Disable tombol & ubah isi
                    proceedBtn.disabled = true;
                    proceedBtn.textContent = "Processing...";
                    proceedBtn.classList.add("opacity-70", "cursor-wait");
                    proceedBtn.innerHTML = `<svg class="animate-spin w-4 h-4 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                </svg> Processing...`;


                    // Submit form secara manual
                    parentForm.submit();
                });
            }
        });
    </script>

@endsection
