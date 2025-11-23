<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlotJadwal extends Model
{
    protected $table = 'slotJadwal';
    protected $primaryKey = 'idSlotJadwal';
    protected $fillable = [
        'idJenisLayanan',
        'idPaketLayanan',
        'tanggal',
        'jamMulai',
        'jamSelesai',
        'status',
        'catatan',
    ];

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'idJenisLayanan', 'idJenisLayanan');
    }

    public function paketLayanan()
    {
        return $this->belongsTo(PaketLayanan::class, 'idPaketLayanan', 'idPaketLayanan');
    }

    public function pemesanan()
    {
        return $this->hasOne(Pemesanan::class, 'idSlotJadwal', 'idSlotJadwal');
    }
}
