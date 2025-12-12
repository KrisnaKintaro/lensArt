<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // Titik tiga (...) mengubah input 'admin' menjadi array ['admin']
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('failed', 'Login dulu bosku!');
        }

        $user = Auth::user();

        // 2. Debugging & Cleaning (PENTING!)
        // Kita paksa jadi huruf kecil semua biar 'Customer' dan 'customer' dianggap sama
        $userRole = strtolower(trim($user->role));

        // Kita juga bersihin inputan dari route, misal ada spasi
        $allowedRoles = array_map(function($r) {
            return strtolower(trim($r));
        }, $roles);

        // 3. Cek Role
        if (!in_array($userRole, $allowedRoles)) {
            // JANGAN REDIRECT. Pake abort(403) biar ketahuan masalahnya.
            // Ini bakal nampilin pesan error di layar putih, bukan nge-loop.
            abort(403, 'Akses Ditolak! Role Anda adalah: "' . $user->role . '". Sedangkan halaman ini butuh: "' . implode(', ', $roles) . '"');
        }

        return $next($request);
    }
}
