<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketLayanan extends Model
{
    protected $table = 'paketLayanan';
    protected $primaryKey = 'idPaketLayanan';
    protected $fillable = [
        'idJenisLayanan',
        'namaPaket',
        'deskripsi',
        'jumlahFileEdit',
        'durasiJam',
        'harga',
        'aktif',
    ];

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'idJenisLayanan', 'idJenisLayanan');
    }

    public function slotJadwal()
    {
        return $this->hasMany(SlotJadwal::class, 'idPaketLayanan', 'idPaketLayanan');
    }
}
