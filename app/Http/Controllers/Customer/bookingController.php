<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\pemesanan;
use App\Models\SlotJadwal;
use App\Models\jenisLayanan;
use App\Models\PaketLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class bookingController extends Controller
{
    public function index()
    {
        $jenisLayanan = jenisLayanan::where('aktif', true)->get();
        return view('customer.booking.tampilanBooking', compact('jenisLayanan'));
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
            ->whereHas('slotJadwal', function ($query) use ($start, $end) {
                $query->whereDate('tanggal', '>=', $start)
                    ->whereDate('tanggal', '<=', $end)
                    ->where('status', 'terpesan');
            })
            ->get();

        $eventKalender = [];

        // Loop data event per slot jam
        foreach ($dataPemesanan as $dp) {
            $slotJadwal = $dp->slotJadwal;

            $startDateTime = $slotJadwal->tanggal . 'T' . $slotJadwal->jamMulai;
            $endDateTime = $slotJadwal->tanggal . 'T' . $slotJadwal->jamSelesai;

            // Untuk tampilan admin
            // $eventKalender[] = [
            //     'id'        => $slotJadwal->idSlotJadwal,
            //     'title'     => $slotJadwal->jenisLayanan->namaLayanan . ' - ' . $slotJadwal->paketLayanan->namaPaket .' | Lokasi : ' .$dp->lokasiAcara,
            //     'start'     => $startDateTime,
            //     'end'       => $endDateTime,
            //     'color'     => '#D32F2F',
            //     'editable'  => false, // Agar tidak bisa digeser di tampilan jam
            //     'className' => 'slot-booked', // Kelas untuk styling

            //     'extendedProps' => [
            //         'status' => $slotJadwal->status,
            //     ],
            // ];

            // Untuk tampilan customer
            $eventKalender[] = [
                'id'        => $slotJadwal->idSlotJadwal,
                'title'     => 'SUDAH DI BOOKING',
                'start'     => $startDateTime,
                'end'       => $endDateTime,
                'color'     => '#D32F2F',
                'editable'  => false, // Agar tidak bisa digeser di tampilan jam
                'className' => 'slot-booked', // Kelas untuk styling

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

        // Hitung total jam terisi per tanggal
        $durasiTerisiHarian = Pemesanan::whereHas('slotJadwal', function ($query) use ($start, $end) {
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
            // 60% - 100%
            if ($persentase > 60) {
                $className = 'day-high';
                // 20% - 60%
            } elseif ($persentase > 20) {
                $className = 'day-med';
                // 0% - 20%
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

    public function simpanBooking(Request $request)
    {
        try {
            DB::beginTransaction();

            $slot = SlotJadwal::create([
                'idJenisLayanan' => $request->idJenisLayanan,
                'idPaketLayanan' => $request->idPaketLayanan,
                'tanggal' => $request->tanggal,
                'jamMulai' => $request->jamMulai,
                'jamSelesai' => $request->jamSelesai,
                'status' => 'terpesan',
                'catatan' => $request->catatan,
            ]);

            $noBooking = 'BOOK-' . date('Ymd') . rand(100, 999);
            pemesanan::create([
                // 'idUser' => Auth::user()->idUser,
                'idUser' => 1,
                'idSlotJadwal' => $slot->idSlotJadwal,
                'tanggalPemesanan' => now(),
                'lokasiAcara' => $request->lokasiAcara,
                'catatan' => $request->catatan,
                'statusPemesanan' => 'pending',
                'statusPembayaran' => 'menunggu',
                'totalHarga' => $request->totalHarga,
                'nomorBooking' => $noBooking
            ]);

            DB::commit();
            return response()->json(['message' => 'Booking berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal simpan: ' . $e->getMessage()], 500);
        }
    }
}

