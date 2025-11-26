@extends('admin.layout')

@section('content')
<div class="flex items-center justify-between mb-4">
	<h1 class="text-2xl font-extrabold text-gray-800">Data Jadwal</h1>
	<a href="{{ route('admin.booking.create') }}" class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded">+ Tambah Jadwal</a>
</div>

@if(session('success'))
	<div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-xl shadow overflow-x-auto">
	<table class="min-w-full text-sm">
		<thead class="bg-gray-100 text-left">
			<tr>
				<th class="p-3">No</th>
				<th class="p-3">Tanggal</th>
				<th class="p-3">Acara</th>
				<th class="p-3">Status</th>
				<th class="p-3">Aksi</th>
			</tr>
		</thead>
		<tbody>
			@forelse($items as $i => $item)
			<tr class="border-t">
				<td class="p-3">{{ $i+1 }}</td>
				<td class="p-3">{{ $item->date }} • {{ $item->start_time }}-{{ $item->end_time }}</td>
				<td class="p-3">
					<div class="font-semibold text-blue-900">{{ $item->event_name }}</div>
					<div class="text-xs text-gray-600">Gedung: {{ $item->gedung->nama ?? '-' }}</div>
					<div class="text-xs text-gray-500 mt-1">Pemesan: {{ $item->user->name ?? '-' }}</div>
					<div class="text-xs text-gray-500 mt-1">Kontak: {{ $item->phone ?? ($item->user->phone ?? '-') }}</div>
					<div class="text-xs text-gray-500 mt-1">Proposal: @if($item->proposal_file) <a href="{{ asset('storage/'.$item->proposal_file) }}" target="_blank" class="text-blue-700 underline">Lihat</a> @else - @endif</div>
					<div class="text-xs text-gray-500 mt-1">Bukti Pembayaran: @php $pay = \App\Models\Payment::where('booking_id', $item->id)->first(); @endphp @if($pay && $pay->proof_file) <a href="{{ asset('storage/'.$pay->proof_file) }}" target="_blank" class="text-blue-700 underline">Lihat</a> @else - @endif</div>
					@if($item->bookingFasilitas && $item->bookingFasilitas->count() > 0)
					<div class="text-xs text-gray-500 mt-1">
						Fasilitas: {{ $item->bookingFasilitas->map(function($bf) { return $bf->fasilitas->nama . ' (' . $bf->jumlah . 'x)'; })->implode(', ') }}
					</div>
					@endif
				</td>
				<td class="p-3">
					@php $label=['1'=>'Menunggu','2'=>'Disetujui','3'=>'Ditolak','4'=>'Selesai'][$item->status] ?? $item->status; @endphp
					<span class="px-2 py-1 rounded text-white text-xs
						{{ $item->status==='2' ? 'bg-green-600' : ($item->status==='1' ? 'bg-yellow-600' : ($item->status==='3' ? 'bg-red-600' : 'bg-blue-600')) }}">
						{{ $label }}
					</span>
				</td>
				<td class="p-3">
					<div class="flex flex-wrap gap-2">
						<a href="{{ route('admin.booking.edit', $item->id) }}" class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-xs">Edit</a>
						<a href="{{ route('admin.booking.invoice', $item->id) }}" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs">Detail</a>
						@if($item->status === '1')
						<form action="{{ route('admin.booking.approve', $item->id) }}" method="POST" class="inline">
							@csrf @method('PUT')
							<button class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs">Setujui</button>
						</form>
						@endif
						<form action="{{ route('booking.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')" class="inline">
							@csrf @method('DELETE')
							<button class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs">Hapus</button>
						</form>
					</div>
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="5" class="p-6 text-center text-gray-500">Tidak ada jadwal.</td>
			</tr>
			@endforelse
		</tbody>
	</table>
</div>
@endsection


