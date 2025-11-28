<nav class="bg-white shadow-md px-6 py-3 w-full" style="position: relative; z-index: 999;">
    <div class="container mx-auto flex items-center justify-between">

        <!-- Logo -->
        <a href="/" class="text-2xl font-bold text-blue-900">
            BooGSG Unila
        </a>

        <!-- Jika belum login -->
        @guest
        <div class="flex items-center gap-3">
            <a href="{{ route('auth.login.form') }}"
               class="px-4 py-2 border border-blue-600 text-blue-600 rounded-md font-medium hover:bg-blue-50 transition">
                Masuk
            </a>

            <a href="{{ route('auth.register.form') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-md font-medium hover:bg-blue-700 transition">
                Daftar
            </a>
        </div>
        @endguest

        <!-- Jika sudah login -->
        @auth
        <div class="flex items-center gap-8">

            <!-- Menu -->
            <ul class="flex items-center gap-6 text-gray-700 font-medium">
                <li><a href="/" class="hover:text-blue-700">Beranda</a></li>
                <li><a href="/gedung" class="hover:text-blue-700">Sewa Gedung & Fasilitas</a></li>
                <li><a href="/jadwal" class="hover:text-blue-700">Jadwal</a></li>
                <li><a href="/booking" class="hover:text-blue-700">Booking</a></li>
                <li><a href="/pembayaran" class="hover:text-blue-700">Pembayaran</a></li>
                <li><a href="/tentang" class="hover:text-blue-700">Tentang</a></li>
            </ul>

            <!-- Profile + Dropdown -->
            <div class="relative">
                <button id="profileBtn" class="flex items-center gap-2 cursor-pointer select-none">
                    <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . Auth::user()->name }}" 
                         class="w-8 h-8 rounded-full border"
                    >
                    <span class="font-medium text-gray-800">{{ Auth::user()->name }}</span>
                </button>

                <!-- Dropdown -->
                <div id="dropdownMenu" 
                     class="hidden absolute right-0 mt-2 w-44 bg-white border rounded-md shadow-md py-2 z-50">

                    <a href="/profile"
                       class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        Pengaturan
                    </a>

                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                            Logout
                        </button>
                    </form>

                </div>
            </div>

        </div>
        @endauth

    </div>
</nav>

<script>
    // Toggle dropdown
    document.addEventListener("DOMContentLoaded", () => {
        const btn = document.getElementById("profileBtn");
        const menu = document.getElementById("dropdownMenu");

        if (btn) {
            btn.addEventListener("click", () => {
                menu.classList.toggle("hidden");
            });
        }

        // Klik di luar dropdown → tutup
        document.addEventListener("click", (e) => {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add("hidden");
            }
        });
    });
</script>