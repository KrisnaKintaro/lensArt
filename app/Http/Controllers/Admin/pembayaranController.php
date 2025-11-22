<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pemesanan;

class pembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with('pemesanan.user')
            ->whereIn('statusPembayaran', ['menunggu', 'dp'])
            ->latest()
            ->get();

        return view('admin.pages.pemesanan.verifikasiPembayaran', compact('pembayaran'));
    }

    public function updateStatusPembayaran(Request $request)
    {
        $pembayaran = Pembayaran::find($request->idPembayaran);
        $pembayaran->statusPembayaran = $request->statusPembayaran;
        if ($request->statusPembayaran == 'lunas' || $request->statusPembayaran == 'dp') {
            $pembayaran->tanggalPembayaran = now();
        }
        $pembayaran->save();

        $pemesanan = Pemesanan::find($pembayaran->idPemesanan);
        if ($pemesanan) {
            $pemesanan->statusPembayaran = $request->statusPembayaran;
            if ($request->statusPembayaran == 'lunas' || $request->statusPembayaran == 'dp') {
                $pemesanan->tanggalPembayaran = now();
            }
            $pemesanan->save();
        }
        return response()->json(['success' =>true]);
    }
}
