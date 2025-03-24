<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Building</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>

<body>
    <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
        <img src="{{ Storage::disk('s3')->url($building->image_url) }}" alt="{{ $building->name }}"
            class="w-full h-48 object-cover rounded-md">
        <header>
            <h2 class="text-xl font-bold text-gray-900 sm:text-3xl mt-3">
                {{ $building->name }} - Floors & Rooms
            </h2>
            <p class="mt-3 text-gray-500">{!! $building->getDescriptionHtml() !!}</p>
        </header>

        <div class="mt-6">
            <h2 class="text-lg font-semibold text-gray-900">Fasilitas:</h2>
            @if ($building->facilities->isEmpty())
                <p class="text-gray-500 italic">Maaf, Fasilitas gedung ini belum ditambahkan.</p>
            @else
                <ul class="mt-2 flex flex-wrap gap-2">
                    @foreach ($building->facilities as $facility)
                        <li
                            class="inline-flex items-center px-2 py-1 me-2 text-sm font-medium text-green-800 border-green-500 border-[1.25px] bg-green-100 rounded-lg">
                            {{ $facility->name }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <form method="GET" action="{{ route('buildings.show', $building->id) }}"
            class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6 mb-8 sticky top-0 z-50 bg-white py-6 w-full">

            <!-- Check-in -->
            <div>
                <label for="checkin_date" class="block text-sm font-medium">Check-in Date</label>
                <input type="date" name="checkin_date" id="checkin_date" value="{{ request('checkin_date') }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    min="{{ now()->toDateString() }}" required>

                @error('checkin_date')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Lama Inap -->
            <div>
                <label for="lama_inap" class="block text-sm font-medium">Lama Inap</label>
                <select name="lama_inap" id="lama_inap"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
                    <option value="">Pilih Lama Inap</option>
                    @for ($i = 6; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('lama_inap') == $i ? 'selected' : '' }}>
                            {{ $i }} Bulan</option>
                    @endfor
                </select>
                @error('lama_inap')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Checkout Date (Readonly) -->
            <div>
                <label for="checkout_date" class="block text-sm font-medium">Checkout Date (Readonly)</label>
                <input type="date" id="checkout_date" disabled
                    class="bg-gray-100 border border-gray-200 text-gray-700 text-sm rounded-lg block w-full p-2.5">
            </div>


            <div class="self-end">
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded transition-all">
                    Cari Kamar
                </button>
            </div>
        </form>

        <!-- Filter & Sorting Form -->
        <form method="GET" action="{{ route('buildings.show', $building->id) }}"
            class="mt-12 grid grid-cols-2 gap-4 lg:grid-cols-6">
            <!-- Filter Nomor Lantai -->
            <select name="floor"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="">Select Floor</option>
                @foreach ($building->floors as $floor)
                    <option value="{{ $floor->floor }}" {{ request('floor') == $floor->floor ? 'selected' : '' }}>
                        Floor {{ $floor->floor }}
                    </option>
                @endforeach
            </select>

            <!-- Search Room Code -->
            <input type="text" name="room_code" placeholder="Room Code"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                value="{{ request('room_code') }}">

            <!-- Range Harga -->
            <input type="number" name="price_min" placeholder="Min Price"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                value="{{ request('price_min') }}">
            <input type="number" name="price_max" placeholder="Max Price"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                value="{{ request('price_max') }}">

            <!-- Sorting Kapasitas Maksimum -->
            <select name="sort_by"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="">Sort by Max Capacity</option>
                <option value="asc" {{ request('sort_by') == 'asc' ? 'selected' : '' }}>Ascending</option>
                <option value="desc" {{ request('sort_by') == 'desc' ? 'selected' : '' }}>Descending</option>
            </select>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Apply Filter</button>
        </form>

        <!-- Floor and Room List -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-900">
                {{ !request('checkin_date') || !request('lama_inap') ? 'Rooms' : 'Available Rooms' }}
            </h2>

            @if ($floors->isEmpty())
                <p class="text-gray-500 italic">Maaf, Tidak ada kamar dengan Spek kek gitu</p>
            @else
                <ul class="mt-2">
                    @foreach ($floors as $floor)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Floor {{ $floor->floor }}
                            </h3>
                            <div class="flex gap-2">
                                <p class="text-gray-500">Max Capacity: {{ $floor->max_capacity }}</p>
                                <p class="text-gray-500">Price: Rp{{ number_format($floor->price, 2) }} / year / people
                                </p>
                            </div>
                            <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-4">
                                @foreach ($floor->rooms as $room)
                                    @php
                                        $bookings = $room->bookings()->where('status', 'booked')->get();

                                        $fullFrom = null;
                                        $fullTo = null;

                                        if ($bookings->count()) {
                                            $minDate = \Carbon\Carbon::parse($bookings->min('checkin_date'));
                                            $maxDate = \Carbon\Carbon::parse($bookings->max('checkout_date'));

                                            $period = \Carbon\CarbonPeriod::create($minDate, $maxDate->subDay());

                                            foreach ($period as $date) {
                                                $totalGuestOnDate = $room
                                                    ->bookings()
                                                    ->where('status', 'booked')
                                                    ->whereDate('checkin_date', '<=', $date)
                                                    ->whereDate('checkout_date', '>', $date)
                                                    ->sum('total_guest');

                                                if ($totalGuestOnDate >= $floor->max_capacity) {
                                                    if (!$fullFrom) {
                                                        $fullFrom = $date->copy();
                                                    }
                                                    $fullTo = $date->copy();
                                                } elseif ($fullFrom && $fullTo) {
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp


                                    <div
                                        class="group cursor-pointer overflow-hidden ring-1 ring-gray-900/5 transition-all duration-300 hover:-translate-y-1 px-6 py-4">
                                        <span
                                            class="absolute -top-3 z-0 h-1 w-1 rounded-full bg-blue-500 transition-all duration-300 group-hover:scale-[100]"></span>
                                        <div class="relative z-10 mx-auto max-w-md">
                                            <span
                                                class="grid h-0 w-0 place-items-center rounded-full bg-blue-500 transition-all duration-300 group-hover:bg-blue-400">

                                            </span>
                                            <div
                                                class="space-y-6 text-base leading-7 text-gray-600 transition-all duration-300 group-hover:text-white/90">
                                                <!-- Room Code -->
                                                <p>{{ $room->code }}</p>

                                                <!-- Booked Guests and Max Capacity -->
                                                <p class="text-sm text-gray-500 flex items-center">
                                                    {{ $room->booked_guest_count }}/{{ $floor->max_capacity }} Slot
                                                    Terisi
                                                </p>

                                                <!-- Full Date Range (Conditional) -->
                                                @if ($fullFrom && $fullTo)
                                                    <p class="text-sm text-red-500">
                                                        Penuh dari {{ $fullFrom->format('d M Y') }} sampai
                                                        {{ $fullTo->format('d M Y') }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="pt-5 text-base font-semibold leading-7">
                                                <p>
                                                <form action="{{ route('booking.store') }}" method="POST">
                                                    <form action="{{ route('booking.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="room_id"
                                                            value="{{ $room->id }}">
                                                        <input type="hidden" name="checkin_date"
                                                            value="{{ request('checkin_date') }}">
                                                        <input type="hidden" name="lama_inap"
                                                            value="{{ request('lama_inap') }}">
                                                        <input type="hidden" name="checkout_date"
                                                            value="{{ \Carbon\Carbon::parse(request('checkin_date'))->addMonths((int) request('lama_inap'))->toDateString() }}">
                                                        <button type="submit"
                                                            @if (!request('checkin_date') || !request('lama_inap')) disabled class="opacity-50 cursor-not-allowed" @endif
                                                            class="text-blue-500 transition-all duration-300 group-hover:text-white">
                                                            Book Now &rarr;
                                                        </button>

                                                    </form>

                                                    </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </ul>
            @endif

        </div>
    </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkinInput = document.getElementById("checkin_date");
            const lamaInapSelect = document.getElementById("lama_inap");
            const checkoutInput = document.getElementById("checkout_date");

            function updateCheckoutDate() {
                const checkin = checkinInput.value;
                const lama = parseInt(lamaInapSelect.value);

                if (checkin && lama) {
                    const checkinDate = new Date(checkin);
                    checkinDate.setMonth(checkinDate.getMonth() + lama);

                    const yyyy = checkinDate.getFullYear();
                    const mm = String(checkinDate.getMonth() + 1).padStart(2, '0');
                    const dd = String(checkinDate.getDate()).padStart(2, '0');

                    const formatted = `${yyyy}-${mm}-${dd}`;
                    checkoutInput.value = formatted;
                } else {
                    checkoutInput.value = "";
                }
            }

            checkinInput.addEventListener("change", updateCheckoutDate);
            lamaInapSelect.addEventListener("change", updateCheckoutDate);

            updateCheckoutDate();
        });
    </script>


</body>

</html>
