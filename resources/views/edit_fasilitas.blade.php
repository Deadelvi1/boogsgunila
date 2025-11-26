@extends('admin.layout')

@section('content')
<h1 class="text-2xl font-extrabold text-gray-800 mb-4">Edit Fasilitas</h1>

@if($errors->any())
	<div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
		<ul>
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

<div class="bg-white rounded-xl shadow p-6">
	<form method="POST" action="{{ route('fasilitas.update', $item->id) }}">
		@csrf
		@method('PUT')
		<div class="space-y-4">
			<div>
				<label class="block text-sm font-medium text-gray-700 mb-1">Nama Fasilitas <span class="text-red-600">*</span></label>
				<input type="text" name="nama" value="{{ old('nama', $item->nama) }}" required
					class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
			</div>
			<div>
				<label class="block text-sm font-medium text-gray-700 mb-1">Harga <span class="text-red-600">*</span></label>
				<input type="number" step="0.01" name="harga" value="{{ old('harga', $item->harga) }}" min="0" required
					class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
			</div>
			<div>
				<label class="block text-sm font-medium text-gray-700 mb-1">Stok <span class="text-red-600">*</span></label>
				<input type="number" name="stok" value="{{ old('stok', $item->stok) }}" min="0" required
					class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
			</div>
			<div>
				<label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
				<textarea name="deskripsi" rows="3"
					class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
					placeholder="Deskripsi fasilitas...">{{ old('deskripsi', $item->deskripsi) }}</textarea>
			</div>
			<div class="flex gap-2 pt-4">
				<button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
				<a href="{{ route('fasilitas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</a>
			</div>
		</div>
	</form>
</div>
@endsection


