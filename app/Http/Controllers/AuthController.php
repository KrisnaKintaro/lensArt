<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function prosesLogin(Request $request)
    {
        $request->validate([
            'identity' => 'required|string',
            'password' => 'required|string',
        ]);

        // Kalau formatnya email,  anggap email. Kalau bukan, anggap namaLengkap.
        $loginType = filter_var($request->identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'namaLengkap';

        $credentials = [
            $loginType => $request->identity,
            'password' => $request->password
        ];
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            $redirectUrl = $user->role === 'admin'
                ? route('kalenderJadwal')
                : route('tampilanCustomer');

            return response()->json([
                'status' => 'success',
                'message' => 'Selamat datang, ' . $user->namaLengkap,
                'redirect_url' => $redirectUrl
            ]);
        }
        return response()->json([
            'errors' => [
                'identity' => ['Email/Nama atau Password salah, Coba lagi bro!']
            ]
        ], 422);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
