@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6">Buat Booking Gedung</h1>
        <div class="bg-white shadow rounded p-6">
            <form action="{{ route('booking.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Pilih Gedung</label>
                        <select name="gedung_id" class="mt-1 w-full border rounded px-3 py-2" required>
                            <option value="">- Pilih Gedung -</option>
                            @foreach($gedung as $g)
                                <option value="{{ $g->id }}" {{ old('gedung_id') == $g->id ? 'selected' : '' }}>
                                    {{ $g->nama }} (Kapasitas: {{ $g->kapasitas }} orang)
                                </option>
                            @endforeach
                        </select>
                        @error('gedung_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Nama Acara</label>
                        <input type="text" name="event_name" value="{{ old('event_name') }}" class="mt-1 w-full border rounded px-3 py-2" required>
                        @error('event_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Jenis Acara</label>
                        <input type="text" name="event_type" value="{{ old('event_type') }}" class="mt-1 w-full border rounded px-3 py-2" required>
                        @error('event_type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Kapasitas</label>
                        <input type="number" name="capacity" value="{{ old('capacity', request('capacity')) }}" class="mt-1 w-full border rounded px-3 py-2" min="1" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Tanggal</label>
                        <input type="date" name="date" value="{{ old('date', request('date')) }}" class="mt-1 w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Jam Mulai</label>
                            <input type="time" name="start_time" value="{{ old('start_time', request('start_time')) }}" class="mt-1 w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Jam Selesai</label>
                            <input type="time" name="end_time" value="{{ old('end_time', request('end_time')) }}" class="mt-1 w-full border rounded px-3 py-2" required>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium">Proposal (PDF/DOC)</label>
                    <input type="file" name="proposal_file" accept=".pdf,.doc,.docx" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Fasilitas (opsional)</label>
                    <div class="space-y-2">
                        @foreach($fasilitas as $f)
                            <div class="flex items-center gap-3">
                                <input type="checkbox" 
                                       id="f_{{ $f->id }}" 
                                       onchange="toggleFasilitas(this, {{ $loop->index }})">
                                <label for="f_{{ $f->id }}" class="w-48">{{ $f->nama }} (Stok: {{ $f->stok }})</label>
                                <input type="hidden" 
                                       name="fasilitas[{{ $loop->index }}][id]" 
                                       value="{{ $f->id }}" 
                                       id="fasilitas_id_{{ $loop->index }}" 
                                       disabled>
                                <input type="number" 
                                       name="fasilitas[{{ $loop->index }}][jumlah]" 
                                       class="border rounded px-2 py-1 w-24" 
                                       min="1" 
                                       max="{{ $f->stok }}" 
                                       value="1" 
                                       id="fasilitas_jumlah_{{ $loop->index }}" 
                                       disabled>
                            </div>
                        @endforeach

                        <script>
                            function toggleFasilitas(checkbox, index) {
                                const idField = document.getElementById('fasilitas_id_' + index);
                                const jumlahField = document.getElementById('fasilitas_jumlah_' + index);
                                
                                if (checkbox.checked) {
                                    idField.disabled = false;
                                    jumlahField.disabled = false;
                                } else {
                                    idField.disabled = true;
                                    jumlahField.disabled = true;
                                }
                            }
                        </script>
                    </div>
                    @error('fasilitas')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    @error('fasilitas.*.id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    @error('fasilitas.*.jumlah')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex gap-2 pt-2">
                    <a href="{{ route('booking.index') }}" class="px-4 py-2 border rounded">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


