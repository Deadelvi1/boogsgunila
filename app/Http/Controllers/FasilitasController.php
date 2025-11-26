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
        return view('list_fasilitas', $data);
    }

    public function create()
    {
        return view('create_fasilitas', ['title' => 'Create Fasilitas']);
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
        return view('edit_fasilitas', ['title' => 'Edit Fasilitas', 'item' => $item]);
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
        $item->delete();
        return redirect()->route('fasilitas.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
}


