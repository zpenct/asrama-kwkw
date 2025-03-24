@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <main class="mt-20 mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
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
    </main>
@endsection
