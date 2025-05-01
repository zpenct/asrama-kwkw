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
    <div class="max-w-screen-xl text-white mx-auto flex justify-between items-end py-10">
        <div class="flex flex-col gap-10">
            <p class=" text-4xl">Kritik dan Saran</p>
            <div class="flex">
                <textarea name="" id="" placeholder="Tulis Disini" class=" rounded-l-md text-center content-center italic w-96"></textarea>
                <a href="" class="bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold py-4 px-4 rounded-r-md text-center">
                    Kirim
                </a>
            </div>
        </div>
        <div class="flex flex-col gap-10">
            <a href="{{ url('/') }}" class="flex items-center space-x-3 rtl:space-x-reverse justify-center">
                <span class="self-center text-4xl/7 whitespace-nowrap inika-bold text-center py-5">Asrama<br>Teknik</span>
            </a>
            <div class="flex gap-14 text-sm text-white">
                <ul class="space-y-2">
                    <li><a href="https://inomarunhas.com/divisi-asrama-teknik-ramtek/" class="hover:text-blue-600 transition duration-300">About Us</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition duration-300">Contact</a></li>
                </ul>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-blue-600 transition duration-300">Services & Facilities</a></li>
                    <li><a href="#" class="hover:text-blue-600 transition duration-300">How to book</a></li>
                </ul>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-blue-600 transition duration-300">Careers</a></li>
                    <li><a href="https://maps.app.goo.gl/c3xjFJB7w5Ldn86VA" class="hover:text-blue-600 transition duration-300">Location</a></li>
                </ul>
            </div>
        </div>
    </div>
    <p class="text-white text-center pt-20 pb-5">© Copyright Asrama Teknik. All right reserved.</p>
</footer>