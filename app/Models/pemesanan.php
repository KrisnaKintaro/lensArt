<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';
    protected $primaryKey = 'idPemesanan';
    protected $fillable = [
        'idUser',
        'idSlotJadwal',
        'tanggalPemesanan',
        'lokasiAcara',
        'catatan',
        'statusPemesanan',
        'metodePembayaran',
        'statusPembayaran',
        'buktiPembayaran',
        'totalHarga',
        'tanggalPembayaran',
        'nomorBooking',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }

    public function slotJadwal()
    {
        return $this->belongsTo(SlotJadwal::class, 'idSlotJadwal', 'idSlotJadwal');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'idPemesanan', 'idPemesanan');
    }
}
