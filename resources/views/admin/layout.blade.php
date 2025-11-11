<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ $title ?? 'Admin • BooGSG' }}</title>
	<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
		body { font-family: 'Inter', sans-serif; }
	</style>
	@stack('head')
</head>
<body class="bg-gray-100">
	<div class="min-h-screen flex">
		<!-- Sidebar -->
		<aside class="w-64 bg-white border-r">
			<div class="px-6 py-4 border-b">
				<div class="text-xl font-extrabold text-blue-900">BooGSG</div>
				<div class="text-xs text-gray-500">Administrator</div>
			</div>
			<nav class="px-3 py-4 space-y-1 text-sm">
				<a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-50 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-900 font-semibold' : 'text-gray-700' }}"><i class="fa-solid fa-gauge"></i> Dashboard</a>
				<a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-50 {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 text-blue-900 font-semibold' : 'text-gray-700' }}"><i class="fa-solid fa-users"></i> Data Pengguna</a>
				<a href="{{ route('admin.schedules.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-50 {{ request()->routeIs('admin.schedules.*') ? 'bg-blue-100 text-blue-900 font-semibold' : 'text-gray-700' }}"><i class="fa-solid fa-calendar"></i> Data Jadwal</a>
				<a href="{{ route('admin.rentals.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-50 {{ request()->routeIs('admin.rentals.*') ? 'bg-blue-100 text-blue-900 font-semibold' : 'text-gray-700' }}"><i class="fa-solid fa-clipboard-list"></i> Detail Sewa</a>
				<a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-50 {{ request()->routeIs('admin.payments.*') ? 'bg-blue-100 text-blue-900 font-semibold' : 'text-gray-700' }}"><i class="fa-solid fa-file-invoice-dollar"></i> Verif Pembayaran</a>
				<form method="POST" action="{{ route('auth.logout') }}" class="mt-4 px-3">
					@csrf
					<button class="w-full flex items-center gap-3 px-3 py-2 rounded-md text-red-600 hover:bg-red-50"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</button>
				</form>
			</nav>
		</aside>

		<!-- Main -->
		<div class="flex-1 flex flex-col">
			<header class="bg-white border-b">
				<div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
					<div class="w-full max-w-xl">
						<div class="relative">
							<i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
							<input type="text" placeholder="Cari..." class="w-full pl-10 pr-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500">
						</div>
					</div>
					<div class="flex items-center gap-3">
						<div class="text-right">
							<div class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'Admin' }}</div>
							<div class="text-xs text-gray-500">Administrator</div>
						</div>
						<img src="{{ asset('img/default-avatar.png') }}" class="w-9 h-9 rounded-full border object-cover" alt="avatar">
					</div>
				</div>
			</header>

			<main class="flex-1 p-6">
				@yield('content')
			</main>
		</div>
	</div>
	@stack('scripts')
</body>
<html>


