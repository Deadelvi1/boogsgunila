@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
  <div class="max-w-7xl mx-auto px-4">
    <h1 class="text-3xl font-extrabold mb-6">Admin Dashboard</h1>
    @if(session('success'))
      <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <a href="{{ route('booking.index') }}" class="block bg-white rounded shadow p-6 hover:shadow-lg transition">
        <div class="text-gray-500 text-sm">Manajemen</div>
        <div class="text-xl font-bold">Booking</div>
      </a>
      <a href="{{ route('fasilitas.index') }}" class="block bg-white rounded shadow p-6 hover:shadow-lg transition">
        <div class="text-gray-500 text-sm">Master</div>
        <div class="text-xl font-bold">Fasilitas</div>
      </a>
      <a href="{{ route('gedung.index') }}" class="block bg-white rounded shadow p-6 hover:shadow-lg transition">
        <div class="text-gray-500 text-sm">Master</div>
        <div class="text-xl font-bold">Gedung</div>
      </a>
    </div>

    <div class="mt-8 bg-white rounded shadow p-6">
      <h2 class="text-lg font-semibold mb-4">Quick Actions</h2>
      <div class="flex flex-wrap gap-3">
        <a href="{{ route('booking.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Lihat Semua Booking</a>
        <a href="{{ route('fasilitas.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Tambah Fasilitas</a>
      </div>
    </div>
  </div>
</div>
@endsection


