<nav class="bg-white shadow-md py-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold text-blue-700">BooGSG Unila</h1>

        <ul class="flex gap-4">
            <li><a href="/">Beranda</a></li>
            <li><a href="{{ route('public.sewa.gedung') }}">Sewa Gedung & Fasilitas</a></li>
            <li><a href="{{ route('public.jadwal') }}">Jadwal</a></li>
            <li><a href="{{ route('tentang') }}">Tentang</a></li>
        </ul>

        <div>
            <a href="{{ route('auth.login.form') }}" class="mx-3">Masuk</a>
            <a href="{{ route('auth.register.form') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Daftar</a>
        </div>
    </div>
</nav>