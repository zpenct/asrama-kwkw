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

<footer class="w-full bg-[#1E1E1E] py-8 text-white">
    <div class="max-w-screen-xl mx-auto px-6 md:px-8 grid grid-cols-1 md:grid-cols-3 gap-10">
        <div class="md:col-span-2 order-1 md:order-none">
        <h3 class="text-lg font-semibold mb-4">Kritik & Saran</h3>
        <form class="">
            <textarea
            id="feedback"
            rows="4"
            placeholder="Tulis kritik atau saran Anda di sini..."
            class="w-full p-3 text-sm text-black bg-white border border-gray-600 rounded-md resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            ></textarea>
            <button
            type="submit"
            class="mt-4 bg-blue-600 hover:bg-blue-700 text-white text-sm px-6 py-2 rounded-md transition duration-300"
            >
            Kirim Masukan
            </button>
        </form>
    </div>

    <div class="order-2 md:order-none">
        <a href="{{ url('/') }}" class="text-3xl font-bold text-white mb-4 inline-block">
            Asrama Teknik - Unofficial ya ges pliss
        </a>
        <p class="text-sm text-gray-400 leading-relaxed mt-2">
            Tempat tinggal mahasiswa yang nyaman dan terjangkau, mendukung pengembangan akademik, sosial, dan organisasi di lingkungan Universitas Hasanuddin.
        </p>
        </div>
    </div>

    <div class="border-t border-gray-700 mt-10 pt-6 w-full">
        <div class="max-w-screen-xl mx-auto px-6 md:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <p class="text-sm text-gray-400 text-center md:text-left w-full md:w-auto">
            © 2025 Asrama Teknik. All rights reserved.
        </p>
        <div class="flex gap-5">
            <a href="{{ url('/') }}">
            <img src="{{ asset('img/Facebook.png') }}" alt="Facebook" class="h-6 w-6 hover:opacity-80 transition">
            </a>
            <a href="{{ url('/') }}">
            <img src="{{ asset('img/Instagram.png') }}" alt="Instagram" class="h-6 w-6 hover:opacity-80 transition">
            </a>
            <a href="{{ url('/') }}">
            <img src="{{ asset('img/Twitter.png') }}" alt="Twitter" class="h-6 w-6 hover:opacity-80 transition">
            </a>
        </div>
        </div>
    </div>
</footer>

