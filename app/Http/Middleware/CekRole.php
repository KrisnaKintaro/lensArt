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
        if (!Auth::check()) {
            return redirect()->route('login')->with('failed', 'Anda belum login, silakan login dulu cuy!');
        }

        $user = Auth::user();
        if (!in_array(Auth::user()->role, $roles)) {
            Auth::logout();
            return redirect()->route('login')->withErrors('failed', 'Eits, Anda tidak punya akses admin! (Auto Logout)');
        }
        return $next($request);
    }
}
