@extends('layouts.app')

@section('content')
<section class="bg-white py-10">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<h1 class="text-3xl md:text-4xl font-extrabold text-blue-900">Pembayaran</h1>
		<p class="text-gray-600">Pembayaran Detail Gedung Serba Guna Universitas Lampung</p>

		<!-- Status menu (horizontal colored pills) -->
		@php
			$menu = [
				'' => ['label' => 'Belum dibayar', 'bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'active' => 'bg-blue-600 text-white'],
				'1' => ['label' => 'Proses', 'bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'active' => 'bg-yellow-600 text-white'],
				'2' => ['label' => 'Selesai', 'bg' => 'bg-green-50', 'text' => 'text-green-700', 'active' => 'bg-green-600 text-white'],
				'3' => ['label' => 'Dibatalkan', 'bg' => 'bg-red-50', 'text' => 'text-red-700', 'active' => 'bg-red-600 text-white'],
			];
		@endphp

		<div class="flex items-center gap-3 mt-6 flex-wrap">
			@foreach($menu as $key => $m)
				@php $isActive = ((string)($active ?? '') === (string)$key); @endphp
				<a href="{{ route('payments.index', ['status' => $key !== '' ? $key : null]) }}"
				   class="px-4 py-2 rounded-full text-sm font-medium border shadow-sm transition-colors {{ $isActive ? $m['active'] : ($m['bg'] . ' ' . $m['text']) }}">
					{{ $m['label'] }}
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


