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

@section('title', 'Ramtek - PT. Inovasi Benua Maritim')

@section('content')
    <main class="min-h-screen w-full">
        <section class="relative top-0 px-4 bg-cover bg-center bg-no-repeat h-screen w-full flex-row content-end"
            style="background-image: url('{{ asset('img/hero1.jpg') }}');">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent z-10"></div>

            <div class="relative max-w-screen-xl z-20 mx-auto mb-24 text-white text-center">
                <h3 class=" text-4xl mb-4 inter-bold">Tinggal Nyaman, Belajar Tenang</h3>
                <p class="inter-base text-base opacity-70">Asrama mahasiswa/i dengan suasana hangat, aman 24 jam, plus air
                    dan listrik gratis. <br> Tinggal tenang, kuliah pun lancar.</p>
            </div>
        </section>

        <section class="mx-auto max-w-screen-xl mt-8 py-8 px-4 inter-base">
            <div class="max-w-screen-xl inter-base p-3 text-3xl">
                <h3 class="inter-bold text-4xl">Pilih Gedung</h3>
                <p class="text-xl">Cari kamar impian kalian!</p>
            </div>
            <div>
                <div class="grid grid-cols-3 gap-10 mt-3">
                    @foreach ($buildings as $building)
                        <div class="block">
                            <div
                                class="relative flex flex-col gap-4 bg-white  shadow-md rounded-2xl border border-transparent">

                                <img src="{{ Storage::disk('s3')->url($building->image_url) }}" alt="{{ $building->name }}"
                                    class="w-full h-64 object-cover rounded-t-2xl">

                                <div class=" pb-4 px-4 flex flex-col gap-4">
                                    <div class="flex justify-between">
                                        <div>
                                            <h3 class=" mb-2 text-xl inter-bold semibold text-gray-900">
                                                {{ $building->name }}
                                            </h3>
                                            <p class="flex items-center gap-1 inter-base text-sm">
                                                Mulai dari <span class="text-red-600 ">Rp.
                                                    {{ number_format($building->floors->min('price') ?? 0, 0, ',', '.') }}</span>
                                            </p>
                                        </div>
                                        <p class="content-end">{{ ucfirst(strtolower($building->type)) }}</p>
                                    </div>


                                    <hr class="border-gray-300">

                                    <a href="{{ route('buildings.show', $building->id) }}"
                                        class="mt-2 bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold py-4 px-4 rounded-xl text-center">
                                        Pilih Asrama
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
        </section>

        <section class="mx-auto my-20 px-4 inter-base flex flex-col gap-20 center max-w-screen-lg">
            <div class="text-center mx-auto">
                <h3 class="inter-bold text-4xl">Keamanan, Air, Listrik</h3>
                <p class="text-2xl opacity-70">Semua yang kalian butuhkan ada disini</p>
            </div>
            <div class="flex gap-36 mx-auto">
                <div class="flex flex-col gap-10">
                    <div class="flex flex-col gap-2 inter-base">
                        <span
                            class="rounded text-white inter-bold w-8 h-8 text-center content-center bg-blue-300 mb-2">1</span>
                        <div class="flex flex-col gap-5">
                            <h4 class="text-3xl inter-bold">Keamanan 24 Jam</h4>
                            <p class="opacity-70">Kami menyediakan sistem keamanan 24 jam dengan lingkungan yang aman dan
                                nyaman.</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 inter-base">
                        <span
                            class="rounded text-white inter-bold w-8 h-8 text-center content-center bg-blue-400 mb-2">2</span>
                        <div class="flex flex-col gap-5">
                            <h4 class="text-3xl inter-bold">Listrik Gratis</h4>
                            <p class="opacity-70">Nikmati fasilitas listrik gratis tanpa perlu memikirkan tagihan bulanan,
                                tinggal lebih hemat.</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 inter-base">
                        <span
                            class="rounded text-white inter-bold w-8 h-8 text-center content-center bg-blue-500 mb-2">3</span>
                        <div class="flex flex-col gap-5">
                            <h4 class="text-3xl inter-bold">Air Gratis</h4>
                            <p class="opacity-70">Penggunaan air bersih sepenuhnya gratis tanpa biaya tambahan setiap hari.
                            </p>
                        </div>
                    </div>
                </div>
                <img src="{{ asset('img/halaman.jpg') }}" alt="" class="w-1/3 rounded-xl">
            </div>
        </section>

        <section class="mx-auto max-w-screen-xl mt-8 py-8 px-4">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1093.54833821619!2d119.49901959404765!3d-5.230314316694764!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbee147fb76d7fd%3A0x88b0fffe0f90afee!2sRamsis%20FT%20Unhas%20Gowa!5e0!3m2!1sid!2sid!4v1748534726136!5m2!1sid!2sid"
                width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" class="mx-auto"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </section>
    </main>
@endsection
