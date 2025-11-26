@extends('admin.layout')

@section('content')
<div class="flex items-center justify-between mb-4">
	<h1 class="text-2xl font-extrabold text-gray-800">Data Gedung</h1>
	<a href="{{ route('gedung.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded">+ Tambah Gedung</a>
</div>

@if(session('success'))
	<div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-xl shadow overflow-x-auto">
	<table class="min-w-full text-sm">
		<thead class="bg-gray-100 text-left">
			<tr>
				<th class="p-3">No</th>
				<th class="p-3">Nama</th>
				<th class="p-3">Lokasi</th>
				<th class="p-3">Kapasitas</th>
				<th class="p-3">Deskripsi</th>
				<th class="p-3 w-40">Aksi</th>
			</tr>
		</thead>
		<tbody>
			@forelse($items as $i => $item)
			<tr class="border-t">
				<td class="p-3">{{ $i+1 }}</td>
				<td class="p-3 font-medium">{{ $item->nama }}</td>
				<td class="p-3 text-gray-600">{{ $item->lokasi ?? '-' }}</td>
				<td class="p-3">{{ number_format($item->kapasitas ?? 0, 0, ',', '.') }} orang</td>
				<td class="p-3 text-gray-500 text-xs">{{ Str::limit($item->deskripsi ?? '-', 50) }}</td>
				<td class="p-3">
					<div class="flex gap-2">
						<a href="{{ route('gedung.edit', $item->id) }}" class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-xs">Edit</a>
						<form action="{{ route('gedung.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus gedung ini?')" class="inline">
							@csrf @method('DELETE')
							<button class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs">Hapus</button>
						</form>
					</div>
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="6" class="p-6 text-center text-gray-500">Belum ada data gedung.</td>
			</tr>
			@endforelse
		</tbody>
	</table>
</div>
@endsection


