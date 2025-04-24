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
    <main class="absolute top-0 left-0 min-h-screen w-full">
        <section class="relative bg-cover bg-center bg-no-repeat h-screen w-full top-0 left-0 z-40 flex-row content-end" style="background-image: url('{{ asset('img/hero.png') }}');">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent z-10"></div>
    
            <div class="relative max-w-screen-xl z-20 mx-auto mb-24">
                <h3 class="text-white text-6xl mb-4 inter-bold">Tinggal Nyaman,<br>Belajar Tenang</h3>
            </div>
        </section>
    
        <section class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8 h-[2000px] inter-base">
            <div class="grid grid-cols-3 gap-10">
                @foreach ($buildings as $building)
                    <a href="{{ route('buildings.show', $building->id) }}" class="block">
                        <div
                            class="flex flex-col items-center bg-white p-4 shadow-md rounded-lg hover:border-blue-500 transition">
                            <img src="{{ Storage::disk('s3')->url($building->image_url) }}" alt="{{ $building->name }}"
                                class="w-full h-48 object-cover rounded-md">
                            <h3 class="mt-3 text-lg font-semibold text-gray-900">
                                {{ $building->name }}
                            </h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </main>
@endsection
