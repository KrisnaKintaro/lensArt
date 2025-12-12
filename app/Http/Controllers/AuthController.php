<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Tambahkan untuk hashing password
use App\Models\User; // Pastikan ini adalah Model User Anda yang benar

class AuthController extends Controller
{
    /**
     * Menampilkan halaman/view form Login.
     */
    public function tampilkanFormLogin()
    {
        return view('login'); 
    }

    /**
     * Menampilkan halaman/view form Register.
     */
    public function tampilkanFormRegister()
    {
        return view('register'); 
    }

    /**
     * Memproses data pendaftaran pengguna baru.
     */
    public function prosesRegister(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'name' => 'required|string|max:255',
            'noTelp' => 'required|string|max:15', 
            'email' => 'required|string|email|max:255|unique:user,email', 
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Buat Pengguna Baru
        try {
            User::create([
                'namaLengkap' => $request->name, 
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer', 
                'noTelp' => $request->noTelp, 
                'fotoProfil' => null, 
            ]);

            // 3. Redirect ke halaman depan (/) setelah register
            return redirect('/')->with('success', 'Pendaftaran berhasil! Silakan Login dengan akun baru Anda.');
            
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['register' => 'Pendaftaran gagal karena masalah server atau kolom wajib database.']);
        }
    }


    /**
     * Memproses login pengguna.
     */
    public function prosesLogin(Request $request)
    {
        $request->validate([
            'identity' => 'required|string',
            'password' => 'required|string',
        ]);

        // Tentukan login type (email atau namaLengkap)
        $loginType = filter_var($request->identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'namaLengkap';

        $credentials = [
            $loginType => $request->identity,
            'password' => $request->password
        ];
        
        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Tentukan default redirect: Admin ke kalender, Customer ke tampilan_opening (HOME)
            $defaultRedirect = $user->role === 'admin'
                ? route('kalenderJadwal')
                : route('tampilan_opening'); // <-- UBAH KE TAMPILAN OPENING

            // Gunakan intended() untuk mengarahkan ke URL yang dilindungi (seperti /booking/create) jika ada.
            // Jika tidak ada URL yang dituju, gunakan defaultRedirect.
            $redirectUrl = redirect()->intended($defaultRedirect)->getTargetUrl();


            return response()->json([
                'status' => 'success',
                'message' => 'Selamat datang, ' . $user->namaLengkap,
                'redirect_url' => $redirectUrl // Kirimkan URL intended atau default
            ]);
        }
        
        // Response jika login gagal
        return response()->json([
            'errors' => [
                'identity' => ['Email/Nama atau Password salah, Coba lagi bro!']
            ]
        ], 422);
    }

    /**
     * Memproses logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}