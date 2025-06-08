@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
        <img src="{{ Storage::disk('s3')->url($building->image_url) }}" alt="{{ $building->name }}"
            class="w-full h-48 object-cover rounded-md">

        <header>
            <h2 class="text-xl font-bold text-gray-900 sm:text-3xl mt-3">
                {{ $building->name }} - Floors & Rooms
            </h2>
            <p class="mt-3 text-gray-500">{!! $building->getDescriptionHtml() !!}</p>
        </header>

        {{-- Fasilitas --}}
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

        @php
            $userGender = auth()->user()?->gender; // "male" atau "female"
            $buildingType = $building->type; // "PUTRA" atau "PUTRI"
            $canBook = !(
                ($userGender === 'male' && $buildingType === 'PUTRI') ||
                ($userGender === 'female' && $buildingType === 'PUTRA')
            );
        @endphp

        @if (!$canBook)
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 mt-5" role="alert">
                <strong class="font-bold">Maaf!</strong>
                <span class="block sm:inline">Kamu tidak diperbolehkan booking gedung ini karena perbedaan gender.</span>
            </div>
        @endif


        {{-- Form Filter Booking --}}
        <form method="GET" action="{{ route('buildings.show', $building->id) }}"
            class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-6 mb-8 lg:sticky lg:top-0 lg:z-50 bg-gray-50 py-6 w-full"
            id="form-cari-kamar" @if (!$canBook) onsubmit="return false;" @endif>


            <div>
                <label for="checkin_date" class="block text-sm font-medium">Check-in Date</label>
                <input type="date" name="checkin_date" id="checkin_date" value="{{ request('checkin_date') }}"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                    min="{{ now()->toDateString() }}" required>
            </div>

            <div>
                <label for="lama_inap" class="block text-sm font-medium">Lama Inap</label>
                <select name="lama_inap" id="lama_inap"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                    <option value="">Pilih Lama Inap</option>
                    @for ($i = 6; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('lama_inap') == $i ? 'selected' : '' }}>
                            {{ $i }} Bulan</option>
                    @endfor
                </select>
            </div>

            <div>
                <label for="checkout_date" class="block text-sm font-medium">Checkout Date (Readonly)</label>
                <input type="date" id="checkout_date" disabled
                    class="bg-white border border-gray-200 text-gray-700 cursor-not-allowed text-sm rounded-lg block w-full p-2.5">
            </div>

            <div class="flex items-center mt-2">
                <input type="checkbox" id="full_occupancy" name="full_occupancy" value="1"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded" {{ request('full_occupancy') ? 'checked' : '' }}>
                <label for="full_occupancy" class="ml-2 text-sm text-gray-700">Penuhi seluruh kamar</label>
            </div>


            <div class="self-end">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg transition-all">
                    Cari Kamar
                </button>
            </div>
        </form>

        {{-- List of Rooms per Floor --}}
        <div class="mt-12">
            <h2 class="text-xl font-semibold text-gray-900">
                {{ !request('checkin_date') || !request('lama_inap') ? 'Rooms' : 'Available Rooms' }}
            </h2>


            @php
                // Ambil query string booking
                $bookingParams = [
                    'checkin_date' => request('checkin_date'),
                    'lama_inap' => request('lama_inap'),
                ];

                $filterlessUrl = route('buildings.show', $building->id) . '?' . http_build_query($bookingParams);
            @endphp

            <form method="GET" action="{{ route('buildings.show', $building->id) }}"
                class="mt-8 grid grid-cols-2 gap-4 lg:grid-cols-7">

                {{-- Inject hidden input untuk mempertahankan booking param --}}
                @foreach ($bookingParams as $key => $val)
                    @if ($val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach

                <select name="floor"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    <option value="">Select Floor</option>
                    @foreach ($building->floors as $floor)
                        <option value="{{ $floor->floor }}" {{ request('floor') == $floor->floor ? 'selected' : '' }}>
                            Floor {{ $floor->floor }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="room_code" placeholder="Room Code"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                    value="{{ request('room_code') }}">

                <input type="number" name="price_min" placeholder="Min Price"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                    value="{{ request('price_min') }}">
                <input type="number" name="price_max" placeholder="Max Price"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                    value="{{ request('price_max') }}">

                <select name="sort_by"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    <option value="">Sort by Max Capacity</option>
                    <option value="asc" {{ request('sort_by') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('sort_by') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>

                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg">
                    Apply Filter
                </button>

                {{-- Tombol Clear Filter yang tetap mempertahankan checkin_date & lama_inap --}}
                <a href="{{ $filterlessUrl }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 p-3 rounded-lg text-center block w-full mt-2 lg:mt-0 lg:w-auto">
                    Clear
                </a>
            </form>


            @if ($floors->isEmpty())
                <p class="text-gray-500 italic mt-3">Maaf, Tidak ada kamar dengan spek seperti itu</p>
            @else
                <ul class="mt-2">
                    @foreach ($floors as $floor)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Floor {{ $floor->floor }}</h3>
                            <div class="flex gap-2 text-sm text-gray-500">
                                <p>Max Capacity: {{ $floor->max_capacity }}</p>
                                <p>Price: Rp{{ number_format($floor->price, 2) }} / year / person</p>
                            </div>

                            <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-4">
                                @foreach ($floor->rooms as $room)
                                    @php

                                        $bookings = $room
                                            ->bookings()
                                            ->whereIn('status', ['pending', 'booked'])
                                            ->where(function ($q) {
                                                $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
                                            })
                                            ->get();

                                        $fullFrom = null;
                                        $fullTo = null;

                                        if ($bookings->count()) {
                                            $minDate = \Carbon\Carbon::parse($bookings->min('checkin_date'));
                                            $maxDate = \Carbon\Carbon::parse($bookings->max('checkout_date'));

                                            $period = \Carbon\CarbonPeriod::create($minDate, $maxDate->subDay());

                                            foreach ($period as $date) {
                                                $totalGuestOnDate = $room
                                                    ->bookings()
                                                    ->whereIn('status', ['pending', 'booked'])
                                                    ->where(function ($q) {
                                                        $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
                                                    })
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
                                        class="group ring-1 ring-gray-200 px-6 py-4 transition hover:-translate-y-1 hover:bg-blue-600 text-gray-800 hover:text-white rounded-lg bg-white">
                                        <p class="text-base font-semibold">{{ $room->code }}</p>
                                        <p class="text-sm">{{ $room->booked_guest_count }}/{{ $floor->max_capacity }} Slot
                                            Terisi</p>

                                        @if ($fullFrom && $fullTo)
                                            <p class="text-xs text-red-400 group-hover:text-red-100">
                                                Penuh dari {{ $fullFrom->format('d M Y') }} sampai
                                                {{ $fullTo->format('d M Y') }}
                                            </p>
                                        @endif
                                        @php
                                            $userGender = auth()->user()->gender ?? null; // 'male' / 'female'
                                            $buildingType = $building->type; // 'PUTRA' / 'PUTRI'
                                            $genderMismatch =
                                                ($userGender === 'male' && $buildingType === 'PUTRI') ||
                                                ($userGender === 'female' && $buildingType === 'PUTRA');
                                            $checkin = request('checkin_date');
                                            $lama = request('lama_inap');
                                        @endphp

                                        @if ($genderMismatch)
                                            <div class="mt-4 text-sm text-red-500 italic">
                                                Gedung ini hanya tersedia untuk
                                                {{ $buildingType === 'PUTRA' ? 'laki-laki' : 'perempuan' }}.
                                            </div>
                                        @else
                                            <form id="booking-form-{{ $room->id }}"
                                                action="{{ route('booking.store') }}" method="POST" class="mt-4">
                                                @csrf
                                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                                <input type="hidden" name="checkin_date" value="{{ $checkin }}">
                                                <input type="hidden" name="lama_inap" value="{{ $lama }}">
                                                <input type="hidden" name="checkout_date"
                                                    value="{{ $checkin && $lama ? \Carbon\Carbon::parse($checkin)->addMonths((int) $lama)->toDateString() : '' }}">
                                                <input type="hidden" name="full_occupancy"
                                                    value="{{ request('full_occupancy') ? '1' : '0' }}">

                                                <button type="submit"
                                                    @if (!$checkin || !$lama) disabled class="opacity-50 cursor-not-allowed book-now-btn" @endif
                                                    class="text-blue-600 group-hover:text-white text-sm book-now-btn group-hover:bg-orange-500 rounded-full px-4 py-2">
                                                    Book Now →
                                                </button>
                                            </form>
                                        @endif


                                    </div>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
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

                    checkoutInput.value = `${yyyy}-${mm}-${dd}`;
                } else {
                    checkoutInput.value = "";
                }
            }

            checkinInput.addEventListener("change", updateCheckoutDate);
            lamaInapSelect.addEventListener("change", updateCheckoutDate);
            updateCheckoutDate();

            const cariKamarForm = document.getElementById('form-cari-kamar');
            const cariButton = cariKamarForm?.querySelector('button[type="submit"]');

            if (cariKamarForm && cariButton) {
                cariKamarForm.addEventListener('submit', function() {
                    cariButton.disabled = true;
                    cariButton.innerHTML = 'Loading...';
                    cariButton.classList.add('opacity-70', 'cursor-wait');
                });
            }

            const bookForms = document.querySelectorAll('form[action*="booking.store"]');

            const bookButtons = document.querySelectorAll('.book-now-btn');

            bookButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    if (button.disabled) return;

                    button.disabled = true;
                    button.textContent = 'Processing...';
                    button.classList.add('opacity-70', 'cursor-wait');

                    button.innerHTML =
                        `<svg class="animate-spin w-4 h-4 inline-block mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/> <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Processing...`;

                    const form = button.closest('form');
                    if (form) {
                        form.submit();
                    }
                });
            });

        });
    </script>
@endsection
