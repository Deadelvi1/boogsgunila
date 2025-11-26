@extends('admin.layout')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold">Tambah Jadwal (Admin)</h1>
    <p class="text-sm text-gray-600">Buat jadwal booking atas nama pengguna</p>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <form action="{{ route('admin.booking.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Pilih Pemesan (User)</label>
                <select name="user_id" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Pemesan --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Pilih Gedung</label>
                <select name="gedung_id" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Gedung --</option>
                    @foreach($gedung as $g)
                        <option value="{{ $g->id }}">{{ $g->nama }} @if($g->lokasi) ({{ $g->lokasi }}) @endif</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Nama Acara</label>
                <input type="text" name="event_name" class="mt-1 w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Jenis Acara</label>
                <input type="text" name="event_type" class="mt-1 w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium">Kapasitas</label>
                <input type="number" name="capacity" min="1" class="mt-1 w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Nomor Telepon Pemesan</label>
                <input type="text" name="phone" class="mt-1 w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Tanggal Mulai</label>
                <input type="date" name="date" class="mt-1 w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Tanggal Selesai (opsional)</label>
                <input type="date" name="end_date" class="mt-1 w-full border rounded px-3 py-2">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-sm font-medium">Jam Mulai</label>
                    <input type="time" name="start_time" class="mt-1 w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Jam Selesai</label>
                    <input type="time" name="end_time" class="mt-1 w-full border rounded px-3 py-2" required>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Proposal (PDF/DOC)</label>
            <input type="file" name="proposal_file" accept=".pdf,.doc,.docx" class="mt-1 w-full">
        </div>

        <div class="flex gap-2 pt-3">
            <a href="{{ route('admin.schedules.index') }}" class="px-4 py-2 border rounded">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
        </div>
    </form>
</div>

@endsection
