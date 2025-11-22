<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function index()
    {
        $pemesanan = Pemesanan::with([
            'user',
            'slotJadwal.jenisLayanan',
            'slotJadwal.paketLayanan',
            'pembayaran',
        ])->latest('created_at')->get();
        return view('admin.pages.pemesanan.lihatPemesanan', compact('pemesanan'));
    }

    public function updateStatusBooking(Request $request)
    {
        $pemesanan = Pemesanan::find($request->idPemesanan);
        $pemesanan->statusPemesanan = $request->statusPemesanan;
        $pemesanan->save();

        return response()->json(['success' => true]);
    }

    public function updateStatusPembayaran(Request $request)
    {
        $pemesanan = Pemesanan::find($request->idPemesanan);
        $pemesanan->statusPembayaran = $request->statusPembayaran;
        $pemesanan->tanggalPembayaran = now();
        $pemesanan->save();

        $pembayaranTerakhir = $pemesanan->pembayaran()->latest()->first();

        if ($pembayaranTerakhir) {
            $pembayaranTerakhir->statusPembayaran = $request->statusPembayaran;
            $pembayaranTerakhir->save(); 
        }

        return response()->json(['success' => true]);
    }
}
