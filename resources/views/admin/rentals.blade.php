@extends('admin.layout')

@section('content')
<div class="flex items-center justify-between mb-4">
	<h1 class="text-2xl font-extrabold text-gray-800">Detail Sewa</h1>
	<a href="{{ route('admin.schedules.index') }}" class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Lihat Semua Jadwal</a>
</div>

<div class="space-y-4">
	@forelse($items as $b)
		<div class="bg-white rounded-xl shadow p-5">
			<div class="grid md:grid-cols-4 gap-4">
				<div class="md:col-span-3">
					<p class="text-sm text-gray-500">Detail</p>
					<div class="grid sm:grid-cols-2 gap-y-1 text-sm">
						<div class="font-semibold text-blue-900">Acara</div><div>{{ $b->event_name }}</div>
						<div class="font-semibold text-blue-900">Tanggal</div><div>{{ $b->date }}</div>
						<div class="font-semibold text-blue-900">Waktu</div><div>{{ $b->start_time }} - {{ $b->end_time }}</div>
						<div class="font-semibold text-blue-900">Status</div>
						<div>
							<span class="px-2 py-1 rounded text-white text-xs
								{{ $b->status==='2' ? 'bg-green-600' : ($b->status==='1' ? 'bg-yellow-600' : ($b->status==='3' ? 'bg-red-600' : 'bg-blue-600')) }}">
								{{ ['1'=>'Proses','2'=>'Aktif','3'=>'Batal','4'=>'Selesai'][$b->status] ?? $b->status }}
							</span>
						</div>
					</div>
				</div>
				<div class="flex items-end">
					<a href="{{ route('booking.edit', $b->id) }}" class="w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white rounded py-2">Kelola</a>
				</div>
			</div>
		</div>
	@empty
		<p class="text-gray-600">Belum ada sewa aktif.</p>
	@endforelse
</div>
@endsection


