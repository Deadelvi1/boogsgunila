@extends('admin.layout')

@section('content')
<div class="flex items-center justify-between mb-4">
	<h1 class="text-2xl font-extrabold text-gray-800">Data Jadwal</h1>
	<a href="{{ route('admin.booking.create') }}" class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded">+ Tambah Jadwal</a>
</div>

@if(session('success'))
	<div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4" id="successMsg">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-xl shadow overflow-x-auto">
	<table class="min-w-full text-sm">
		<thead class="bg-gray-100 text-left">
			<tr>
				<th class="p-3">No</th>
				<th class="p-3">Tanggal</th>
				<th class="p-3">Acara</th>
				<th class="p-3">Status</th>
				<th class="p-3">Aksi</th>
			</tr>
		</thead>
		<tbody id="scheduleTable">
			@forelse($items as $i => $item)
			@php
				$payment = \App\Models\Payment::where('booking_id', $item->id)->first();
				$paymentStatus = $payment ? ['0'=>'Pending','1'=>'Verified','2'=>'Rejected','3'=>'Cancelled'][$payment->status] ?? 'Unknown' : 'No Payment';
				$paymentStatusColor = $payment ? ($payment->status === '1' ? 'bg-green-600' : ($payment->status === '0' ? 'bg-yellow-600' : 'bg-red-600')) : 'bg-gray-600';
			@endphp
			<tr class="border-t" id="row-{{ $item->id }}">
				<td class="p-3">{{ $i+1 }}</td>
				<td class="p-3">{{ $item->date }} • {{ $item->start_time }}-{{ $item->end_time }}</td>
				<td class="p-3">
					<div class="font-semibold text-blue-900">{{ $item->event_name }}</div>
					<div class="text-xs text-gray-600">Gedung: {{ $item->gedung->nama ?? '-' }}</div>
					<div class="text-xs text-gray-500 mt-1">Pemesan: {{ $item->user->name ?? '-' }}</div>
				</td>
				<td class="p-3" id="status-{{ $item->id }}">
					<div>
						@php $label=['1'=>'Menunggu','2'=>'Disetujui','3'=>'Ditolak','4'=>'Selesai'][$item->status] ?? $item->status; @endphp
						<span class="px-2 py-1 rounded text-white text-xs block mb-2 status-badge
							{{ $item->status==='2' ? 'bg-green-600' : ($item->status==='1' ? 'bg-yellow-600' : ($item->status==='3' ? 'bg-red-600' : 'bg-blue-600')) }}"
							data-status="{{ $item->status }}">
							{{ $label }}
						</span>
						<span class="px-2 py-1 rounded text-white text-xs block payment-badge {{ $paymentStatusColor }}">
							💳 {{ $paymentStatus }}
						</span>
					</div>
				</td>
			<td class="p-3">
				<div class="flex flex-col gap-2">
					<a href="{{ route('admin.booking.edit', $item->id) }}" class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-xs text-center">Edit</a>
					<button type="button" onclick="deleteSchedule({{ $item->id }})" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs cursor-pointer">Hapus</button>
				</div>
			</td>
			</tr>
			@empty
			<tr>
				<td colspan="5" class="p-6 text-center text-gray-500">Tidak ada jadwal.</td>
			</tr>
			@endforelse
		</tbody>
	</table>
</div>

<script>
// Polling untuk status updates setiap 3 detik
setInterval(refreshStatuses, 3000);

async function refreshStatuses() {
	const rows = document.querySelectorAll('tr[id^="row-"]');
	for (let row of rows) {
		const bookingId = row.id.replace('row-', '');
		try {
			const response = await fetch(`/admin/api/booking/${bookingId}/status`);
			const data = await response.json();
			updateStatusDisplay(bookingId, data);
		} catch (e) {
			console.error('Error fetching status:', e);
		}
	}
}

function updateStatusDisplay(bookingId, data) {
	const statusContainer = document.querySelector(`#status-${bookingId}`);
	if (!statusContainer) return;

	const statusLabels = {
		'1': 'Menunggu',
		'2': 'Disetujui',
		'3': 'Ditolak',
		'4': 'Selesai'
	};
	const statusColors = {
		'1': 'bg-yellow-600',
		'2': 'bg-green-600',
		'3': 'bg-red-600',
		'4': 'bg-blue-600'
	};

	const paymentStatuses = {
		'0': 'Pending',
		'1': 'Verified',
		'2': 'Rejected',
		'3': 'Cancelled'
	};
	const paymentColors = {
		'0': 'bg-yellow-600',
		'1': 'bg-green-600',
		'2': 'bg-red-600',
		'3': 'bg-red-600'
	};

	// Update booking status
	const statusBadge = statusContainer.querySelector('.status-badge');
	if (statusBadge && statusBadge.dataset.status !== data.status) {
		statusBadge.textContent = statusLabels[data.status] || data.status;
		statusBadge.dataset.status = data.status;
		statusBadge.className = `px-2 py-1 rounded text-white text-xs block mb-2 status-badge ${statusColors[data.status]}`;
	}

	// Update payment status
	const paymentBadge = statusContainer.querySelector('.payment-badge');
	if (paymentBadge && data.payment_status) {
		const newPaymentStatus = paymentStatuses[data.payment_status] || 'No Payment';
		if (paymentBadge.textContent !== `💳 ${newPaymentStatus}`) {
			paymentBadge.textContent = `💳 ${newPaymentStatus}`;
			paymentBadge.className = `px-2 py-1 rounded text-white text-xs block payment-badge ${paymentColors[data.payment_status] || 'bg-gray-600'}`;
		}
	}
}

async function deleteSchedule(bookingId) {
	if (!confirm('Hapus jadwal ini?')) return;

	try {
		const response = await fetch(`/admin/api/booking/${bookingId}/delete`, {
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
				'Content-Type': 'application/json'
			}
		});

		const data = await response.json();
		
		if (response.ok) {
			// Hapus baris dari tabel
			const row = document.querySelector(`#row-${bookingId}`);
			if (row) row.remove();
			
			// Tampilkan pesan sukses
			showSuccessMsg('Jadwal berhasil dihapus');
		} else {
			alert('Error: ' + (data.message || 'Gagal menghapus jadwal'));
		}
	} catch (error) {
		console.error('Error:', error);
		alert('Terjadi kesalahan');
	}
}

function showSuccessMsg(msg) {
	const successDiv = document.getElementById('successMsg') || (() => {
		const div = document.createElement('div');
		div.id = 'successMsg';
		div.className = 'bg-green-100 text-green-700 px-4 py-3 rounded mb-4';
		document.querySelector('.flex.items-center').insertAdjacentElement('afterend', div);
		return div;
	})();
	
	successDiv.textContent = msg;
	successDiv.style.display = 'block';
	
	setTimeout(() => {
		successDiv.style.display = 'none';
	}, 3000);
}
</script>
@endsection


