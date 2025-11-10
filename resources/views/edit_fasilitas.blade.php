@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6">Edit Fasilitas</h1>
        <div class="bg-white shadow rounded p-6">
            <form action="{{ route('fasilitas.update', $item->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama', $item->nama) }}" class="mt-1 w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Harga</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $item->price) }}" class="mt-1 w-full border rounded px-3 py-2" min="0" required>
                </div>
                <div class="flex gap-2 pt-2">
                    <a href="{{ route('fasilitas.index') }}" class="px-4 py-2 border rounded">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


