<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Approval;

class ApprovalController extends Controller
{
    public function index()
    {
        $approvals = Approval::with(['document.user'])->where('status', 'pending')->get();
        return view('approvals.index', compact('approvals'));
    }

    public function approve($id)
    {
        $approval = Approval::findOrFail($id);
        $approval->status = 'approved';
        $approval->save();

        return redirect()->route('approvals.index')->with('success', 'Dokumen disetujui.');
    }

    public function reject($id)
    {
        $approval = Approval::findOrFail($id);
        $approval->status = 'rejected';
        $approval->save();

        return redirect()->route('approvals.index')->with('success', 'Dokumen ditolak.');
    }
}