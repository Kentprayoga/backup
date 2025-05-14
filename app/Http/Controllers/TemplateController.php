<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class TemplateController extends Controller
{
    
public function download($filePath)
{
    // Mendapatkan file dari storage private
    $file = storage_path("app/$filePath");

    // Mengecek apakah file ada di folder
    if (file_exists($file)) {
        // Mengunduh file
        return response()->download($file);
    } else {
        // Jika file tidak ditemukan
        return redirect()->route('template.index')->with('error', 'File tidak ditemukan.');
    }
}
    public function index()
    {
        // Mengambil templates beserta category-nya
        $templates = Template::with('category')->get();
        $categories = Category::all();
        return view('template.index', compact('templates', 'categories'));
    }

    public function create()
    {
        // Mengambil semua kategori
        $categories = Category::all();
        return view('template.create', compact('categories'));
    }

// TemplateController.php
public function store(Request $request)
{
    $request->validate([
        'categorie_id' => 'required|exists:categories,id',
        'name' => 'required|string|max:255',
        'format_nomor' => 'required|string|max:255',
        'file' => 'required|file|mimes:pdf,docx,xlsx|max:2048',
    ]);

    $category = Category::find($request->categorie_id);
    $year = now()->year;
    $ext = $request->file('file')->getClientOriginalExtension();
    $filename = strtolower(str_replace(' ', '_', $category->name)) . '-' . 
                strtolower(str_replace(' ', '_', $request->name)) . "_$year.$ext";

    // Simpan file di folder public/storage/template
    $request->file('file')->storeAs('public/storage/template', $filename);

    Template::create([
        'categorie_id' => $request->categorie_id,
        'name' => $request->name,
        'format_nomor' => $request->format_nomor,
        'file_path' => "storage/template/$filename", // Menggunakan path relatif untuk URL
    ]);

    return redirect()->route('template.index')->with('success', 'File berhasil diupload.');
}

    
}