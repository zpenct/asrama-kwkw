<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Ramtek - PT. Inovasi Benua Maritim')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js" defer></script>
</head>

<body class="bg-gray-50">
    <x-navbar />


    @yield('content')
    @yield('scripts')
    @stack('scripts')

    <x-footer />
</body>

</html>
