<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'idPembayaran';
    protected $fillable = [
        'idPemesanan',
        'jumlahBayar',
        'metodePembayaran',
        'statusPembayaran',
        'buktiPembayaran',
        'tanggalPembayaran',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'idPemesanan', 'idPemesanan');
    }
}
