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

        $loginType = filter_var($request->identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'namaLengkap';

        $credentials = [
            $loginType => $request->identity,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // 1. Ambil role dan pastikan huruf kecil & bersih dari spasi
            // Sesuai dengan enum database lu: 'customer' atau 'admin'
            $role = strtolower(trim($user->role));

            // 2. Default Redirect (Jaga-jaga kalau role gak kebaca)
            $redirectTarget = '/';

            // 3. Logic Redirect
            if ($role === 'admin') {
                $redirectTarget = route('kalenderJadwal');
            } elseif ($role === 'customer') {
                // INI TARGET CUSTOMER -> Langsung ke Form Booking
                $redirectTarget = route('tampilanBookingCustomer');
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Selamat datang, ' . $user->namaLengkap,
                'role' => $role, // Kirim role yang udah dibersihkan
                'redirect_url' => $redirectTarget
            ]);
        }

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
