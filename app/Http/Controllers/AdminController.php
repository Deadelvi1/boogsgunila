<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Fasilitas;
use App\Models\Gedung;
use App\Models\Payment;

class AdminController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth', 'role:A']);
	}

	public function index()
	{
		return view('admin.dashboard', [
			'title' => 'Admin Dashboard',
			'stats' => [
				'users' => \App\Models\User::count(),
				'bookings' => Booking::count(),
				'gedung' => Gedung::count(),
				'fasilitas' => Fasilitas::count(),
				'payments_pending' => Payment::where('status', '1')->count(),
				'active_rentals' => Booking::where('status', '2')->count(),
			],
		]);
	}

	public function usersIndex()
	{
		$req = request();
		$query = \App\Models\User::orderBy('name');
		if ($q = $req->input('q')) {
			$query->where(function($qq) use ($q) {
				$qq->where('name', 'like', "%$q%")
				   ->orWhere('email', 'like', "%$q%")
				   ->orWhere('phone', 'like', "%$q%");
			});
		}
		$users = $query->get();
		return view('admin.users', ['title' => 'Data Pengguna', 'users' => $users, 'q' => $q ?? null]);
	}

	public function usersCreate()
	{
		return view('admin.users_create', ['title' => 'Tambah Pengguna']);
	}

	public function usersStore(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email',
			'password' => 'required|string|min:6',
			'role' => 'required|in:A,U',
			'phone' => 'nullable|string|max:30',
		]);

		\App\Models\User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => \Illuminate\Support\Facades\Hash::make($request->password),
			'role' => $request->role,
			'phone' => $request->phone,
			'email_verified_at' => now(),
		]);

		return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
	}

	public function usersEdit($id)
	{
		$user = \App\Models\User::findOrFail($id);
		return view('admin.users_edit', ['title' => 'Edit Pengguna', 'user' => $user]);
	}

	public function usersUpdate(Request $request, $id)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email,' . $id,
			'password' => 'nullable|string|min:6',
			'role' => 'required|in:A,U',
			'phone' => 'nullable|string|max:30',
		]);

		$user = \App\Models\User::findOrFail($id);
		$data = [
			'name' => $request->name,
			'email' => $request->email,
			'role' => $request->role,
		];

		if ($request->filled('password')) {
			$data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
		}

		$data['phone'] = $request->phone;

		$user->update($data);

		return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
	}

	public function usersDestroy($id)
	{
		$user = \App\Models\User::findOrFail($id);
		// Prevent deleting yourself
		if ($user->id === auth()->id()) {
			return redirect()->route('admin.users.index')->with('error', 'Tidak dapat menghapus akun sendiri.');
		}
		$user->delete();
		return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
	}

	public function schedulesIndex()
	{
		$req = request();
		$query = Booking::with(['user','gedung','bookingFasilitas.fasilitas'])->latest();
		if ($q = $req->input('q')) {
			$query->where(function($qq) use ($q) {
				$qq->where('event_name', 'like', "%$q%")
				   ->orWhere('event_type', 'like', "%$q%")
				   ->orWhere('date', 'like', "%$q%");
			})
			->orWhereHas('user', function($u) use ($q) { $u->where('name', 'like', "%$q%"); })
			->orWhereHas('gedung', function($g) use ($q) { $g->where('nama', 'like', "%$q%"); });
		}
		$items = $query->get();
		return view('admin.schedules', ['title' => 'Data Jadwal', 'items' => $items, 'q' => $q ?? null]);
	}

	public function rentalsIndex()
	{
		$req = request();
		$query = Booking::with(['user','gedung','bookingFasilitas.fasilitas'])->whereIn('status',['1','2'])->latest();
		if ($q = $req->input('q')) {
			$query->where(function($qq) use ($q) {
				$qq->where('event_name', 'like', "%$q%")
				   ->orWhere('event_type', 'like', "%$q%")
				   ->orWhere('date', 'like', "%$q%");
			})
			->orWhereHas('user', function($u) use ($q) { $u->where('name', 'like', "%$q%"); })
			->orWhereHas('gedung', function($g) use ($q) { $g->where('nama', 'like', "%$q%"); });
		}
		$items = $query->get();
		return view('admin.rentals', ['title' => 'Detail Sewa', 'items' => $items, 'q' => $q ?? null]);
	}

	public function bookingCreate()
	{
		$users = \App\Models\User::orderBy('name')->get();
		$gedung = \App\Models\Gedung::orderBy('nama')->get();
		return view('admin.create_booking', [
			'title' => 'Tambah Jadwal',
			'users' => $users,
			'gedung' => $gedung,
		]);
	}

	public function bookingEdit($id)
	{
		$booking = Booking::with('bookingFasilitas.fasilitas')->findOrFail($id);
		$gedung = \App\Models\Gedung::orderBy('nama')->get();
		$fasilitas = \App\Models\Fasilitas::orderBy('nama')->get();
		return view('admin.edit_booking', [
			'title' => 'Edit Booking (Admin)',
			'item' => $booking,
			'gedung' => $gedung,
			'fasilitas' => $fasilitas,
		]);
	}

	public function bookingUpdate(Request $request, $id)
	{
		// reuse BookingController update-like logic but allow admin
		$request->validate([
			'gedung_id' => 'required|exists:gedung,id',
			'event_name' => 'required|string|max:255',
			'event_type' => 'required|string|max:100',
			'capacity' => 'required|integer|min:1',
			'phone' => 'required|string|max:30',
			'date' => 'required|date',
			'end_date' => 'nullable|date|after_or_equal:date',
			'start_time' => 'required|date_format:H:i',
			'end_time' => 'required|date_format:H:i|after:start_time',
			'fasilitas' => 'nullable|array',
			'fasilitas.*.id' => 'required_with:fasilitas|exists:fasilitas,id',
			'fasilitas.*.jumlah' => 'required_with:fasilitas|integer|min:1',
			'status' => 'required|in:1,2,3,4',
		]);

		$booking = Booking::findOrFail($id);

		$updateData = $request->only(['gedung_id','event_name','event_type','capacity','phone','date','end_date','start_time','end_time']);
		$updateData['status'] = $request->input('status');

		// handle proposal file if uploaded
		if ($request->hasFile('proposal_file')) {
			$filePath = $request->file('proposal_file')->store('proposals', 'public');
			$updateData['proposal_file'] = $filePath;
		}

		$oldStatus = $booking->status;

		$booking->update($updateData);

		// Prepare friendly message if status changed
		$statusLabels = ['1' => 'Menunggu', '2' => 'Disetujui', '3' => 'Ditolak', '4' => 'Selesai'];
		if (isset($updateData['status']) && $oldStatus !== $updateData['status']) {
			$oldLabel = $statusLabels[$oldStatus] ?? $oldStatus;
			$newLabel = $statusLabels[$updateData['status']] ?? $updateData['status'];
			$msg = "Status booking \"{$booking->event_name}\" diubah dari {$oldLabel} menjadi {$newLabel}.";
			return redirect()->route('admin.schedules.index')->with('success', $msg);
		}

		// Update fasilitas
		$booking->bookingFasilitas()->delete();
		foreach ($request->input('fasilitas', []) as $item) {
			\App\Models\BookingFasilitas::create([
				'booking_id' => $booking->id,
				'fasilitas_id' => $item['id'],
				'jumlah' => $item['jumlah'] ?? 1,
			]);
		}

		return redirect()->route('admin.schedules.index')->with('success', 'Booking berhasil diperbarui oleh admin.');
	}

	public function bookingStore(\Illuminate\Http\Request $request)
	{
		$request->validate([
			'user_id' => 'required|exists:users,id',
			'gedung_id' => 'required|exists:gedung,id',
			'event_name' => 'required|string|max:255',
			'event_type' => 'required|string|max:100',
			'capacity' => 'required|integer|min:1',
			'phone' => 'required|string|max:30',
			'date' => 'required|date',
			'end_date' => 'nullable|date|after_or_equal:date',
			'start_time' => 'required|date_format:H:i',
			'end_time' => 'required|date_format:H:i|after:start_time',
			'proposal_file' => 'nullable|file|mimes:pdf,doc,docx',
		]);

		$filePath = null;
		if ($request->hasFile('proposal_file')) {
			$filePath = $request->file('proposal_file')->store('proposals', 'public');
		}

		$booking = Booking::create([
			'user_id' => $request->user_id,
			'gedung_id' => $request->gedung_id,
			'event_name' => $request->event_name,
			'event_type' => $request->event_type,
			'capacity' => $request->capacity,
			'phone' => $request->phone,
			'date' => $request->date,
			'end_date' => $request->end_date,
			'start_time' => $request->start_time,
			'end_time' => $request->end_time,
			'proposal_file' => $filePath,
			'status' => '1',
		]);

		return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil ditambahkan.');
	}

	public function bookingInvoice($id)
	{
		$booking = Booking::with(['user', 'gedung', 'bookingFasilitas.fasilitas'])->findOrFail($id);

		$payment = Payment::where('booking_id', $id)->first();
		if (!$payment) {
			// calculate amount similar to BookingController
			$start = strtotime($booking->start_time);
			$end = strtotime($booking->end_time);
			$hours = max(1, ceil(($end - $start) / 3600));

			$facilitiesTotal = 0;
			foreach ($booking->bookingFasilitas as $bf) {
				$facilitiesTotal += ($bf->fasilitas->harga ?? 0) * $bf->jumlah;
			}

			$startDate = strtotime($booking->date);
			$endDate = strtotime($booking->end_date ?? $booking->date);
			$days = (int) floor(($endDate - $startDate) / 86400) + 1;
			if ($days < 1) { $days = 1; }

			$gedungModel = $booking->gedung;
			if ($gedungModel && !empty($gedungModel->harga) && $gedungModel->harga > 0) {
				$amount = ($gedungModel->harga * $days) + $facilitiesTotal;
			} else {
				// fallback hourly rate (same default as BookingController)
				$amount = ($hours * 500000 * $days) + $facilitiesTotal;
			}

			$payment = Payment::create([
				'booking_id' => $booking->id,
				'amount' => $amount,
				'method' => 'pending',
				'proof_file' => null,
				'status' => '0',
			]);
		}

		$paymentAccounts = \App\Models\PaymentAccount::where('is_active', true)->orderBy('type')->orderBy('name')->get();
		return view('admin.booking_invoice', [
			'title' => 'Invoice Booking',
			'booking' => $booking,
			'payment' => $payment,
			'paymentAccounts' => $paymentAccounts,
		]);
	}
}



