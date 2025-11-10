@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6">Edit Gedung</h1>
        <div class="bg-white shadow rounded p-6">
            <form action="{{ route('gedung.update', $item->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama', $item->nama) }}" class="mt-1 w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $item->lokasi) }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Kapasitas</label>
                    <input type="number" name="kapasitas" value="{{ old('kapasitas', $item->kapasitas) }}" class="mt-1 w-full border rounded px-3 py-2" min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium">Deskripsi</label>
                    <textarea name="deskripsi" class="mt-1 w-full border rounded px-3 py-2" rows="4">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                </div>
                <div class="flex gap-2 pt-2">
                    <a href="{{ route('gedung.index') }}" class="px-4 py-2 border rounded">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


