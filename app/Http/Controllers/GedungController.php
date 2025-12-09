<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;

class GedungController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:A']);
    }

    public function index(Request $request)
    {
        $q = $request->input('q');

        $items = Gedung::when($q, function($query) use ($q) {
            $query->where('nama', 'like', "%{$q}%")
                  ->orWhere('deskripsi', 'like', "%{$q}%")
                  ->orWhere('lokasi', 'like', "%{$q}%");
        })->orderBy('nama')->get();

        $data = [
            'title' => 'Gedung',
            'items' => $items,
            'q' => $q,
        ];
        return view('admin.gedung.index', $data);
    }

    public function create()
    {
        return view('admin.gedung.create', ['title' => 'Tambah Gedung']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|integer|min:0',
            'harga' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        Gedung::create($request->only(['nama','lokasi','kapasitas','harga','deskripsi']));
        return redirect()->route('gedung.index')->with('success', 'Gedung berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = Gedung::findOrFail($id);
        return view('admin.gedung.edit', ['title' => 'Edit Gedung', 'item' => $item]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|integer|min:0',
            'harga' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $item = Gedung::findOrFail($id);
        $item->update($request->only(['nama','lokasi','kapasitas','harga','deskripsi']));
        return redirect()->route('gedung.index')->with('success', 'Gedung berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = Gedung::findOrFail($id);

        // Prevent deleting a gedung that has bookings
        $hasBooking = \App\Models\Booking::where('gedung_id', $item->id)->exists();
        if ($hasBooking) {
            return redirect()->route('gedung.index')->with('error', 'Tidak dapat menghapus gedung karena masih ada booking terkait.');
        }

        $item->delete();
        return redirect()->route('gedung.index')->with('success', 'Gedung berhasil dihapus.');
    }
}


