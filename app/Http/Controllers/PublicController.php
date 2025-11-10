<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fasilitas;
use App\Models\Booking;

class PublicController extends Controller
{
    private const BASE_RATE_PER_HOUR = 500000;

    public function home(Request $request)
    {
        $fasilitas = Fasilitas::all();
        // Map to a shape the public/home view expects (id, name, price)
        $facilitiesForView = $fasilitas->map(function ($it) {
            return (object) [
                'id' => $it->id,
                'nama' => $it->nama,
                'harga' => $it->harga,
                'stok' => $it->stok,
            ];
        });

        return view('home', [
            'title' => 'Booking GSG Unila',
            'facilities' => $facilitiesForView,
        ]);
    }

    public function availability(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $overlap = Booking::where('date', $request->input('date'))
            ->whereIn('status', ['1','2'])
            ->where(function($q) use ($request) {
                $q->where('start_time', '<', $request->input('end_time'))
                  ->where('end_time', '>', $request->input('start_time'));
            })
            ->exists();

        return response()->json(['available' => !$overlap]);
    }

    public function quote(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'facilities' => 'array',
            'facilities.*.id' => 'exists:fasilitas,id',
            'facilities.*.qty' => 'integer|min:1',
        ]);

        $start = strtotime($request->input('start_time'));
        $end = strtotime($request->input('end_time'));
        $hours = max(1, (int) ceil(($end - $start) / 3600));
        $base = $hours * self::BASE_RATE_PER_HOUR;

        $facilitiesTotal = 0;
        foreach ((array) $request->input('facilities', []) as $f) {
            $item = Fasilitas::find($f['id'] ?? null);
            if ($item) {
                $qty = (int)($f['qty'] ?? 1);
                $facilitiesTotal += $item->harga * $qty;
            }
        }

        return response()->json([
            'hours' => $hours,
            'base' => $base,
            'facilities' => $facilitiesTotal,
            'total' => $base + $facilitiesTotal,
        ]);
    }
}


