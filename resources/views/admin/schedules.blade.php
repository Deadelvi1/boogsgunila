@extends('admin.layout')

@section('content')
<div class="flex items-center justify-between mb-4">
	<h1 class="text-2xl font-extrabold text-gray-800">Data Jadwal</h1>
	<a href="{{ route('booking.index') }}" class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded">+ Tambah Jadwal</a>
</div>

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
			@foreach($items as $i => $item)
			<tr class="border-t">
				<td class="p-3">{{ $i+1 }}</td>
				<td class="p-3">{{ $item->date }} • {{ $item->start_time }}-{{ $item->end_time }}</td>
				<td class="p-3">
					<div class="font-semibold text-blue-900">{{ $item->event_name }}</div>
					<div class="text-xs text-gray-600">{{ $item->gedung->nama ?? '-' }}</div>
				</td>
				<td class="p-3">
					@php $label=['1'=>'Proses','2'=>'Selesai','3'=>'Batal','4'=>'Aktif'][$item->status] ?? $item->status; @endphp
					<span class="px-2 py-1 rounded text-white text-xs
						{{ $item->status==='2' ? 'bg-green-600' : ($item->status==='1' ? 'bg-yellow-600' : ($item->status==='3' ? 'bg-red-600' : 'bg-blue-600')) }}">
						{{ $label }}
					</span>
				</td>
				<td class="p-3">
					<div class="flex gap-2">
						<a href="{{ route('booking.edit', $item->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</a>
						<form action="{{ route('booking.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
							@csrf @method('DELETE')
							<button class="px-3 py-1 bg-red-600 text-white rounded">Hapus</button>
						</form>
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
@endsection


