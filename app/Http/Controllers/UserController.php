<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Profile;
use App\Models\Division;
use App\Models\Position;
use App\Models\Detail;
use App\Models\WorkDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan form tambah user
    public function create()
    {
        $positions = Position::all();
        $divisions = Division::all();
        return view('user.create', compact('positions', 'divisions'));
    }

    // Simpan user baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string|unique:profiles,nip',
            'phone_number' => 'required|string',
            'tgllahir' => 'required|date',  // Pastikan tanggal lahir ada dan valid
            'tglmasuk' => 'required|date',
            'address' => 'nullable|string',
            'position_id' => 'required|exists:positions,id',
            'division_id' => 'required|exists:divisions,id',
        ]);
                // Ambil tanggal lahir dari request
        $tanggal_lahir = $request->tgllahir; // Format yang dikirimkan: YYYY-MM-DD
        
        // Mengonversi tanggal lahir ke format DDMMYYYY
        $generatedPassword = date('dmY', strtotime($tanggal_lahir)); // Contoh: 07102002
        
        // Buat user dengan password hasil konversi
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($generatedPassword), // Hash password sebelum disimpan
            'role_id' => 2, // Default sebagai user
        ]);
        
        // Buat profil
        $user->profile()->create([
            'user_id' => $user->id,
            'name' => $request->name,
            'nip' => $request->nip,
            'phone_number' => $request->phone_number,
            'tgllahir' => $request->tgllahir,
            'tglmasuk' => $request->tglmasuk,
            'address' => $request->address,
        ]);
                $detail = $user->details()->create([
            'position_id' => $request->position_id,
        ]);
        
        // Buat work division
        $detail->workDivisions()->create([
            'division_id' => $request->division_id,
        ]);
        
        return redirect()->route('user.create')->with('success', 'User berhasil ditambahkan.');
    }


    // Tampilkan daftar user
public function index(Request $request)
{
    $divisions = Division::all();
    $positions = Position::all();

    $query = Profile::with([
        'user.details.position',
        'user.details.workDivisions.division'
    ]);

    // Filter pencarian umum (nama, nip, no telp)
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('nip', 'like', '%' . $request->search . '%')
              ->orWhere('phone_number', 'like', '%' . $request->search . '%');
        });
    }

    // Filter berdasarkan tanggal lahir
    if ($request->filled('tgllahir')) {
        $query->whereDate('tgllahir', $request->tgllahir);
    }

    // Filter berdasarkan tanggal masuk
    if ($request->filled('tglmasuk')) {
        $query->whereDate('tglmasuk', $request->tglmasuk);
    }

    // Filter berdasarkan divisi
    if ($request->filled('division_id')) {
        $query->whereHas('user.details.workDivisions', function ($q) use ($request) {
            $q->where('division_id', $request->division_id);
        });
    }

    // Filter berdasarkan posisi
    if ($request->filled('position_id')) {
        $query->whereHas('user.details', function ($q) use ($request) {
            $q->where('position_id', $request->position_id);
        });
    }

// ✅ Filter berdasarkan status (aktif/inaktif)
// ✅ Filter berdasarkan status yang dipilih (active atau inactive)
if ($request->filled('status')) {
    $status = $request->status; // Ambil status yang dipilih (active atau inactive)
    
    // Validasi status yang diterima agar hanya 'active' atau 'inactive'
    if (in_array($status, ['active', 'inactive'])) {
        $query->whereHas('user', function ($q) use ($status) {
            $q->where('status', $status); // Filter berdasarkan status yang dipilih
        });
    }
}



    $users = $query->get();

    return view('user.index', compact('users', 'divisions', 'positions'));
}



    // Tampilkan form edit
    public function edit($id)
    {
        $profile = Profile::with(['user.details.position', 'user.details.workDivisions.division'])->findOrFail($id);
        $positions = Position::all();
        $divisions = Division::all();
    
        return view('user.edit', compact('profile', 'positions', 'divisions'));
    }

    // Update data user
    public function update(Request $request, $id)
    {
        $profile = Profile::with('user.details.workDivisions')->findOrFail($id);
        $user = $profile->user;
        $detail = $user->details->first();
    
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|unique:profiles,nip,' . $profile->id,
            'phone_number' => 'required|string',
            'tgllahir' => 'required|date',
            'tglmasuk' => 'required|date',
            'address' => 'nullable|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'position_id' => 'required|exists:positions,id',
            'division_id' => 'required|exists:divisions,id',
        ]);
                $profile->update([
            'name' => $request->name,
            'nip' => $request->nip,
            'phone_number' => $request->phone_number,
            'tgllahir' => $request->tgllahir,
            'tglmasuk' => $request->tglmasuk,
            'address' => $request->address,
        ]);
    
        // Update email user
        $user->update([
            'email' => $request->email,
        ]);
    
        // Update posisi
        $detail->update([
            'position_id' => $request->position_id,
        ]);
    
        // Update work division
        $workDivision = $detail->workDivisions->first();
        if ($workDivision) {
            $workDivision->update([
                'division_id' => $request->division_id,
            ]);
        }
            
        return redirect()->route('user.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }
    
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return redirect()->back()->with('success', 'Status pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $profile = Profile::findOrFail($id);
        $profile->user->delete(); // ini juga menghapus profile jika relasi cascade
        return redirect()->route('user.index')->with('success', 'Data karyawan berhasil dihapus!');
    }
}