<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
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


</body>

</html>
