@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Daftar Booking Gedung</h1>
            <a href="{{ route('booking.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Buat Booking</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
        @endif
        
        @if(session('warning'))
            <div class="bg-yellow-100 text-yellow-700 px-4 py-3 rounded mb-4">{{ session('warning') }}</div>
        @endif

        <div class="bg-white shadow rounded overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-3">Pemesan</th>
                        <th class="p-3">Gedung</th>
                        <th class="p-3">Acara</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Waktu</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 w-56">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr class="border-top">
                        <td class="p-3">{{ $item->user->name ?? '-' }}</td>
                        <td class="p-3">{{ $item->gedung->nama ?? '-' }}</td>
                        <td class="p-3">{{ $item->event_name }} ({{ $item->event_type }})</td>
                        <td class="p-3">
                            @if($item->end_date && $item->end_date !== $item->date)
                                {{ $item->date }} - {{ $item->end_date }}
                            @else
                                {{ $item->date }}
                            @endif
                        </td>
                        <td class="p-3">{{ $item->start_time }} - {{ $item->end_time }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-white 
                                @if($item->status==='1') bg-yellow-500 @elseif($item->status==='2') bg-green-600 @elseif($item->status==='3') bg-red-600 @else bg-gray-600 @endif">
                                @php($labels=['1'=>'Menunggu','2'=>'Disetujui','3'=>'Ditolak','4'=>'Selesai'])
                                {{ $labels[$item->status] ?? $item->status }}
                            </span>
                        </td>
                        <td class="p-3 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('booking.invoice', $item->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded text-xs">Invoice</a>
                                <a href="{{ route('booking.edit', $item->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded text-xs">Edit</a>
                                <form action="{{ route('booking.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus booking ini?')" class="inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded text-xs">Hapus</button>
                                </form>
                                @if(auth()->check() && auth()->user()->id === $item->user_id && $item->status !== '4')
                                <form action="{{ route('booking.cancel', $item->id) }}" method="POST" onsubmit="return confirm('Batalkan booking ini?')" class="inline-flex">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="px-3 py-1 bg-red-400 text-white rounded text-xs">Batal</button>
                                </form>
                                @endif
                                @if(auth()->check() && auth()->user()->role === 'A')
                                <form action="{{ route('admin.booking.approve', $item->id) }}" method="POST" class="inline-flex">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded text-xs">Approve</button>
                                </form>
                                <form action="{{ route('admin.booking.reject', $item->id) }}" method="POST" class="inline-flex">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="px-3 py-1 bg-gray-600 text-white rounded text-xs">Reject</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">Belum ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


