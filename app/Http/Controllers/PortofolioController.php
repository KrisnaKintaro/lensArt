<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Pemesanan; // Model Pemesanan sudah diimpor
use App\Models\PaketLayanan;// <-- PASTIKAN INI ADALAH MODEL YANG BENAR UNTUK TABEL paketLayanan

class PortofolioController extends Controller
{
    // Method lama Anda
    public function layanan()
    {
        return view('customer.layanan'); 
    }

    /**
     * Menampilkan formulir booking (Dipanggil oleh route('booking.create')).
     * Membutuhkan parameter 'paket_id' dari URL.
     */
    public function createBooking(Request $request)
    {
        // 1. Ambil idPaketLayanan dari URL (?paket_id=X)
        $paketId = $request->query('paket_id');
        
        // 2. Cari data paket di database
        $paket = PaketLayanan::find($paketId); 

        // Penanganan jika paket tidak ditemukan
        if (!$paket) {
            return redirect()->route('layanan.index')->withErrors(['error' => 'Paket layanan tidak ditemukan.']);
        }
        
        // 3. Tampilkan view booking yang kita rancang
        // Asumsi view ada di 'booking.create'
        return view('booking.create', [
            'paket' => $paket
        ]);
    }
    
    /**
     * Memproses dan menyimpan data pemesanan dari formulir (Dipanggil oleh route('booking.store')).
     */
    public function storeBooking(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'idPaketLayanan' => 'required|exists:paketLayanan,idPaketLayanan', // Pastikan ID paket valid
            'tanggalPemesanan' => 'required|date|after_or_equal:today', 
            'idSlotJadwal' => 'required|integer', // Asumsi ada validasi slot jadwal (nanti bisa diperkuat)
            'lokasiAcara' => 'required|string|max:255',
            'metodePembayaran' => 'required|in:transferBank,eWallet,tunai',
            'catatan' => 'nullable|string',
            'totalHarga' => 'required|numeric',
        ]);
        
        // 2. Membuat Nomor Booking Unik (Contoh sederhana)
        $nomorBooking = 'BOOK-' . time() . Auth::id();

        // 3. Simpan data ke tabel Pemesanan
        try {
            Pemesanan::create([
                'idUser' => Auth::id(),
                'idPaketLayanan' => $request->idPaketLayanan,
                'tanggalPemesanan' => $request->tanggalPemesanan,
                'idSlotJadwal' => $request->idSlotJadwal,
                'lokasiAcara' => $request->lokasiAcara,
                'catatan' => $request->catatan,
                'metodePembayaran' => $request->metodePembayaran,
                'totalHarga' => $request->totalHarga,
                'nomorBooking' => $nomorBooking,
                'statusPemesanan' => 'menunggu', // Status default
            ]);

            // 4. Redirect ke halaman riwayat booking customer
            return redirect()->route('customer.riwayatBooking')->with('success', 'Pemesanan Berhasil! Nomor Booking Anda: ' . $nomorBooking);

        } catch (\Exception $e) {
            // Tangani error jika gagal menyimpan ke database
            return redirect()->back()->withInput()->withErrors(['booking_error' => 'Gagal membuat pemesanan. Coba lagi.']);
        }
    }
    
    // ... Tambahkan method lain yang dibutuhkan PortofolioController di sini ...
    // public function riwayatBooking() { ... }
}