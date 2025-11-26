<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fasilitas;

class FasilitasController extends Controller
{
    public function showPublic()
    {
        return view('sewa.fasilitas', ['title' => 'Full Set Dekorasi Wisuda']);
    }

    public function index()
    {
        $data = [
            'title' => 'List Fasilitas',
            'items' => Fasilitas::orderBy('nama')->get(),
        ];
        return view('admin.fasilitas.index', $data);
    }

    public function create()
    {
        return view('admin.fasilitas.create', ['title' => 'Tambah Fasilitas']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        Fasilitas::create($validated);
        return redirect()->route('fasilitas.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = Fasilitas::findOrFail($id);
        return view('admin.fasilitas.edit', ['title' => 'Edit Fasilitas', 'item' => $item]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $item = Fasilitas::findOrFail($id);
        $item->update($validated);
        return redirect()->route('fasilitas.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = Fasilitas::findOrFail($id);
        // Prevent deletion if fasilitas is used in any booking_fasilitas
        $used = \App\Models\BookingFasilitas::where('fasilitas_id', $item->id)->exists();
        if ($used) {
            return redirect()->route('fasilitas.index')->with('error', 'Tidak dapat menghapus fasilitas karena sedang digunakan pada booking.');
        }
        $item->delete();
        return redirect()->route('fasilitas.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
}


