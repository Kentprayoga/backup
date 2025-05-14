<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function login()
    {
        // Jika pengguna sudah login, redirect ke halaman utama (dashboard atau halaman lainnya)
        if (Auth::check()) {
            // Menggunakan back() untuk kembali ke halaman sebelumnya
            return back();
        }


        return view('auth.login');
    }

    // Menangani autentikasi pengguna
    public function authenticate(Request $request)
    {
        // Validasi kredensial login
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba autentikasi pengguna
        if (Auth::attempt($credentials)) {
            // Cek apakah role_id pengguna adalah 1 (admin)
            if (Auth::user()->role_id != 1) {
                // Jika bukan admin, logout dan beri pesan error
                Auth::logout();
                return back()->with('error', 'Anda tidak memiliki akses sebagai admin.');
            }

            // Regenerasi session untuk login yang berhasil
            $request->session()->regenerate();

            // Redirect ke halaman sebelumnya atau halaman utama
        
            return redirect()->intended('/'); // atau halaman utama yang sesuai
        }

        // Jika login gagal, tampilkan pesan error
        return back()->with('error', 'Email atau password salah.')->withInput();
    }

    // Menangani logout pengguna
    public function logout(Request $request)
    {
        // Logout pengguna dan invalidate session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}