@extends('admin.layout')

@section('content')
<div class="space-y-6">
	<h1 class="text-2xl font-extrabold text-gray-800">Dashboard</h1>

	@if(session('success'))
		<div class="bg-green-100 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
	@endif

	<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
		<div class="bg-white rounded-xl shadow p-4">
			<div class="text-xs text-gray-500">Pengguna</div>
			<div class="text-2xl font-extrabold text-blue-900">{{ $stats['users'] ?? '-' }}</div>
		</div>
		<div class="bg-white rounded-xl shadow p-4">
			<div class="text-xs text-gray-500">Jadwal</div>
			<div class="text-2xl font-extrabold text-blue-900">{{ $stats['bookings'] ?? '-' }}</div>
		</div>
		<div class="bg-white rounded-xl shadow p-4">
			<div class="text-xs text-gray-500">Pembayaran Pending</div>
			<div class="text-2xl font-extrabold text-blue-900">{{ $stats['payments_pending'] ?? '-' }}</div>
		</div>
		<div class="bg-white rounded-xl shadow p-4">
			<div class="text-xs text-gray-500">Sewa Aktif</div>
			<div class="text-2xl font-extrabold text-blue-900">{{ $stats['active_rentals'] ?? '-' }}</div>
		</div>
	</div>

	<div class="bg-white rounded-xl shadow p-6">
		<h2 class="text-lg font-semibold text-gray-800 mb-2">Ringkasan Terbaru</h2>
		<p class="text-sm text-gray-500">Tidak ada ringkasan untuk saat ini.</p>
	</div>
</div>
@endsection


