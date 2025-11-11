<?php

namespace App\Http\Controllers;

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

	public function schedulesIndex()
	{
		$items = Booking::with(['user','gedung'])->latest()->get();
		return view('admin.schedules', ['title' => 'Data Jadwal', 'items' => $items]);
	}

	public function rentalsIndex()
	{
		$items = Booking::with(['user','gedung'])->whereIn('status',['1','2'])->latest()->get();
		return view('admin.rentals', ['title' => 'Detail Sewa', 'items' => $items]);
	}
}



