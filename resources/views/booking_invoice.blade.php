@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
  <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Invoice Booking</h1>
    @if(session('success'))
      <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    <div class="space-y-2">
      <div><strong>Pemesan:</strong> {{ $booking->user->name ?? '-' }}</div>
      <div><strong>Acara:</strong> {{ $booking->event_name }} ({{ $booking->event_type }})</div>
      <div><strong>Tanggal:</strong> {{ $booking->date }}</div>
      <div><strong>Waktu:</strong> {{ $booking->start_time }} - {{ $booking->end_time }}</div>
      <div><strong>Status Booking:</strong> {{ ['1'=>'Menunggu','2'=>'Disetujui','3'=>'Ditolak','4'=>'Selesai'][$booking->status] ?? $booking->status }}</div>
    </div>
    <hr class="my-4">
    <div class="space-y-2">
      <div><strong>Total Pembayaran:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
      <div><strong>Status Pembayaran:</strong> {{ ['0'=>'Belum dibayar','1'=>'Proses','2'=>'Selesai','3'=>'Dibatalkan'][$payment->status] ?? $payment->status }}</div>
      <div><strong>Metode:</strong> {{ $payment->method === 'pending' ? '-' : $payment->method }}</div>
    </div>

    @if($payment->status === '0')
      <div class="mt-6 bg-blue-50 border rounded p-4">
        <h2 class="font-semibold mb-2">Unggah Bukti Pembayaran</h2>
        <form method="POST" action="{{ route('payments.upload', $payment->id) }}" enctype="multipart/form-data" class="space-y-3">
          @csrf
          <input type="file" name="proof" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm" required>
          <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Upload</button>
        </form>
        <p class="text-xs text-gray-600 mt-2">Catatan: setelah upload, status menjadi Proses menunggu verifikasi admin.</p>
      </div>
    @elseif($payment->status === '1')
      <div class="mt-6 bg-yellow-50 border rounded p-4 text-yellow-700">
        Bukti pembayaran terkirim. Menunggu verifikasi admin.
      </div>
    @elseif($payment->status === '2')
      <div class="mt-6 bg-green-50 border rounded p-4 text-green-700">
        Pembayaran selesai. Terima kasih!
      </div>
    @elseif($payment->status === '3')
      <div class="mt-6 bg-red-50 border rounded p-4 text-red-700">
        Pembayaran dibatalkan. Silakan hubungi admin jika ini kesalahan.
      </div>
    @endif

    <div class="mt-6 flex gap-2">
      <a href="{{ route('booking.index') }}" class="px-4 py-2 border rounded">Kembali</a>
      <a href="{{ route('payments.index') }}" class="px-4 py-2 border rounded">Lihat Semua Pembayaran</a>
    </div>
  </div>
  <div class="text-center mt-4 text-sm text-gray-600">Silakan lakukan pembayaran sesuai instruksi sistem.</div>
  
</div>
@endsection


