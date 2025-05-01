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

<nav id="main-navbar" class="max-w-screen-xl bg-white border-b border-gray-200 sticky top-10 mx-auto transition-all z-50 rounded-2xl inter-base">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto lg:px-8 md:px-4">
        <a href="{{ url('/') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="self-center text-2xl whitespace-nowrap">Home</span>
        </a>
        <a href="{{ url('/') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="self-center text-2xl whitespace-nowrap">Booking</span>
        </a>
        <a href="{{ url('/') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="self-center text-4xl/7 whitespace-nowrap inika-bold text-center py-5">Asrama<br>Teknik</span>
        </a>
        <a href="{{ auth()->check() ? route('transactions.show', auth()->id()) : route('login') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="self-center text-2xl whitespace-nowrap">Transaction</span>
        </a>

        <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            @auth
                <button type="button" class="text-white bg-black hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-lg px-4 py-2 text-center flex gap-2"
                    id="user-menu-button" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                    <span class="sr-only">User</span>
                    <div
                        class="w-8 h-8 rounded-full text-white flex items-center justify-center font-semibold uppercase">
                        {{ strtoupper(auth()->user()->name[0]) }}
                    </div>
                </button>

                <!-- Dropdown -->
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow "
                    id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 ">{{ auth()->user()->name }}</span>
                        <span class="block text-sm text-gray-500 truncate">{{ auth()->user()->email }}</span>
                    </div>
                    <ul class="py-2">
                        <li>
                            <form action="{{ route('filament.admin.auth.logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Sign out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ url('/admin/login') }}"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center">
                    Login
                </a>
            @endauth
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.getElementById('main-navbar');
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 100) {
                navbar.classList.remove('top-10');
                navbar.classList.add('top-0');
            } else {
                navbar.classList.remove('top-0');
                navbar.classList.add('top-10');
            }
        });
    });
</script>