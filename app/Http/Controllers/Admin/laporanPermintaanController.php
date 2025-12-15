<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class laporanPermintaanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filter Bulan & Tahun
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // 2. Query SEMUA Data (Tanpa filter status tertentu)
        $query = Pemesanan::with(['user', 'slotJadwal.paketLayanan'])
            ->whereMonth('tanggalPemesanan', $bulan)
            ->whereYear('tanggalPemesanan', $tahun);

        $dataLaporan = $query->get();

        // 3. Data buat Chart (Hitung jumlah per Status Pemesanan)
        // Hasilnya misal: ['pending' => 5, 'selesai' => 10, 'dibatalkan' => 2]
        $chartDataRaw = $query->selectRaw('statusPemesanan, COUNT(*) as jumlah')
            ->groupBy('statusPemesanan')
            ->pluck('jumlah', 'statusPemesanan');

        // Pisahin Label (Nama Status) dan Values (Jumlahnya)
        $chartLabels = $chartDataRaw->keys()->map(function($status){
            return ucfirst($status); // Biar huruf depan besar (Pending, Selesai)
        });
        $chartValues = $chartDataRaw->values();

        return view('admin.pages.laporan.laporanPermintaan', compact(
            'dataLaporan',
            'chartLabels',
            'chartValues',
            'bulan',
            'tahun'
        ));
    }
}
