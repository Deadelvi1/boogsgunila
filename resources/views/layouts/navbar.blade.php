<nav class="bg-white shadow-md sticky top-0 z-50" x-data="{ mobileMenu: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          
            <div class="flex items-center">
                <a href="/" class="flex items-center space-x-2 text-blue-900">
                    <span class="font-bold text-xl">BooGSG</span>
                    <span class="font-normal text-xl">Unila</span>
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-6">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-900 px-3 py-2 text-sm font-medium transition duration-300">Beranda</a>
                    <a href="{{ route('dashboard') }}#fasilitas" class="text-gray-700 hover:text-blue-900 px-3 py-2 text-sm font-medium transition duration-300">Sewa</a>
                    <a href="{{ route('dashboard') }}#jadwal" class="text-gray-700 hover:text-blue-900 px-3 py-2 text-sm font-medium transition duration-300">Jadwal</a>
                    <a href="#tentang" class="text-gray-700 hover:text-blue-900 px-3 py-2 text-sm font-medium transition duration-300">Tentang</a>
                @else
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-900 px-3 py-2 text-sm font-medium transition duration-300">Beranda</a>
                    <a href="{{ route('home') }}#sewa" class="text-gray-700 hover:text-blue-900 px-3 py-2 text-sm font-medium transition duration-300">Sewa</a>
                    <a href="{{ route('home') }}#jadwal" class="text-gray-700 hover:text-blue-900 px-3 py-2 text-sm font-medium transition duration-300">Jadwal</a>
                    <a href="{{ route('home') }}#tentang" class="text-gray-700 hover:text-blue-900 px-3 py-2 text-sm font-medium transition duration-300">Tentang</a>
                @endauth
                @auth
                    @if(auth()->user()->role === 'A')
                        <a href="{{ route('gedung.index') }}" 
                           class="text-gray-700 hover:text-blue-900 px-3 py-2 text-sm font-medium transition duration-300">
                            Gedung
                        </a>
                        <a href="{{ route('fasilitas.index') }}" 
                           class="text-gray-700 hover:text-blue-900 px-3 py-2 text-sm font-medium transition duration-300">
                            Fasilitas
                        </a>
                    @endif
                    <a href="{{ route('booking.index') }}" 
                       class="text-gray-700 hover:text-blue-900 px-3 py-2 text-sm font-medium transition duration-300">
                        Booking
                    </a>
                @endauth
            </div>

            <div class="flex items-center space-x-3">
                @auth
                    <div class="relative" x-data="{ open: false }" @keydown.escape.stop="open = false" @click.away="open = false">
                        <button type="button" 
                                class="flex items-center space-x-2 text-gray-700 focus:outline-none" 
                                @click="open = !open"
                                id="user-menu-button" 
                                aria-expanded="false" 
                                aria-haspopup="true">
                            <img src="{{ auth()->user()->profile_photo_url ?? asset('img/dea.png') }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="h-8 w-8 rounded-full border-2 border-gray-300 object-cover" 
                                 onerror="this.src='{{ asset('img/default-avatar.png') }}'">
                            <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             x-cloak
                             class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                             role="menu"
                             aria-orientation="vertical"
                             aria-labelledby="user-menu-button"
                             tabindex="-1">
                            <a href="{{ route('profile') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                               role="menuitem" 
                               tabindex="-1">
                                Profile
                            </a>
                            <form id="logout-form" method="POST" action="{{ route('auth.logout') }}" class="hidden">
                                @csrf
                            </form>
                            <button type="button"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem"
                                    tabindex="-1">
                                Logout
                            </button>
                        </div>
                    </div>
                @else
                    <a href="{{ route('auth.login.form') }}" class="bg-blue-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-300 hover:bg-blue-800">Masuk</a>
                    <a href="{{ route('auth.register.form') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-300 hover:bg-blue-600">Daftar</a>
                @endauth
                
                <button class="md:hidden text-gray-700 hover:text-blue-900 focus:outline-none" @click="mobileMenu = !mobileMenu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="md:hidden" x-show="mobileMenu" @click.away="mobileMenu = false">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gray-50 rounded-lg mt-2 border border-gray-200">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-900 block px-3 py-2 rounded-md text-base font-medium transition duration-300">Beranda</a>
                    <a href="{{ route('dashboard') }}#fasilitas" class="text-gray-700 hover:text-blue-900 block px-3 py-2 rounded-md text-base font-medium transition duration-300">Sewa</a>
                    <a href="{{ route('dashboard') }}#jadwal" class="text-gray-700 hover:text-blue-900 block px-3 py-2 rounded-md text-base font-medium transition duration-300">Jadwal</a>
                    <a href="#tentang" class="text-gray-700 hover:text-blue-900 block px-3 py-2 rounded-md text-base font-medium transition duration-300">Tentang</a>
                @else
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-900 block px-3 py-2 rounded-md text-base font-medium transition duration-300">Beranda</a>
                    <a href="{{ route('home') }}#sewa" class="text-gray-700 hover:text-blue-900 block px-3 py-2 rounded-md text-base font-medium transition duration-300">Sewa</a>
                    <a href="{{ route('home') }}#jadwal" class="text-gray-700 hover:text-blue-900 block px-3 py-2 rounded-md text-base font-medium transition duration-300">Jadwal</a>
                    <a href="{{ route('home') }}#tentang" class="text-gray-700 hover:text-blue-900 block px-3 py-2 rounded-md text-base font-medium transition duration-300">Tentang</a>
                @endauth
                @auth
                    @if(auth()->user()->role === 'A')
                        <a href="{{ route('gedung.index') }}" 
                           class="text-gray-700 hover:text-blue-900 block px-3 py-2 rounded-md text-base font-medium transition duration-300">
                            Gedung
                        </a>
                        <a href="{{ route('fasilitas.index') }}" 
                           class="text-gray-700 hover:text-blue-900 block px-3 py-2 rounded-md text-base font-medium transition duration-300">
                            Fasilitas
                        </a>
                    @endif
                    <a href="{{ route('booking.index') }}" 
                       class="text-gray-700 hover:text-blue-900 block px-3 py-2 rounded-md text-base font-medium transition duration-300">
                        Booking
                    </a>
                    <hr class="border-gray-300">
                    <a href="{{ route('profile') }}" 
                       class="text-gray-700 hover:text-blue-900 block px-3 py-2 rounded-md text-base font-medium transition duration-300">
                        Profile
                    </a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                       class="text-gray-700 hover:text-blue-900 block w-full text-left px-3 py-2 rounded-md text-base font-medium transition duration-300">
                        Logout
                    </a>
                @else
                    <a href="{{ route('auth.login.form') }}" 
                       class="text-gray-700 hover:text-blue-900 block px-3 py-2 rounded-md text-base font-medium transition duration-300">
                        Masuk
                    </a>
                    <a href="{{ route('auth.register.form') }}" 
                       class="text-gray-700 hover:text-blue-900 block px-3 py-2 rounded-md text-base font-medium transition duration-300">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>