@extends('layouts.app')

@section('content')
<section class="bg-gradient-to-b from-blue-50 to-white">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
		<!-- Breadcrumb -->
		<nav class="text-sm text-gray-500">
			<a href="{{ route('home') }}" class="hover:text-blue-700">Beranda</a>
			<span class="mx-2">/</span>
			<span class="text-blue-800 font-medium">Sewa Gedung</span>
		</nav>

		<div class="mt-4 grid lg:grid-cols-3 gap-8">
			<div class="lg:col-span-2">
				<!-- Title -->
				<div class="flex items-start justify-between gap-4">
					<div>
						<h1 class="text-3xl md:text-4xl font-extrabold text-blue-900">Gedung Serba Guna</h1>
						<p class="text-gray-600">Penyewaan Gedung Serba Guna Universitas Lampung</p>
					</div>
					<span class="hidden md:inline-flex items-center gap-2 bg-white border rounded-full px-4 py-2 shadow-sm">
						<i class="fa-solid fa-location-dot text-blue-700"></i>
						<span class="text-sm text-gray-700">Kampus Unila, Gedong Meneng</span>
					</span>
				</div>

				<!-- Gallery -->
				<div class="mt-5 grid md:grid-cols-3 gap-4">
					<div class="md:col-span-2 relative">
						<img src="{{ asset('img/gsgjauh.jpg') }}" alt="GSG Unila" class="w-full h-64 md:h-80 object-cover rounded-2xl shadow-md">
						<span class="absolute bottom-3 left-3 bg-white/90 text-gray-800 text-xs px-3 py-1 rounded-full shadow">
							Hall Utama • AC • Audio-Visual
						</span>
					</div>
					<div class="grid grid-rows-2 gap-4">
						<img src="{{ asset('img/wisuda.jpeg') }}" alt="Acara" class="w-full h-38 md:h-38 object-cover rounded-2xl shadow-md">
						<img src="{{ asset('img/tambahan.jpg') }}" alt="Acara lain" class="w-full h-38 md:h-38 object-cover rounded-2xl shadow-md">
					</div>
				</div>

				<!-- Features -->
				<div class="mt-6 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
					<div class="bg-white border rounded-xl p-4 flex items-start gap-3 shadow-sm">
						<span class="w-10 h-10 grid place-items-center rounded-lg bg-blue-100 text-blue-700">
							<i class="fa-solid fa-users"></i>
						</span>
						<div>
							<p class="font-semibold text-blue-900">Kapasitas Besar</p>
							<p class="text-sm text-gray-600">Cocok untuk wisuda, konser, seminar.</p>
						</div>
					</div>
					<div class="bg-white border rounded-xl p-4 flex items-start gap-3 shadow-sm">
						<span class="w-10 h-10 grid place-items-center rounded-lg bg-blue-100 text-blue-700">
							<i class="fa-solid fa-volume-high"></i>
						</span>
						<div>
							<p class="font-semibold text-blue-900">Audio-Visual</p>
							<p class="text-sm text-gray-600">Panggung, sound system, proyektor.</p>
						</div>
					</div>
					<div class="bg-white border rounded-xl p-4 flex items-start gap-3 shadow-sm">
						<span class="w-10 h-10 grid place-items-center rounded-lg bg-blue-100 text-blue-700">
							<i class="fa-solid fa-square-parking"></i>
						</span>
						<div>
							<p class="font-semibold text-blue-900">Parkir Luas</p>
							<p class="text-sm text-gray-600">Area parkir memadai.</p>
						</div>
					</div>
				</div>

				<!-- Description -->
				<div class="mt-6 bg-white rounded-2xl border p-6 leading-relaxed text-gray-700 shadow-sm">
					<h2 class="text-xl font-bold text-blue-900 mb-2">Tentang GSG Unila</h2>
					<p>
						Gedung Serba Guna Universitas Lampung (GSG Unila) adalah fasilitas utama kampus untuk berbagai kegiatan akademik dan non-akademik seperti wisuda, seminar, konser, penerimaan, serta acara mahasiswa. 
						Berlokasi di Kampus Unila, Gedong Meneng, gedung ini memiliki kapasitas besar dengan dukungan panggung, sistem audio-visual, AC, serta area parkir luas.
					</p>
					<p class="mt-3">
						GSG dapat digunakan untuk kegiatan internal universitas maupun pihak luar dengan izin resmi. Kenyamanan dan kelengkapan fasilitas menjadikan GSG salah satu ikon penting di lingkungan Universitas Lampung.
					</p>
				</div>
			</div>

			<!-- Sidebar CTA -->
			<aside class="bg-white border rounded-2xl p-6 h-max sticky top-24 shadow-sm">
				<h3 class="text-lg font-semibold text-gray-800">Mulai Sewa</h3>
				<p class="mt-1 text-3xl font-extrabold text-blue-600">100K <span class="text-gray-500 text-base font-medium">per Hari</span></p>
				<a href="{{ url('/booking/create') }}" class="mt-4 inline-block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 rounded-lg transition">Sewa Sekarang</a>
				<a href="{{ route('public.jadwal') }}" class="mt-2 inline-block w-full text-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 rounded-lg transition">Lihat Jadwal Booking</a>

				<div class="mt-6 border-t pt-4">
					<p class="text-sm font-medium text-gray-700 mb-2">Termasuk</p>
					<ul class="space-y-2 text-sm text-gray-600">
						<li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i> Kapasitas besar</li>
						<li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i> Sistem audio-visual</li>
						<li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i> Area parkir luas</li>
					</ul>
				</div>
			</aside>
		</div>
	</div>
</section>
@endsection


