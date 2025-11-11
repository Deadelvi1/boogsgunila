@extends('admin.layout')

@section('content')
<h1 class="text-2xl font-extrabold text-gray-800 mb-4">Verifikasi Pembayaran</h1>
@if(session('success'))
	<div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-xl shadow overflow-x-auto">
	<table class="min-w-full text-sm">
		<thead class="bg-gray-100 text-left">
			<tr>
				<th class="p-3">Pemesan</th>
				<th class="p-3">Acara</th>
				<th class="p-3">Tanggal</th>
				<th class="p-3">Jumlah</th>
				<th class="p-3">Status</th>
				<th class="p-3">Bukti</th>
				<th class="p-3 w-60">Aksi</th>
			</tr>
		</thead>
		<tbody>
			@forelse($payments as $p)
				<tr class="border-t">
					<td class="p-3">{{ $p->booking->user->name ?? '-' }}</td>
					<td class="p-3">{{ $p->booking->event_name ?? '-' }}</td>
					<td class="p-3">{{ $p->booking->date ?? '-' }}</td>
					<td class="p-3">Rp {{ number_format($p->amount,0,',','.') }}</td>
					<td class="p-3">
						<span class="px-2 py-1 rounded text-white
							@switch($p->status)
								@case('0') bg-gray-500 @break
								@case('1') bg-yellow-600 @break
								@case('2') bg-green-600 @break
								@case('3') bg-red-600 @break
								@default bg-gray-500
							@endswitch">
							{{ ['0'=>'Belum','1'=>'Proses','2'=>'Selesai','3'=>'Batal'][$p->status] ?? $p->status }}
						</span>
					</td>
					<td class="p-3">
						@if($p->proof_file)
							<a href="{{ asset('storage/'.$p->proof_file) }}" target="_blank" class="text-blue-700 underline">Lihat</a>
						@else
							<span class="text-gray-500">-</span>
						@endif
					</td>
					<td class="p-3">
						<div class="flex flex-wrap gap-2">
							<form method="POST" action="{{ route('admin.payments.status', $p->id) }}">
								@csrf @method('PUT')
								<input type="hidden" name="status" value="2">
								<button class="px-3 py-1 bg-green-600 text-white rounded">Tandai Selesai</button>
							</form>
							<form method="POST" action="{{ route('admin.payments.status', $p->id) }}">
								@csrf @method('PUT')
								<input type="hidden" name="status" value="3">
								<button class="px-3 py-1 bg-red-600 text-white rounded">Batalkan</button>
							</form>
						</div>
					</td>
				</tr>
			@empty
				<tr>
					<td colspan="7" class="p-6 text-center text-gray-500">Tidak ada pembayaran.</td>
				</tr>
			@endforelse
		</tbody>
	</table>
</div>
@endsection


