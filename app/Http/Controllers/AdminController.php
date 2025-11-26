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
		$users = \App\Models\User::orderBy('name')->get();
		return view('admin.users', ['title' => 'Data Pengguna', 'users' => $users]);
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
		]);

		\App\Models\User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => \Illuminate\Support\Facades\Hash::make($request->password),
			'role' => $request->role,
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
		$items = Booking::with(['user','gedung','bookingFasilitas.fasilitas'])->latest()->get();
		return view('admin.schedules', ['title' => 'Data Jadwal', 'items' => $items]);
	}

	public function rentalsIndex()
	{
		$items = Booking::with(['user','gedung','bookingFasilitas.fasilitas'])->whereIn('status',['1','2'])->latest()->get();
		return view('admin.rentals', ['title' => 'Detail Sewa', 'items' => $items]);
	}
}



