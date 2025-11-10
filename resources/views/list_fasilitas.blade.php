@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Daftar Fasilitas</h1>
            <a href="{{ route('fasilitas.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah Fasilitas</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow rounded overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-3">Nama</th>
                        <th class="p-3">Harga</th>
                        <th class="p-3 w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr class="border-t">
                        <td class="p-3 font-medium">{{ $item->nama }}</td>
                        <td class="p-3">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="p-3">
                            <div class="flex gap-2">
                                <a href="{{ route('fasilitas.edit', $item->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</a>
                                <form action="{{ route('fasilitas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus fasilitas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-500">Belum ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


