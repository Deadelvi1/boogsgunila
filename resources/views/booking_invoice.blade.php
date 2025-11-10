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
      <div><strong>Status Pembayaran:</strong> {{ ['0'=>'Pending','1'=>'Lunas','2'=>'Gagal'][$payment->status] ?? $payment->status }}</div>
      <div><strong>Metode:</strong> {{ $payment->method }}</div>
    </div>
    <div class="mt-6 flex gap-2">
      <a href="{{ route('booking.index') }}" class="px-4 py-2 border rounded">Kembali</a>
    </div>
  </div>
  <div class="text-center mt-4 text-sm text-gray-600">Silakan lakukan pembayaran sesuai instruksi sistem.</div>
  
</div>
@endsection


