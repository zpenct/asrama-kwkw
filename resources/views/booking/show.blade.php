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
        $pricePerPersonPerYear = $floor->price;
        $imageFloor = $floor->image_url;

        $checkin = \Carbon\Carbon::parse($booking->checkin_date);
        $checkout = \Carbon\Carbon::parse($booking->checkout_date);
        $durationInYears = $checkin->floatDiffInRealYears($checkout);
        $durationInMonths = $checkin->floatDiffInMonths($checkout);

        $totalAmount = $pricePerPersonPerYear * $totalGuest * $durationInYears;

        $latestTransaction = $booking->transactions()->latest()->first();
        $transactionStatus = $latestTransaction->status ?? null;

        $hasActiveTransaction =
            $latestTransaction && in_array($transactionStatus, ['waiting_payment', 'waiting_verification']);
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 grid-rows-5 gap-6 max-w-8xl mx-auto p-6">
        <div class="md:col-span-2 md:row-span-5 bg-white border border-gray-200 shadow-sm rounded-lg p-6">
            <div class="max-w-full rounded-lg">
                <img class="rounded-lg w-full bg-cover"
                    src="{{ $imageFloor ? Storage::disk('s3')->url($imageFloor) : asset('images/default-floor.jpg') }}"
                    alt="Floor Image" />

                <div class="divide-y rounded-lg mx-auto mt-10">
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-slate-900">Frequently asked questions</h2>
                    </div>
                    <div class="accordion" role="accordion">
                        <button type="button"
                            class="toggle-button w-full text-base outline-none text-left font-semibold py-6 text-slate-900 hover:text-blue-600 flex items-center">
                            <span class="mr-4">Bagaimana cara melakukan booking kamar?</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"
                                class="w-3 fill-current ml-auto shrink-0">
                                <path class="plus"
                                    d="M37.059 16H26V4.941C26 2.224 23.718 0 21 0s-5 2.224-5 4.941V16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5H16v11.059C16 39.776 18.282 42 21 42s5-2.224 5-4.941V26h11.059C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                                <path
                                    d="M37.059 16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5h32.118C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                            </svg>
                        </button>
                        <div class="content invisible max-h-0 overflow-hidden transition-all duration-300">
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Pilih tanggal check-in, durasi, dan jumlah tamu. Sistem akan menampilkan kamar yang
                                tersedia.
                                Setelah memilih kamar, Anda dapat menekan tombol "Proceed to Payment" untuk lanjut ke tahap
                                pembayaran.
                            </p>
                        </div>
                    </div>

                    <div class="accordion" role="accordion">
                        <button type="button"
                            class="toggle-button w-full text-base outline-none text-left font-semibold py-6 text-slate-900 hover:text-blue-600 flex items-center">
                            <span class="mr-4">Apa yang harus saya lakukan setelah booking?</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"
                                class="w-3 fill-current ml-auto shrink-0">
                                <path class="plus"
                                    d="M37.059 16H26V4.941C26 2.224 23.718 0 21 0s-5 2.224-5 4.941V16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5H16v11.059C16 39.776 18.282 42 21 42s5-2.224 5-4.941V26h11.059C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                                <path
                                    d="M37.059 16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5h32.118C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                            </svg>
                        </button>
                        <div class="content invisible max-h-0 overflow-hidden transition-all duration-300">
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Setelah booking, Anda akan diarahkan ke halaman transaksi untuk mengupload bukti pembayaran.
                                Bukti ini harus diunggah dalam waktu maksimal 24 jam, atau booking akan kedaluwarsa
                                otomatis.
                            </p>
                        </div>
                    </div>

                    <div class="accordion" role="accordion">
                        <button type="button"
                            class="toggle-button w-full text-base outline-none text-left font-semibold py-6 text-slate-900 hover:text-blue-600 flex items-center">
                            <span class="mr-4">Berapa lama waktu yang saya miliki untuk melakukan pembayaran?</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"
                                class="w-3 fill-current ml-auto shrink-0">
                                <path class="plus"
                                    d="M37.059 16H26V4.941C26 2.224 23.718 0 21 0s-5 2.224-5 4.941V16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5H16v11.059C16 39.776 18.282 42 21 42s5-2.224 5-4.941V26h11.059C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                                <path
                                    d="M37.059 16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5h32.118C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                            </svg>
                        </button>
                        <div class="content invisible max-h-0 overflow-hidden transition-all duration-300">
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Anda memiliki waktu 24 jam sejak melakukan booking untuk menyelesaikan pembayaran dan
                                mengupload bukti transfer.
                                Jika lewat dari itu, booking akan otomatis kadaluarsa.
                            </p>
                        </div>
                    </div>

                    <div class="accordion" role="accordion">
                        <button type="button"
                            class="toggle-button w-full text-base outline-none text-left font-semibold py-6 text-slate-900 hover:text-blue-600 flex items-center">
                            <span class="mr-4">Bagaimana saya tahu apakah booking saya sudah dikonfirmasi?</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"
                                class="w-3 fill-current ml-auto shrink-0">
                                <path class="plus"
                                    d="M37.059 16H26V4.941C26 2.224 23.718 0 21 0s-5 2.224-5 4.941V16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5H16v11.059C16 39.776 18.282 42 21 42s5-2.224 5-4.941V26h11.059C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                                <path
                                    d="M37.059 16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5h32.118C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" />
                            </svg>
                        </button>
                        <div class="content invisible max-h-0 overflow-hidden transition-all duration-300">
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Setelah Anda mengunggah bukti pembayaran, admin akan memverifikasi dalam waktu maksimal 1x24
                                jam.
                                Anda akan menerima notifikasi atau bisa mengecek statusnya langsung di halaman booking Anda.
                            </p>
                        </div>
                    </div>


                </div>
            </div>

        </div>
        <div class="md:row-span-5 md:col-start-3">
            <div class="mx-auto p-6 bg-white rounded-lg shadow-sm border border-gray-200">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Booking Details</h2>

                <div class="space-y-3 mb-6 mt-12">
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
                        <span class="text-gray-600">Created At</span>
                        <span class="font-medium">{{ $booking->created_at->format('d M Y') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Expired Booking Date</span>
                        <span class="font-medium">{{ $booking->expired_at }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Booking Status</span>
                        <span class="capitalize font-medium">
                            {{ $booking->getComputedStatusAttribute() }}
                        </span>

                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Transaction Status</span>
                        <span class="capitalize font-medium">
                            {{ $transactionStatus ? str_replace('_', ' ', $transactionStatus) : '-' }}
                        </span>

                    </div>
                </div>

                <div class="space-y-4">


                    <div class="grid grid-cols-1 gap-2 rounded-xl bg-[#333A73] text-white p-2  mt-4">
                        <div class="flex gap-4">
                            <div class="flex-1 flex flex-col gap-1 p-2">
                                <span class="text-xl leading-none">
                                    Rp{{ number_format($pricePerPersonPerYear, 0, ',', '.') }}
                                </span>
                                <span class="text-xs font-bold">Harga per orang / tahun</span>
                            </div>
                            <div class="flex-1 flex flex-col gap-1 p-2">
                                <span class="text-xl leading-none">
                                    {{ round($durationInMonths, 1) }} Bulan
                                </span>
                                <span class="text-xs font-semibold">Durasi inap</span>
                            </div>
                            <div class="flex-1 hidden lg:flex flex-col gap-1 p-2">
                                <span class="text-xl leading-none">
                                    {{ $booking->total_guest }}
                                </span>
                                <span class="text-xs font-semibold">Jumlah tamu</span>
                            </div>
                        </div>
                        <div class="bg-[#387ADF] p-2 rounded-lg">
                            <div class="flex flex-col gap-1 p-2">

                                <span class="text-2xl leading-none">
                                    Rp{{ number_format($totalAmount, 0, ',', '.') }}
                                </span>
                                <span class="text-xs font-semibold text-blue-100">Total Biaya Keseluruhan</span>
                            </div>
                        </div>
                    </div>

                    @if ($hasActiveTransaction)
                        <a href="{{ route('transactions.upload', $latestTransaction->id) }}"
                            class="block text-center py-3 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 mt-4">
                            View Transaction Details
                        </a>
                    @endif

                    @if ($booking->status === 'pending' && !$hasActiveTransaction)
                        <form method="POST" action="{{ route('booking.update', $booking) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" id="proceed-payment-btn"
                                class="w-full py-3 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                                Proceed to Payment
                            </button>
                        </form>
                    @endif

                    @if ($booking->status === 'expired')
                        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded-lg text-sm">
                            Booking ini telah kedaluwarsa. Silakan lakukan booking ulang.
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>




    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.accordion').forEach(elm => {
                const button = elm.querySelector('.toggle-button');
                const content = elm.querySelector('.content');
                const plusIcon = button.querySelector('.plus');

                button.addEventListener('click', () => {
                    const isHidden = content.classList.toggle('invisible');
                    content.style.maxHeight = isHidden ? '0px' : `${content.scrollHeight + 100}px`;
                    button.classList.toggle('text-blue-600', !isHidden);
                    button.classList.toggle('text-gray-800', isHidden);
                    content.classList.toggle('pb-6', !isHidden);
                    plusIcon.classList.toggle('hidden', !isHidden);
                    plusIcon.classList.toggle('block', isHidden);
                });
            })
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
