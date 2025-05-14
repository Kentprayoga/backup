<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $query = Document::with(['category', 'template', 'approval', 'user']);

        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        if ($request->filled('status')) {
            $query->whereHas('approval', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_pengajuan', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        $documents = $query->get();

        return view('history.index', compact('documents', 'categories'));
    }

        public function cetakHistory(Request $request)
    {
        $query = Document::with(['category', 'template', 'approval', 'user']);

        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        if ($request->filled('status')) {
            $query->whereHas('approval', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_pengajuan', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        $documents = $query->get();

        $pdf = PDF::loadView('history.cetak-history', compact('documents'));
        return $pdf->download('history-dokumen.pdf');
    }

        public function cetakSatu($id)
    {
        $document = Document::with(['category', 'template', 'approval', 'user'])->findOrFail($id);

        $pdf = PDF::loadView('history.cetak-satu', compact('document'));
        return $pdf->download("history-{$document->id}.pdf");
    }


}