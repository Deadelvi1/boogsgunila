@extends('admin.layout')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold">Edit Jadwal (Admin)</h1>
    <p class="text-sm text-gray-600">Perbarui data booking dan status</p>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <form action="{{ route('admin.booking.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Pilih Pemesan (User)</label>
                <select name="user_id" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Pemesan --</option>
                    @foreach($gedung as $g) @break @endforeach
                    @foreach(\App\Models\User::orderBy('name')->get() as $u)
                        <option value="{{ $u->id }}" {{ ($item->user_id == $u->id) ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Pilih Gedung</label>
                <select name="gedung_id" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Gedung --</option>
                    @foreach($gedung as $g)
                        <option value="{{ $g->id }}" {{ ($item->gedung_id == $g->id) ? 'selected' : '' }}>{{ $g->nama }} @if($g->lokasi) ({{ $g->lokasi }}) @endif</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Nama Acara</label>
                <input type="text" name="event_name" value="{{ $item->event_name }}" class="mt-1 w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Jenis Acara</label>
                <input type="text" name="event_type" value="{{ $item->event_type }}" class="mt-1 w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium">Kapasitas</label>
                <input type="number" name="capacity" min="1" value="{{ $item->capacity }}" class="mt-1 w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Nomor Telepon Pemesan</label>
                <input type="text" name="phone" value="{{ $item->phone ?? ($item->user->phone ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Tanggal Mulai</label>
                <input type="date" name="date" value="{{ $item->date }}" class="mt-1 w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Tanggal Selesai (opsional)</label>
                <input type="date" name="end_date" value="{{ $item->end_date }}" class="mt-1 w-full border rounded px-3 py-2">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-sm font-medium">Jam Mulai</label>
                    <input type="time" name="start_time" value="{{ $item->start_time }}" class="mt-1 w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Jam Selesai</label>
                    <input type="time" name="end_time" value="{{ $item->end_time }}" class="mt-1 w-full border rounded px-3 py-2" required>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Status</label>
            <select name="status" class="mt-1 w-full border rounded px-3 py-2">
                <option value="1" {{ $item->status==='1' ? 'selected' : '' }}>Menunggu</option>
                <option value="2" {{ $item->status==='2' ? 'selected' : '' }}>Disetujui</option>
                <option value="3" {{ $item->status==='3' ? 'selected' : '' }}>Ditolak</option>
                <option value="4" {{ $item->status==='4' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium">Proposal Saat Ini</label>
            @if($item->proposal_file)
                <div class="mt-1">
                    <a href="{{ asset('storage/'.$item->proposal_file) }}" target="_blank" class="text-blue-700 underline">Lihat proposal</a>
                </div>
            @else
                <div class="text-gray-500">Tidak ada proposal</div>
            @endif
            <div class="mt-2">
                <label class="block text-sm font-medium">Unggah Proposal Baru (opsional)</label>
                <input type="file" name="proposal_file" accept=".pdf,.doc,.docx" class="mt-1 w-full">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Fasilitas (opsional)</label>
            <div class="mt-2 space-y-2">
                @foreach($fasilitas as $f)
                    @php
                        $bf = $item->bookingFasilitas->firstWhere('fasilitas_id', $f->id);
                    @endphp
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="fasilitas[][id]" value="{{ $f->id }}" {{ $bf ? 'checked' : '' }}>
                        <div class="flex-1">
                            <label class="text-sm font-medium">{{ $f->nama }} (Rp {{ number_format($f->harga ?? 0) }})</label>
                        </div>
                        <div>
                            <input type="number" name="fasilitas[][jumlah]" min="1" value="{{ $bf->jumlah ?? 1 }}" class="w-20 border rounded px-2 py-1">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex gap-2 pt-3">
            <a href="{{ route('admin.schedules.index') }}" class="px-4 py-2 border rounded">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan Perubahan</button>
        </div>
    </form>
</div>

@endsection
