<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemesanan;  // Jangan lupa ini
use App\Models\Pembayaran; // Jangan lupa ini

class CustomerTransaksiController extends Controller
{
    // ==========================================
    // 1. FITUR RIWAYAT PEMBAYARAN (YANG UDAH JADI)
    // ==========================================
    public function riwayatPembayaran(Request $request)
    {
        $idUser = Auth::user()->idUser;

        // Base Query: Ambil pembayaran milik user
        $query = Pembayaran::whereHas('pemesanan', function($q) use ($idUser) {
            $q->where('idUser', $idUser);
        });

        // --- LOGIC BARU MULAI DI SINI ---
        // Kita filter querynya dengan logika:
        // Tampilkan jika (Status Pembayaran BUKAN Ditolak)
        // ATAU (Status Pembayaran Ditolak TAPI Pemesanan TIDAK Dibatalkan)
        $query->where(function($q) {
            // Kondisi 1: Ambil semua yang statusnya normal (menunggu, lunas, dp, dll)
            $q->where('statusPembayaran', '!=', 'ditolak')

            // Kondisi 2: Kalau statusnya 'ditolak', cek status pemesanannya
              ->orWhere(function($subQ) {
                  $subQ->where('statusPembayaran', 'ditolak')
                       ->whereHas('pemesanan', function($p) {
                           // Hanya ambil jika pemesanan BELUM dibatalkan
                           $p->where('statusPemesanan', '!=', 'dibatalkan');
                       });
              });
        });
        // --- LOGIC BARU SELESAI ---

        // Filter User dari Dropdown (Optional)
        if ($request->has('status') && $request->status != 'semua') {
            $query->where('statusPembayaran', $request->status);
        }

        $pembayarans = $query->with('pemesanan')->latest('created_at')->get();

        return view('customer.riwayat_pembayaran', compact('pembayarans'));
    }

    // ==========================================
    // 2. FITUR RIWAYAT BOOKING (INI YG BIKIN ERROR)
    // ==========================================
    public function riwayatBooking(Request $request)
    {
        $idUser = Auth::user()->idUser;

        // Ambil data pemesanan milik user + data slot jadwalnya
        $query = Pemesanan::with('slotJadwal')->where('idUser', $idUser);

        // Filter Status
        if ($request->has('status') && $request->status != 'semua') {
            $query->where('statusPemesanan', $request->status);
        }

        // Urutkan dari yg terbaru
        $bookings = $query->latest('tanggalPemesanan')->get();

        // Kirim ke view (halaman)
        return view('customer.riwayat_booking', compact('bookings'));
    }

    // ==========================================
    // 3. FITUR BATALKAN BOOKING
    // ==========================================
    public function cancelBooking($id)
    {
        // Cari bookingnya, pastiin punya user yang login
        $booking = Pemesanan::where('idPemesanan', $id)
                    ->where('idUser', Auth::user()->idUser)
                    ->firstOrFail();

        // Cek apakah status masih pending
        if ($booking->statusPemesanan == 'pending') {
            $booking->update([
                'statusPemesanan' => 'dibatalkan'
            ]);

            // Opsional: Kalau mau status slot jadwal jadi kosong lagi
            // $booking->slotJadwal()->update(['status' => 'kosong']);

            return back()->with('success', 'Booking berhasil dibatalkan.');
        }

        return back()->with('error', 'Booking tidak bisa dibatalkan karena sudah diproses admin.');
    }

    public function updateBuktiPembayaran(Request $request, $id)
    {
        // 1. Validasi harus gambar
        $request->validate([
            'buktiPembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Cari data pembayaran berdasarkan ID dan pastikan punya user yang login
        $pembayaran = Pembayaran::whereHas('pemesanan', function($q) {
            $q->where('idUser', Auth::user()->idUser);
        })->where('idPembayaran', $id)->firstOrFail();

        // 3. Proses Upload Gambar Baru
        if ($request->hasFile('buktiPembayaran')) {
            $file = $request->file('buktiPembayaran');
            // Nama file unik: waktu_namaasli.jpg
            $filename = time() . '_' . $file->getClientOriginalName();

            // Simpan ke folder public/gambarBuktiPembayaran
            $file->move(public_path('gambarBuktiPembayaran'), $filename);

            // 4. Update Database
            // Ganti nama file gambar DAN ubah status jadi 'menunggu' lagi
            $pembayaran->update([
                'buktiPembayaran' => $filename,
                'statusPembayaran' => 'menunggu'
            ]);

            return back()->with('success', 'Bukti pembayaran berhasil diperbarui. Mohon tunggu verifikasi admin.');
        }

        return back()->with('error', 'Gagal mengupload gambar.');
    }

}
