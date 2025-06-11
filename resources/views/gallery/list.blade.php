@extends('layouts.app')

@section('title', 'Gallery - PT. Inovasi Benua Maritim - Unofficial sekedar tugas kuliah')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">Building Images</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @forelse ($buildingPaths as $path)
                <div class="bg-white rounded shadow">
                    <img src="{{ Storage::disk('s3')->url($path) }}" alt="Building Image"
                        class="w-full h-48 object-cover rounded-lg">
                    {{-- <p class="text-xs text-center mt-1 px-2 break-words">{{ $path }}</p> --}}
                </div>
            @empty
                <p class="col-span-full text-gray-500 italic">No building images found.</p>
            @endforelse
        </div>

        <h2 class="text-2xl font-bold my-4 ">Floor Images</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
            @forelse ($floorPaths as $path)
                <div class="bg-white rounded shadow">
                    <img src="{{ Storage::disk('s3')->url($path) }}" alt="Floor Image"
                        class="w-full h-48 object-cover rounded-lg">
                    {{-- <p class="text-xs text-center mt-1 px-2 break-words">{{ $path }}</p> --}}
                </div>
            @empty
                <p class="col-span-full text-gray-500 italic">No floor images found.</p>
            @endforelse
        </div>

        <h2 class="text-2xl font-bold my-4 ">Facility Images</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
            @forelse ($facilityPaths as $path)
                <div class="bg-white rounded shadow">
                    <img src="{{ Storage::disk('s3')->url($path) }}" alt="Facility Image"
                        class="w-full h-48 object-cover rounded-lg">
                    {{-- <p class="text-xs text-center mt-1 px-2 break-words">{{ $path }}</p> --}}
                </div>
            @empty
                <p class="col-span-full text-gray-500 italic">No floor facility found.</p>
            @endforelse
        </div>
    </div>
@endsection
