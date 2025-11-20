<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class slotJadwal extends Model
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
}
