@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6">Edit Booking</h1>
        <div class="bg-white shadow rounded p-6">
            <form action="{{ route('booking.update', $item->id) }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            <span class="text-red-600">*</span> Pilih Gedung
                        </label>
                        <select name="gedung_id" id="gedung_id" class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">- Pilih Gedung -</option>
                            @foreach($gedung as $g)
                                <option value="{{ $g->id }}" {{ (old('gedung_id', $item->gedung_id) == $g->id) ? 'selected' : '' }} data-kapasitas="{{ $g->kapasitas }}" data-lokasi="{{ $g->lokasi ?? '-' }}">
                                    {{ $g->nama }} - Kapasitas: {{ $g->kapasitas }} orang @if($g->lokasi) ({{ $g->lokasi }}) @endif
                                </option>
                            @endforeach
                        </select>
                        <div id="gedung-info" class="mt-2 p-2 bg-blue-50 rounded text-sm text-gray-700 hidden">
                            <p><strong>Lokasi:</strong> <span id="gedung-lokasi">-</span></p>
                            <p><strong>Kapasitas Maksimal:</strong> <span id="gedung-kapasitas">-</span> orang</p>
                        </div>
                        @error('gedung_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const gedungSelect = document.getElementById('gedung_id');
                            const gedungInfo = document.getElementById('gedung-info');
                            const gedungLokasi = document.getElementById('gedung-lokasi');
                            const gedungKapasitas = document.getElementById('gedung-kapasitas');
                            const capacityInput = document.querySelector('input[name="capacity"]');
                            
                            if (gedungSelect) {
                                gedungSelect.addEventListener('change', function() {
                                    const selectedOption = this.options[this.selectedIndex];
                                    
                                    if (this.value && selectedOption.dataset.kapasitas) {
                                        gedungLokasi.textContent = selectedOption.dataset.lokasi || '-';
                                        gedungKapasitas.textContent = selectedOption.dataset.kapasitas || '-';
                                        gedungInfo.classList.remove('hidden');
                                        
                                        if (capacityInput) {
                                            capacityInput.setAttribute('max', selectedOption.dataset.kapasitas);
                                        }
                                    } else {
                                        gedungInfo.classList.add('hidden');
                                        if (capacityInput) {
                                            capacityInput.removeAttribute('max');
                                        }
                                    }
                                });
                                
                                if (gedungSelect.value) {
                                    gedungSelect.dispatchEvent(new Event('change'));
                                }
                            }
                        });
                    </script>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Nama Acara</label>
                        <input type="text" name="event_name" value="{{ old('event_name', $item->event_name) }}" class="mt-1 w-full border rounded px-3 py-2" required>
                        @error('event_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Jenis Acara</label>
                        <input type="text" name="event_type" value="{{ old('event_type', $item->event_type) }}" class="mt-1 w-full border rounded px-3 py-2" required>
                        @error('event_type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Kapasitas</label>
                        <input type="number" name="capacity" value="{{ old('capacity', $item->capacity) }}" class="mt-1 w-full border rounded px-3 py-2" min="1" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Tanggal</label>
                        <input type="date" name="date" value="{{ old('date', $item->date) }}" class="mt-1 w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Jam Mulai</label>
                            <input type="time" name="start_time" value="{{ old('start_time', $item->start_time) }}" class="mt-1 w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Jam Selesai</label>
                            <input type="time" name="end_time" value="{{ old('end_time', $item->end_time) }}" class="mt-1 w-full border rounded px-3 py-2" required>
                        </div>
                    </div>
                </div>
                @if($item->proposal_file)
                    <div>
                        <label class="block text-sm font-medium">Proposal Saat Ini</label>
                        <a href="{{ Storage::url($item->proposal_file) }}" class="text-blue-600 hover:underline" target="_blank">Lihat Proposal</a>
                    </div>
                @endif
                <div>
                    <label class="block text-sm font-medium">Proposal Baru (PDF/DOC, kosongkan jika tidak ingin mengubah)</label>
                    <input type="file" name="proposal_file" accept=".pdf,.doc,.docx" class="mt-1 w-full border rounded px-3 py-2">
                    @error('proposal_file')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Fasilitas</label>
                    <div class="space-y-2">
                        @foreach($fasilitas as $f)
                            @php
                                $bookingFasilitas = $item->bookingFasilitas->firstWhere('fasilitas_id', $f->id);
                                $checked = $bookingFasilitas !== null;
                                $jumlah = $bookingFasilitas ? $bookingFasilitas->jumlah : 1;
                            @endphp
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="f_{{ $f->id }}" 
                                    {{ $checked ? 'checked' : '' }}
                                    onchange="this.nextElementSibling.disabled=!this.checked; this.nextElementSibling.nextElementSibling.disabled=!this.checked;">
                                <label for="f_{{ $f->id }}" class="w-48">{{ $f->nama }} (Stok: {{ $f->stok }})</label>
                                <input type="hidden" name="fasilitas[{{ $loop->index }}][id]" value="{{ $f->id }}" {{ !$checked ? 'disabled' : '' }}>
                                <input type="number" name="fasilitas[{{ $loop->index }}][jumlah]" class="border rounded px-2 py-1 w-24" 
                                    min="1" max="{{ $f->stok }}" value="{{ $jumlah }}" {{ !$checked ? 'disabled' : '' }}>
                            </div>
                        @endforeach
                    </div>
                    @error('fasilitas')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    @error('fasilitas.*.id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    @error('fasilitas.*.jumlah')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                @if(auth()->user()->role === 'A')
                <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select name="status" class="mt-1 w-full border rounded px-3 py-2" required>
                        <option value="1" {{ old('status', $item->status) == '1' ? 'selected' : '' }}>Menunggu</option>
                        <option value="2" {{ old('status', $item->status) == '2' ? 'selected' : '' }}>Disetujui</option>
                        <option value="3" {{ old('status', $item->status) == '3' ? 'selected' : '' }}>Ditolak</option>
                        <option value="4" {{ old('status', $item->status) == '4' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                @endif
                <div class="flex gap-2 pt-2">
                    <a href="{{ route('booking.index') }}" class="px-4 py-2 border rounded">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


