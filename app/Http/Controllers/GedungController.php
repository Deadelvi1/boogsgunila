<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;

class GedungController extends Controller
{
    public function showPublic()
    {
        return view('sewa.gedung', ['title' => 'Gedung Serba Guna']);
    }
    public function index()
    {
        $data = [
            'title' => 'List Gedung',
            'items' => Gedung::all(),
        ];
        return view('list_gedung', $data);
    }

    public function create()
    {
        return view('create_gedung', ['title' => 'Create Gedung']);
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
        return view('edit_gedung', ['title' => 'Edit Gedung', 'item' => $item]);
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


