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

<footer class="w-full bg-[#1E1E1E] inter-base">
    <div
        class="max-w-screen-xl text-white mx-auto flex flex-col md:flex-row justify-between items-start gap-y-10 py-10 px-4">
        {{-- Kritik dan Saran --}}
        <div class="flex flex-col gap-6 w-full">
            <p class="text-2xl font-bold">Kritik dan Saran</p>
            <div class="flex flex-col sm:flex-row w-full md:max-w-[400px]">
                <input placeholder="Tulis Disini"
                    class="w-full sm:h-auto text-center italic rounded-t-md sm:rounded-t-none sm:rounded-l-md p-3 text-black" />
                <a href="#"
                    class="bg-blue-600 hover:bg-blue-700 text-white py-2 text-center md:py-4 md:px-6 rounded-b-md sm:rounded-b-none sm:rounded-r-md">
                    Kirim
                </a>
            </div>
        </div>

        {{-- Info & Links --}}
        <div class="flex flex-col gap-6 w-full">
            <a href="{{ url('/') }}" class="flex justify-center md:justify-start items-center">
                <span class="text-2xl text-center md:text-left font-bold leading-tight">Asrama Teknik</span>
            </a>

            <div class="flex flex-col sm:flex-row sm:justify-between gap-y-6 text-sm text-white">
                <ul class="space-y-2 text-center sm:text-left">
                    <li><a href="https://inomarunhas.com/divisi-asrama-teknik-ramtek/"
                            class="hover:text-blue-600 transition duration-300">About Us</a></li>
                    <li><a href="#" class="hover:text-blue-600 transition duration-300">Contact</a></li>
                </ul>
                <ul class="space-y-2 text-center sm:text-left">
                    <li><a href="#" class="hover:text-blue-600 transition duration-300">Services & Facilities</a>
                    </li>
                    <li><a href="#" class="hover:text-blue-600 transition duration-300">How to book</a></li>
                </ul>
                <ul class="space-y-2 text-center sm:text-left">
                    <li><a href="#" class="hover:text-blue-600 transition duration-300">Careers</a></li>
                    <li><a href="https://maps.app.goo.gl/c3xjFJB7w5Ldn86VA"
                            class="hover:text-blue-600 transition duration-300">Location</a></li>
                </ul>
            </div>
        </div>
    </div>

    <p class="text-white text-center pt-10 pb-6 text-sm">© Copyright Asrama Teknik. All rights reserved.</p>
</footer>
