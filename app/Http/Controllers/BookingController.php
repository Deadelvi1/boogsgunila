<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use App\Models\Fasilitas;
use App\Models\BookingFasilitas;
use App\Models\Gedung;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    private const BASE_RATE_PER_HOUR = 500000; 
    public function index()
    {
        $data = [
            'title' => 'List Booking',
            'items' => Booking::with(['user', 'gedung'])->latest()->get(),
        ];
        return view('list_booking', $data);
    }

    public function create()
    {
        $gedung = Gedung::all();
        $fasilitas = Fasilitas::all();
        return view('create_booking', [
            'title' => 'Create Booking',
            'gedung' => $gedung,
            'fasilitas' => $fasilitas,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'gedung_id' => 'required|exists:gedung,id',
            'event_name' => 'required|string|max:255',
            'event_type' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'proposal_file' => 'nullable|file|mimes:pdf,doc,docx',
            'fasilitas' => 'nullable|array',
            'fasilitas.*.id' => 'required|exists:fasilitas,id',
            'fasilitas.*.jumlah' => 'required|integer|min:1',
        ]);

        // Cek bentrok jadwal pada gedung dan tanggal yang sama (status 1/2 dianggap memblokir)
        $overlap = Booking::where('gedung_id', $request->input('gedung_id'))
            ->where('date', $request->input('date'))
            ->whereIn('status', ['1','2'])
            ->where(function($q) use ($request) {
                $q->where('start_time', '<', $request->input('end_time'))
                  ->where('end_time', '>', $request->input('start_time'));
            })
            ->exists();
        if ($overlap) {
            return back()->withErrors(['date' => 'Jadwal bentrok pada tanggal/jam tersebut.'])->withInput();
        }

        $filePath = null;
        if ($request->hasFile('proposal_file')) {
            $filePath = $request->file('proposal_file')->store('proposals', 'public');
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'gedung_id' => $request->input('gedung_id'),
            'event_name' => $request->input('event_name'),
            'event_type' => $request->input('event_type'),
            'capacity' => $request->input('capacity'),
            'date' => $request->input('date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'proposal_file' => $filePath,
            'status' => '1',
        ]);

        $fasilitasItems = $request->input('fasilitas', []);
        $facilitiesTotal = 0;
        foreach ($fasilitasItems as $item) {
            $fasilitas = Fasilitas::find($item['id']);
            BookingFasilitas::create([
                'booking_id' => $booking->id,
                'fasilitas_id' => $item['id'],
                'jumlah' => $item['jumlah'] ?? 1,
            ]);
        }

        // Estimasi biaya: durasi (jam) * BASE_RATE + total fasilitas
        $start = strtotime($request->input('start_time'));
        $end = strtotime($request->input('end_time'));
        $hours = max(1, ceil(($end - $start) / 3600));
        $amount = ($hours * self::BASE_RATE_PER_HOUR) + $facilitiesTotal;

        \App\Models\Payment::create([
            'booking_id' => $booking->id,
            'amount' => $amount,
            'method' => 'pending',
            'proof_file' => null,
            'status' => '0', // pending
        ]);

        return redirect()->route('booking.invoice', $booking->id)->with('success', 'Booking berhasil dibuat.');
    }

    public function edit($id)
    {
        $booking = Booking::with('bookingFasilitas.fasilitas')->findOrFail($id);
        $gedung = Gedung::all();
        $fasilitas = Fasilitas::all();
        return view('edit_booking', [
            'title' => 'Edit Booking',
            'item' => $booking,
            'gedung' => $gedung,
            'fasilitas' => $fasilitas,
        ]);
    }

    public function invoice($id)
    {
        $booking = Booking::with('user')->findOrFail($id);
        $payment = \App\Models\Payment::where('booking_id', $id)->firstOrFail();
        return view('booking_invoice', [
            'title' => 'Invoice Booking',
            'booking' => $booking,
            'payment' => $payment,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'gedung_id' => 'required|exists:gedung,id',
            'event_name' => 'required|string|max:255',
            'event_type' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:1,2,3,4',
            'fasilitas' => 'array',
            'fasilitas.*.id' => 'required_with:fasilitas|exists:fasilitas,id',
            'fasilitas.*.jumlah' => 'required_with:fasilitas|integer|min:1',
        ]);

        // Cek bentrok saat update (kecuali dirinya sendiri)
        $overlap = Booking::where('gedung_id', $request->input('gedung_id'))
            ->where('date', $request->input('date'))
            ->where('id', '!=', $id)
            ->whereIn('status', ['1','2'])
            ->where(function($q) use ($request) {
                $q->where('start_time', '<', $request->input('end_time'))
                  ->where('end_time', '>', $request->input('start_time'));
            })
            ->exists();
        if ($overlap) {
            return back()->withErrors(['date' => 'Jadwal bentrok pada tanggal/jam tersebut.'])->withInput();
        }

        $booking = Booking::findOrFail($id);
        $booking->update($request->only([
            'gedung_id', 'event_name', 'event_type', 'capacity', 'date', 'start_time', 'end_time', 'status'
        ]));

        // Update fasilitas
        $booking->bookingFasilitas()->delete(); // Remove old items
        foreach ($request->input('fasilitas', []) as $item) {
            BookingFasilitas::create([
                'booking_id' => $booking->id,
                'fasilitas_id' => $item['id'],
                'jumlah' => $item['jumlah'] ?? 1,
            ]);
        }

        return redirect()->to('/booking')->with('success', 'Booking berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->to('/booking')->with('success', 'Booking berhasil dihapus.');
    }
}


