@extends('layouts.app')

@section('content')
<section class="bg-gradient-to-b from-blue-50 to-white">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
		<!-- Breadcrumb -->
		<nav class="text-sm text-gray-500">
			<a href="{{ route('home') }}" class="hover:text-blue-700">Beranda</a>
			<span class="mx-2">/</span>
			<span class="text-blue-800 font-medium">Fasilitas</span>
		</nav>
		<h1 class="mt-2 text-3xl md:text-4xl font-extrabold text-blue-900">Full Set Dekorasi Wisuda</h1>
		<p class="text-gray-600">Dekorasi Wisuda dengan Bunga, Meja, Kursi dan Karpet Merah</p>

		<div class="grid md:grid-cols-3 gap-8 mt-6">
			<div class="md:col-span-2 grid md:grid-cols-3 gap-4">
				<div class="md:col-span-3 relative">
					<img src="{{ asset('img/wisuda.jpg') }}" alt="Full Set Dekor Wisuda" class="w-full h-64 md:h-80 object-cover rounded-2xl shadow-md">
					<span class="absolute bottom-3 left-3 bg-white/90 text-gray-800 text-xs px-3 py-1 rounded-full shadow">Paket lengkap dekorasi</span>
				</div>
				<img src="{{ asset('img/konser.jpeg') }}" alt="Galeri 1" class="w-full h-40 object-cover rounded-2xl shadow-md">
				<img src="{{ asset('img/workshop.jpg') }}" alt="Galeri 2" class="w-full h-40 object-cover rounded-2xl shadow-md">
				<img src="{{ asset('img/wisuda.jpeg') }}" alt="Galeri 3" class="w-full h-40 object-cover rounded-2xl shadow-md">
			</div>

			<aside class="bg-white border rounded-2xl p-6 h-max sticky top-24 shadow-sm">
				<h3 class="text-lg font-semibold text-gray-800">Mulai Sewa</h3>
				<p class="mt-1 text-3xl font-extrabold text-blue-600">150K <span class="text-gray-500 text-base font-medium">per Hari</span></p>
				<a href="{{ url('/booking/create') }}" class="mt-4 inline-block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 rounded-lg transition">Sewa Sekarang</a>
				<a href="{{ route('public.jadwal') }}" class="mt-2 inline-block w-full text-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 rounded-lg transition">Lihat Jadwal Booking</a>
				
				<div class="mt-6 border-t pt-4">
					<p class="text-sm font-medium text-gray-700 mb-2">Termasuk</p>
					<ul class="space-y-2 text-sm text-gray-600">
						<li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i> Dekor bunga & backdrop</li>
						<li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i> Meja, kursi & karpet merah</li>
						<li class="flex items-center"><i class="fas fa-check text-green-600 mr-2"></i> Penataan panggung rapi</li>
					</ul>
				</div>
			</aside>
		</div>

		<div class="mt-8 bg-white rounded-2xl border p-6 leading-relaxed text-gray-700 shadow-sm">
			<h2 class="text-xl font-bold text-blue-900 mb-2">Informasi Penyewaan</h2>
			<p>
				Paket dekorasi lengkap untuk wisuda di Gedung Serba Guna (GSG) Unila. Paket menghadirkan suasana megah dan berkesan dengan perlengkapan seperti meja depan, sound system, TV proyektor, karpet merah, kursi tambahan, dan bunga-bunga dekoratif yang mempercantik area panggung serta ruangan.
			</p>
		</div>
	</div>
</section>
@endsection


