<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js" defer></script>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen px-4">

    <div class="bg-white rounded-xl p-8 max-w-md w-full text-center">
        <div class="flex justify-center mb-4">

            <img src="{{ asset('logo/pt-inovasi.png') }}" alt="Logo Asrama Teknik" class="h-24">

        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Reset Password Terkirim</h1>
        <p class="text-gray-600 text-sm mb-6">
            Kami telah mengirimkan link reset password ke email Anda.<br>
            Silakan cek kotak masuk Anda dan ikuti petunjuknya.
        </p>

        <a href="{{ url('/') }}"
            class="inline-block bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold px-6 py-2 rounded-lg transition duration-300">
            Kembali ke Beranda
        </a>
    </div>

</body>

</html>
