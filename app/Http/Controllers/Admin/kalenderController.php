<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\SlotJadwal;
use App\Models\jenisLayanan;
use App\Models\PaketLayanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class kalenderController extends Controller
{
    public function index()
    {
        $jenisLayanan = jenisLayanan::where('aktif', true)->get();
        return view('admin.pages.kalenderJadwalKerja.kalenderJadwal', compact('jenisLayanan'));
    }

    public function getPaket(Request $request)
    {
        $paket = PaketLayanan::where('idJenisLayanan', $request->idJenisLayanan)->get();
        return response()->json($paket);
    }

    public function getSlotJadwal(Request $request)
    {
        $start = $request->start;
        $end = $request->end;

        $dataPemesanan = Pemesanan::with([
            'slotJadwal.jenisLayanan',
            'slotJadwal.paketLayanan',
            'user'
        ])
            ->where('statusPemesanan', '!=', 'dibatalkan')
            ->whereHas('slotJadwal', function ($query) use ($start, $end) {
                $query->whereDate('tanggal', '>=', $start)
                    ->whereDate('tanggal', '<=', $end)
                    ->where('status', 'terpesan');
            })
            ->get();

        $eventKalender = [];

        foreach ($dataPemesanan as $dp) {
            $slotJadwal = $dp->slotJadwal;

            $startDateTime = $slotJadwal->tanggal . 'T' . $slotJadwal->jamMulai;
            $endDateTime = $slotJadwal->tanggal . 'T' . $slotJadwal->jamSelesai;

            // Untuk tampilan admin
            $eventKalender[] = [
                'id'        => $slotJadwal->idSlotJadwal,
                'title'     => $slotJadwal->jenisLayanan->namaLayanan . ' - ' . $slotJadwal->paketLayanan->namaPaket . ' | Lokasi : ' . $dp->lokasiAcara,
                'start'     => $startDateTime,
                'end'       => $endDateTime,
                'color'     => '#D32F2F',
                'editable'  => false,
                'className' => 'slot-booked',
                'extendedProps' => [
                    'status' => $slotJadwal->status,
                ],
            ];
        }
        return response()->json($eventKalender);
    }

    public function getDataPresentaseBookingHarian(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        $totalJamSehari = 24.0;

        $durasiTerisiHarian = Pemesanan::query()
            ->where('statusPemesanan', '!=', 'dibatalkan')
            ->whereHas('slotJadwal', function ($query) use ($start, $end) {
                $query->whereDate('tanggal', '>=', $start)
                    ->whereDate('tanggal', '<=', $end)
                    ->where('status', 'terpesan');
            })
            ->join('slotJadwal', 'pemesanan.idSlotJadwal', '=', 'slotJadwal.idSlotJadwal')
            ->select(
                'slotJadwal.tanggal',
                DB::raw("SUM(TIME_TO_SEC(TIMEDIFF(slotJadwal.jamSelesai, slotJadwal.jamMulai)) / 3600) as totalJamTerisi")
            )
            ->groupBy('slotJadwal.tanggal')
            ->get();

        $arrayDurasiTerisiHarian = $durasiTerisiHarian->toArray();

        $dataPresentaseHarian = [];
        foreach ($arrayDurasiTerisiHarian as $dataDurasi) {
            $totalJam = (float)$dataDurasi['totalJamTerisi'];
            $persentase = ($totalJam / $totalJamSehari) * 100;

            $className = '';
            if ($persentase > 80) {
                $className = 'day-high';
            } elseif ($persentase > 30) {
                $className = 'day-med';
            } else {
                $className = 'day-low';
            }

            if ($className) {
                $dataPresentaseHarian[] = [
                    'tanggal' => $dataDurasi['tanggal'],
                    'persentase' => round($persentase, 2),
                    'className' => $className,
                ];
            }
        }
        return response()->json($dataPresentaseHarian);
    }

    // --- BAGIAN UTAMA YANG DIUBAH ---
    public function simpanBooking(Request $request)
    {
        try {
            DB::beginTransaction();

            // 1. Logic Upload Bukti (Baru)
            $gambarBukti = null;
            if ($request->metodePembayaran != 'tunai') {
                if ($request->hasFile('buktiPembayaran')) {
                    $file = $request->file('buktiPembayaran');
                    // Kasih nama unik ada '_admin_' biar tau ini uploadan admin
                    $filename = 'bukti_admin_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('gambarBuktiPembayaran'), $filename);
                    $gambarBukti = $filename;
                }
            }

            // 2. Simpan Slot Jadwal
            $slot = SlotJadwal::create([
                'idJenisLayanan' => $request->idJenisLayanan,
                'idPaketLayanan' => $request->idPaketLayanan,
                'tanggal' => $request->tanggal,
                'jamMulai' => $request->jamMulai,
                'jamSelesai' => $request->jamSelesai,
                'status' => 'terpesan',
                'catatan' => $request->catatan,
            ]);

            // 3. Simpan Pemesanan
            $noBooking = 'BOOK-ADM-' . date('Ymd') . rand(100, 999);

            $pemesananBaru = Pemesanan::create([
                'idUser' => Auth::user()->idUser, // Pakai user admin yang sedang login
                'idSlotJadwal' => $slot->idSlotJadwal,
                'tanggalPemesanan' => now(),
                'lokasiAcara' => $request->lokasiAcara,
                'catatan' => $request->catatan,
                'statusPemesanan' => 'pending',

                // Data Pembayaran Baru
                'metodePembayaran' => $request->metodePembayaran,
                'statusPembayaran' => $request->statusPembayaran,
                'buktiPembayaran' => $gambarBukti,
                'totalHarga' => $request->totalHarga,
                'nomorBooking' => $noBooking
            ]);

            // 4. Buat Record di Tabel Pembayaran (Biar sinkron datanya)
            Pembayaran::create([
                'idPemesanan'       => $pemesananBaru->idPemesanan,
                'jumlahBayar'       => $request->totalHarga,
                'metodePembayaran'  => $request->metodePembayaran,
                'statusPembayaran'  => $request->statusPembayaran,
                'buktiPembayaran'   => $gambarBukti,
                'tanggalPembayaran' => now(),
            ]);

            DB::commit();
            return response()->json(['message' => 'Booking berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            // Hapus file gambar kalau gagal simpan di database
            if (isset($filename) && file_exists(public_path('gambarBuktiPembayaran/' . $filename))) {
                unlink(public_path('gambarBuktiPembayaran/' . $filename));
            }
            return response()->json(['message' => 'Gagal simpan: ' . $e->getMessage()], 500);
        }
    }
}
