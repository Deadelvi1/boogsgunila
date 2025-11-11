<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;

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
            'items' => Facility::all(),
        ];
        return view('list_fasilitas', $data);
    }

    public function create()
    {
        return view('create_fasilitas', ['title' => 'Create Fasilitas']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        Facility::create(['name' => $request->input('nama'), 'price' => $request->input('price')]);
        return redirect()->to('/fasilitas')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = Facility::findOrFail($id);
        return view('edit_fasilitas', ['title' => 'Edit Fasilitas', 'item' => $item]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $item = Facility::findOrFail($id);
        $item->update(['name' => $request->input('nama'), 'price' => $request->input('price')]);
        return redirect()->to('/fasilitas')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = Facility::findOrFail($id);
        $item->delete();
        return redirect()->to('/fasilitas')->with('success', 'Fasilitas berhasil dihapus.');
    }
}


