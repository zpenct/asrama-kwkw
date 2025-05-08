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

@section('title', 'Home')

@section('content')
    <main class="min-h-screen w-full">
        <section class="relative top-0 px-4 bg-cover bg-center bg-no-repeat h-screen w-full flex-row content-end" style="background-image: url('{{ asset('img/hero1.jpg') }}');">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent z-10"></div>
    
            <div class="relative max-w-screen-xl z-20 mx-auto mb-24">
                <h3 class="text-white text-6xl mb-4 inter-bold">Tinggal Nyaman,<br>Belajar Tenang</h3>
            </div>
        </section>
    
        <section class="mx-auto max-w-screen-xl py-8 px-4 h-full inter-base">
            <div class="max-w-screen-xl inter-base p-3 text-3xl text-center">
                <h3>Pilih Asrama Mu!</h3>
            </div>
            <div>
            <div class="grid grid-cols-3 gap-10 mt-3">
                @foreach ($buildings as $building)
                    <div class="block">
                        <div class="relative flex flex-col gap-4 bg-white p-4 shadow-md rounded-lg border border-transparent">
                            
                            <img src="{{ Storage::disk('s3')->url($building->image_url) }}" 
                                alt="{{ $building->name }}" 
                                class="w-full h-48 object-cover rounded-md">
                            
                            <div>
                                <h3 class="pl-1 mb-2 text-xl font-semibold text-gray-900">
                                    {{ $building->name }}
                                </h3>
                                <p class="flex items-center gap-1 inter-bold text-blue-700 text-sm">
                                    <img src="{{ asset('img/location.png') }}" alt="" class="w-5 h-5">
                                    Kampus Teknik UNHAS Gowa
                                </p>
                            </div>

                            <hr class="border-t-4 border-dotted border-gray-300">

                            <div class="flex flex-col gap-2">
                                <p class="text-end text-sm text-gray-400">
                                    Mulai dari 
                                    <span class="text-red-600 inter-bold text-xl">Rp. 3.500.000,-</span><br>
                                    / orang / tahun
                                </p>
                                <div class="flex items-center gap-2 text-sm inter-bold p-2 bg-blue-100 w-fit rounded-lg text-[#0854E7]">
                                    <img src="{{ asset('img/fren.png') }}" alt="" class="w-4 h-4">
                                    <span>2 Orang / Kamar</span>
                                </div>
                            </div>

                            <a href="{{ route('buildings.show', $building->id) }}" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold py-4 px-4 rounded-lg text-center">
                                Pilih Asrama
                            </a>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </section>
        <x-footer />
    </main>
@endsection
