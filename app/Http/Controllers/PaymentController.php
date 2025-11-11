<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Booking;

class PaymentController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth']);
	}

	public function adminIndex()
	{
		$this->middleware(['auth', 'role:A']);
		$payments = Payment::with(['booking' => function ($q) { $q->with('user','gedung'); }])->latest()->get();
		return view('admin.payments', [
			'title' => 'Verifikasi Pembayaran',
			'payments' => $payments,
		]);
	}

	public function index(Request $request)
	{
		// status: 0 pending, 1 processing, 2 success, 3 canceled
		$statusFilter = $request->input('status');

		$query = Payment::with(['booking' => function ($q) {
			$q->with('gedung');
		}])->whereHas('booking', function ($q) {
			$q->where('user_id', Auth::id());
		});

		if ($statusFilter !== null && $statusFilter !== '') {
			$query->where('status', $statusFilter);
		}

		$payments = $query->latest()->get();

		return view('payments.index', [
			'title' => 'Pembayaran',
			'payments' => $payments,
			'active' => (string)($statusFilter ?? ''),
		]);
	}

	public function uploadProof(Request $request, $paymentId)
	{
		$request->validate([
			'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
		]);

		$payment = Payment::with('booking')->where('id', $paymentId)
			->whereHas('booking', function ($q) {
				$q->where('user_id', Auth::id());
			})->firstOrFail();

		$path = $request->file('proof')->store('payment_proofs', 'public');
		$payment->update([
			'proof_file' => $path,
			'method' => 'manual-transfer',
			'status' => '1', // processing
		]);

		return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi.');
	}

	// Admin only quick status updates
	public function adminMark(Request $request, $paymentId)
	{
		$this->middleware(['auth', 'role:A']);
		$request->validate([
			'status' => 'required|in:1,2,3',
		]);
		$payment = Payment::findOrFail($paymentId);
		$payment->update(['status' => $request->input('status')]);
		return back()->with('success', 'Status pembayaran diperbarui.');
	}
}



