<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    // Menampilkan seluruh posisi
    public function index()
    {
        $positions = Position::all();
        return view('user.index', compact('positions'));
    }

    // Menyimpan posisi baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|unique:positions,id', // Validasi ID yang dimasukkan harus unik
            'position_name' => 'required|string|max:255', // Nama posisi wajib diisi
        ]);
    
        // Simpan posisi baru dengan ID manual yang dimasukkan
        Position::create([
            'id' => $request->id, // Menggunakan ID yang dimasukkan oleh pengguna
            'name' => $request->position_name, // Nama posisi
        ]);

        return redirect()->back()->with('success', 'Posisi berhasil ditambahkan.');
    }

    // Menampilkan data posisi untuk edit
    public function edit($id)
    {
        $position = Position::findOrFail($id);
        return response()->json(['position' => $position]);
    }

    // Memperbarui posisi
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $position = Position::findOrFail($id);
        $position->update(['name' => $request->name]);

        return response()->json(['success' => 'Posisi berhasil diperbarui.']);
    }

    // Menghapus posisi
    public function destroy($id)
    {
        $position = Position::findOrFail($id);
        $position->delete();

        return response()->json(['success' => 'Posisi berhasil dihapus.']);
    }
    public function getPositions()
    {
        $positions = Position::all();
        return response()->json($positions);
    }
}