@extends('layouts.app')

@section('content')
<section class="bg-white py-10">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<h1 class="text-3xl md:text-4xl font-extrabold text-blue-900">Pembayaran</h1>
		<p class="text-gray-600">Pembayaran Detail Gedung Serba Guna Universitas Lampung</p>

		<!-- Tabs -->
		@php
			$tabs = [
				'' => ['label' => 'Belum dibayar', 'icon' => 'clock', 'color' => 'text-gray-500'],
				'1' => ['label' => 'Proses', 'icon' => 'spinner', 'color' => 'text-yellow-500'],
				'2' => ['label' => 'Selesai', 'icon' => 'check-circle', 'color' => 'text-green-600'],
				'3' => ['label' => 'Dibatalkan', 'icon' => 'xmark-circle', 'color' => 'text-red-600'],
			];
		@endphp
		<div class="flex items-center gap-6 mt-6">
			@foreach($tabs as $key => $tab)
				@php $isActive = (string)$active === (string)$key; @endphp
				<a href="{{ route('payments.index', ['status' => $key !== '' ? $key : null]) }}"
				   class="flex items-center gap-2 text-sm {{ $isActive ? 'font-bold text-blue-900' : 'text-gray-600' }}">
					<span class="{{ $tab['color'] }}">●</span> {{ $tab['label'] }}
				</a>
			@endforeach
		</div>

		<!-- Grid -->
		<div class="grid md:grid-cols-3 gap-6 mt-8">
			@forelse($payments as $p)
				@php
					$booking = $p->booking;
					$gedungName = $booking->gedung->nama ?? 'Sewa Gedung';
					$img = asset('img/gsgjauh.jpg');
				@endphp
				<div class="bg-white border rounded-2xl overflow-hidden">
					<img src="{{ $img }}" alt="gedung" class="w-full h-40 object-cover">
					<div class="p-4">
						<h3 class="font-bold text-blue-900">{{ $gedungName }}</h3>
						<p class="text-sm text-gray-600 mb-2">{{ $booking->event_name ?? 'Acara' }} • {{ $booking->date }}</p>
						<p class="text-lg font-extrabold text-gray-800 mb-3">Rp {{ number_format($p->amount,0,',','.') }}</p>

						@if($p->status === '0')
							<form method="POST" action="{{ route('payments.upload', $p->id) }}" enctype="multipart/form-data" class="space-y-2">
								@csrf
								<input type="file" name="proof" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm">
								<button class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-lg py-2">Lakukan Pembayaran</button>
								<p class="text-xs text-gray-500">Segera bayar sebelum 24 jam</p>
							</form>
						@elseif($p->status === '1')
							<p class="text-sm text-yellow-600 font-medium">Menunggu verifikasi</p>
						@elseif($p->status === '2')
							<p class="text-sm text-green-600 font-medium">Selesai</p>
						@else
							<p class="text-sm text-red-600 font-medium">Dibatalkan</p>
						@endif
					</div>
				</div>
			@empty
				<p class="text-gray-600">Belum ada data pembayaran.</p>
			@endforelse
		</div>
	</div>
</section>
@endsection


