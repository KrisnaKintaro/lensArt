<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class laporanPendapatanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Cek Filter: Kalau user gak milih bulan/tahun, pake bulan/tahun sekarang
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // 2. Query Utama (Jantungnya Laporan)
        // Syarat: Status Pemesanan = Selesai DAN Pembayaran = Lunas
        $query = Pemesanan::with(['user', 'slotJadwal.paketLayanan']) // Load relasi biar ga berat (Eager Loading)
            ->where('statusPemesanan', 'selesai')
            ->where('statusPembayaran', 'lunas')
            ->whereMonth('tanggalPemesanan', $bulan)
            ->whereYear('tanggalPemesanan', $tahun);

        // 3. Ambil Datanya
        $dataLaporan = $query->get(); // Ini buat ditampilin di Tabel

        // 4. Hitung Total Duit (Income)
        $totalPendapatan = $query->sum('totalHarga');

        // 5. Siapin Data Buat Chart (Grafik)
        // Kita kelompokkan data berdasarkan tanggal, terus dijumlah duitnya per tanggal
        $chartDataRaw = $query->selectRaw('DATE(tanggalPemesanan) as tanggal, SUM(totalHarga) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Pisahin Label (Tanggal) dan Values (Duit) buat dikirim ke Chart.js
        $chartLabels = $chartDataRaw->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('d M'); // Format jadi "15 Dec"
        });
        $chartValues = $chartDataRaw->pluck('total');

        // 6. Kirim semua variable ke View
        return view('admin.pages.laporan.laporanPendapatan', compact(
            'dataLaporan',
            'totalPendapatan',
            'chartLabels',
            'chartValues',
            'bulan',
            'tahun'
        ));
    }
}
