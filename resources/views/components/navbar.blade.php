<style>
    @import url('https://fonts.googleapis.com/css2?family=Inika:wght@400;700&display=swap');

    .inika-bold {
        font-family: "Inika", serif;
        font-weight: 700;
        font-style: normal;
    }

    @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');

    .inter-base {
        font-family: "Inter", sans-serif;
        font-weight: 400;
        font-style: normal;
    }
</style>

<nav id="main-navbar" class="sticky top-0 z-50 w-full border-b bg-white inter-base">
    <div class="flex justify-between items-center lg:px-8 px-2 md:px-4 py-3">
        <a href="{{ url('/') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="{{ asset('logo/pt-inovasi.png') }}" alt="Logo Asrama Teknik" class="h-12">
        </a>

        <div class="flex">
            <div class="hidden md:flex items-center space-x-6">

                @php
                    $navLinks = [['label' => 'Home', 'url' => url('/')]];

                    if (
                        auth()->check() &&
                        (auth()->user()->hasRole('admin') ||
                            auth()->user()->hasRole('super_admin') ||
                            auth()->user()->hasRole('superadmin'))
                    ) {
                        $navLinks[] = ['label' => 'Dashboard', 'url' => route('filament.admin.pages.dashboard')];
                    } else {
                        $navLinks[] = [
                            'label' => 'Booking',
                            'url' => auth()->check() ? route('transactions.show') : route('login'),
                        ];
                    }

                    $navLinks[] = ['label' => 'Gallery', 'url' => url('/gallery')];
                @endphp


                @foreach ($navLinks as $link)
                    <a href="{{ $link['url'] }}" class="flex items-center">
                        <span
                            class="relative inline-block text-sm font-medium text-gray-700 transition-all duration-200 ease-in-out group hover:scale-105">
                            {{ $link['label'] }}
                            <span
                                class="absolute left-0 bottom-0 h-0.5 w-0 bg-blue-500 transition-all duration-300 ease-in-out group-hover:w-full"></span>
                        </span>
                    </a>
                @endforeach
            </div>

            <div class="flex items-center space-x-3 md:space-x-4">
                <button id="menu-toggle" class="md:hidden text-gray-700 focus:outline-none z-50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                @auth
                    <div class="hidden md:block relative">
                        <button type="button" id="user-menu-button-desktop"
                            class="text-blue-600 border-2 border-blue-600 hover:bg-blue-500 hover:text-white hover:border-0 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm w-10 h-10 flex items-center justify-center transition-all">
                            <div
                                class="w-8 h-8 rounded-full text-black flex items-center justify-center font-semibold uppercase">
                                {{ strtoupper(auth()->user()->name[0]) }}

                            </div>
                        </button>
                        <div id="user-dropdown-desktop"
                            class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                            <div class="px-4 py-3">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="border-t">
                                <form action="{{ route('filament.admin.auth.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="hidden md:block relative">
                        <a href="{{ url('/admin/login') }}"
                            class="text-blue-600 border-2 border-blue-600 hover:bg-blue-500 hover:text-white hover:border-0 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-20 h-10 flex items-center justify-center transition-all">
                            Masuk
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <div id="mobile-menu"
        class="md:hidden fixed inset-0 bg-white z-40 flex flex-col justify-start pt-6 px-6 space-y-5 overflow-auto transition-all duration-300 transform scale-y-0 origin-top">
        @foreach ($navLinks as $link)
            <a href="{{ $link['url'] }}" class="text-base font-medium text-gray-700 hover:text-blue-600">
                {{ $link['label'] }}
            </a>
        @endforeach

        <div class="absolute bottom-4 left-0 right-0 px-6">
            <div class="relative w-full flex justify-center">
                @auth
                    <div id="user-dropdown-mobile"
                        class="hidden absolute bottom-14 w-full max-w-xs mx-auto bg-white border border-gray-200 rounded-lg shadow z-50">
                        <div class="px-4 py-3 text-center">
                            <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="border-t">
                            <form action="{{ route('filament.admin.auth.logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>

                    <button id="user-button-mobile"
                        class="w-full h-12 rounded-lg border-2 border-blue-600 text-blue-600 flex items-center justify-center font-semibold uppercase hover:bg-blue-500 hover:text-white transition-all">
                        {{ strtoupper(auth()->user()->name[0]) }}
                    </button>
                @else
                    <a href="{{ url('/admin/login') }}"
                        class="text-blue-600 border-2 border-blue-600 hover:bg-blue-500 hover:text-white hover:border-0 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full h-10 flex items-center justify-center transition-all">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            const userBtnMobile = document.getElementById('user-button-mobile');
            const dropdownMobile = document.getElementById('user-dropdown-mobile');
            const userBtnDesktop = document.getElementById('user-menu-button-desktop');
            const dropdownDesktop = document.getElementById('user-dropdown-desktop');

            if (menuToggle && mobileMenu) {
                menuToggle.addEventListener('click', () => {
                    if (mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.remove('hidden');
                        requestAnimationFrame(() => {
                            mobileMenu.classList.remove('scale-y-0');
                            mobileMenu.classList.add('scale-y-100');
                        });
                    } else {
                        mobileMenu.classList.remove('scale-y-100');
                        mobileMenu.classList.add('scale-y-0');

                        setTimeout(() => {
                            mobileMenu.classList.add('hidden');
                        }, 300);
                    }

                    if (dropdownMobile && !dropdownMobile.classList.contains('hidden')) {
                        dropdownMobile.classList.add('hidden');
                    }
                });
            }

            if (userBtnMobile && dropdownMobile) {
                userBtnMobile.addEventListener('click', (e) => {
                    e.stopPropagation();
                    dropdownMobile.classList.toggle('hidden');
                });

                dropdownMobile.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
            }

            if (userBtnDesktop && dropdownDesktop) {
                userBtnDesktop.addEventListener('click', (e) => {
                    e.stopPropagation();
                    dropdownDesktop.classList.toggle('hidden');
                });

                dropdownDesktop.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
            }

            document.addEventListener('click', () => {
                if (dropdownMobile && !dropdownMobile.classList.contains('hidden')) {
                    dropdownMobile.classList.add('hidden');
                }
                if (dropdownDesktop && !dropdownDesktop.classList.contains('hidden')) {
                    dropdownDesktop.classList.add('hidden');
                }
            });
        });
    </script>
</nav>


<!-- Floating WhatsApp Button -->
<a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
    class="fixed bottom-6 right-6 bg-green-500 hover:bg-green-600 text-white p-3 rounded-full shadow-lg z-50 transition-all duration-300"
    aria-label="Contact via WhatsApp">
    <img src="{{ asset('img/Wa.svg') }}" alt="Twitter" class="h-6 w-6 hover:opacity-80 transition">
</a>
