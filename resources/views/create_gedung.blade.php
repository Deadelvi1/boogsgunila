@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6">Tambah Gedung</h1>
        <div class="bg-white shadow rounded p-6">
            <form action="{{ route('gedung.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="mt-1 w-full border rounded px-3 py-2" required>
                    @error('nama')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Kapasitas</label>
                    <input type="number" name="kapasitas" value="{{ old('kapasitas') }}" class="mt-1 w-full border rounded px-3 py-2" min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium">Deskripsi</label>
                    <textarea name="deskripsi" class="mt-1 w-full border rounded px-3 py-2" rows="4">{{ old('deskripsi') }}</textarea>
                </div>
                <div class="flex gap-2 pt-2">
                    <a href="{{ route('gedung.index') }}" class="px-4 py-2 border rounded">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


