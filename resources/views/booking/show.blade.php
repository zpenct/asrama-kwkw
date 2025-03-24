@php
    $room = $booking->room;
    $floor = $room->floor;
    $maxCapacity = $floor->max_capacity;

    $usedGuests = \App\Models\Booking::where('room_id', $room->id)
        ->where('status', 'booked')
        ->where('id', '!=', $booking->id)
        ->sum('total_guest');

    $totalGuest = old('total_guest', $booking->total_guest ?? 1);

    $availableGuests = max($maxCapacity - $usedGuests, 0);
    $max = $availableGuests + $booking->total_guest;

    $pricePerPersonPerYear = $floor->price;

    $checkin = \Carbon\Carbon::parse($booking->checkin_date);
    $checkout = \Carbon\Carbon::parse($booking->checkout_date);
    $durationInYears = $checkin->floatDiffInRealYears($checkout);

    $totalAmount = $pricePerPersonPerYear * $totalGuest * $durationInYears;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Building</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    <div class="max-w-2xl mx-auto p-6 bg-white border-blue-500 rounded-lg mt-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Booking Details</h2>

        <div class="space-y-4">
            <div class="flex justify-between border-b pb-2">
                <strong class="text-gray-700">Booking ID:</strong>
                <span class="text-gray-900">{{ $booking->id }}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <strong class="text-gray-700">Room Code:</strong>
                <span class="text-gray-900">{{ $booking->room->code ?? '-' }}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <strong class="text-gray-700">User:</strong>
                <span class="text-gray-900">{{ $booking->user->name ?? 'Guest' }}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <strong class="text-gray-700">Check-in Date:</strong>
                <span class="text-gray-900">{{ \Carbon\Carbon::parse($booking->checkin_date)->format('d M Y') }}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <strong class="text-gray-700">Check-out Date:</strong>
                <span class="text-gray-900">{{ \Carbon\Carbon::parse($booking->checkout_date)->format('d M Y') }}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <strong class="text-gray-700">Created At:</strong>
                <span class="text-gray-900">{{ $booking->created_at->format('d M Y H:i') }}</span>
            </div>

            <div class="mt-6">
                <form action="{{ route('booking.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <label for="total_guest" class="block text-sm font-medium text-gray-700">Total Guest</label>
                    <input type="number" id="total_guest" name="total_guest" value="{{ $totalGuest }}" min="1"
                        max="{{ $availableGuests }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <p class="mt-2 text-sm text-gray-500">Maksimal tamu yang bisa ditambahkan: {{ $availableGuests }}
                    </p>

                    <h2 class="text-xl font-bold text-blue-500 mt-6">Total Amount:
                        Rp{{ number_format($totalAmount, 0, ',', '.') }}</h2>

                    <button type="submit"
                        class="mt-6 w-full px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Next
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
