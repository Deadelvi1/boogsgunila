@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-purple-50 to-indigo-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
       
        <div class="text-center mb-8">
            <h1 class="text-5xl font-bold bg-gradient-to-r from-pink-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent mb-2 animate-pulse">
                👥 Daftar User Mahasiswa 👥
            </h1>
            <p class="text-gray-600 text-lg">Kelola data mahasiswa dengan mudah dan efisien</p>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl mb-6 mx-4 animate-bounce shadow-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl mb-6 mx-4 animate-bounce shadow-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-semibold">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="bg-white/80 backdrop-blur-sm shadow-2xl rounded-3xl overflow-hidden border border-white/20">
          
            <div class="bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 p-6">
                <h2 class="text-2xl font-bold text-white text-center flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Data Mahasiswa
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse">
                    <thead>
                        <tr class="bg-gradient-to-r from-pink-400 to-purple-500 text-white">
                            <th class="py-5 px-6 text-sm font-bold uppercase tracking-wider">ID</th>
                            <th class="py-5 px-6 text-sm font-bold uppercase tracking-wider">Nama Mahasiswa</th>
                            <th class="py-5 px-6 text-sm font-bold uppercase tracking-wider">NPM</th>
                            <th class="py-5 px-6 text-sm font-bold uppercase tracking-wider">Kelas</th>
                            <th class="py-5 px-6 text-sm font-bold uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-pink-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gradient-to-r hover:from-pink-50 hover:to-purple-50 transition duration-300 group">
                                <td class="py-5 px-6 text-gray-700 font-mono text-xs bg-gray-50">
                                    <span class="bg-gray-200 px-2 py-1 rounded-full">{{ substr($user->id, 0, 8) }}...</span>
                                </td>
                                <td class="py-5 px-6 text-gray-700 font-semibold text-lg">{{ $user->nama }}</td>
                                <td class="py-5 px-6 text-gray-700 font-mono bg-blue-50 rounded-lg">{{ $user->nim }}</td>
                                <td class="py-5 px-6 text-gray-700">
                                    <span class="bg-gradient-to-r from-purple-100 to-pink-100 px-3 py-1 rounded-full text-purple-800 font-semibold">
                                        {{ $user->kelas->nama_kelas }}
                                    </span>
                                </td>
                                <td class="py-5 px-6">
                                    <div class="flex justify-center gap-3">
                                        
                                        <a href="{{ route('user.edit', $user->id) }}" 
                                           class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-5 py-2 rounded-xl font-semibold shadow-lg transform hover:scale-110 transition duration-300 flex items-center gap-2 group">
                                            <svg class="w-4 h-4 group-hover:rotate-12 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        
                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('⚠️ Apakah Anda yakin ingin menghapus mahasiswa {{ $user->nama }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-5 py-2 rounded-xl font-semibold shadow-lg transform hover:scale-110 transition duration-300 flex items-center gap-2 group">
                                                <svg class="w-4 h-4 group-hover:rotate-12 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 px-6 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-4">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="text-xl font-semibold">Belum ada data mahasiswa</p>
                                        <p class="text-gray-400">Klik tombol di bawah untuk menambahkan mahasiswa pertama</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="text-center mt-8 space-y-4">
            <a href="{{ route('user.create') }}" 
               class="bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 hover:from-green-600 hover:via-emerald-600 hover:to-teal-600 text-white px-10 py-4 rounded-xl font-bold shadow-xl transform hover:scale-105 transition duration-300 inline-flex items-center gap-3 text-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                ➕ Tambah Mahasiswa Baru
            </a>
            
            <div class="text-sm text-gray-500">
                Total Mahasiswa: <span class="font-bold text-pink-600">{{ $users->count() }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
