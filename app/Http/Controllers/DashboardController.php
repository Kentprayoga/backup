<?php
namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Approval;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {   $adminId = Auth::id(); // Pastikan admin login
        $totalDocuments = Document::count();
        $pendingApprovals = Approval::where('status', 'pending')->count();
        $incomingMessages = Message::where('receiver_id', $adminId)->count();

        // Ambil data pengajuan per tanggal dalam 7 hari terakhir
        $dateRange = Carbon::now()->subDays(7);
        $documentsPerDate = Document::where('tanggal_pengajuan', '>=', $dateRange)
            ->selectRaw('DATE(tanggal_pengajuan) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Siapkan data untuk chart
        $dates = $documentsPerDate->pluck('date');
        $counts = $documentsPerDate->pluck('count');

        return view('pages.dashboard', compact('totalDocuments', 'pendingApprovals', 'incomingMessages', 'dates', 'counts'));
    }
}