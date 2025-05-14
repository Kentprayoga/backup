<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Menampilkan semua kategori
    public function index()
    {
        $categories = Category::all();
        return view('template.index', compact('categories'));
    }

    // Menyimpan kategori baru
    public function store(Request $request)
    {
        // Validasi input ID kategori manual
        $request->validate([
            'id' => 'required|integer|unique:categories,id',
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        // Simpan kategori baru dengan ID yang diinput manual
        Category::create([
            'id' => $request->id,
            'name' => $request->name,
        ]);

        // Redirect kembali ke halaman index template setelah berhasil
        return redirect()->route('template.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Mengambil data kategori untuk diubah
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    // Mengupdate kategori
    public function update(Request $request, $id)
    {
        $request->validate([
            'id' => 'required|unique:categories,id,' . $id,  // Pastikan ID unik, kecuali untuk kategori yang sedang diupdate
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'id' => $request->id,   // Update ID manual
            'name' => $request->name,
        ]);

        return redirect()->route('template.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // Menghapus kategori
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}