<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class portofolio extends Model
{
    protected $table = 'portofolio';
    protected $primaryKey = 'idPortofolio';
    protected $fillable = [
        'idJenisLayanan',
        'namaPortofolio',
        'deskripsi',
        'urlPorto',
        'jenisPorto',
        'tanggalPorto',
    ];

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'idJenisLayanan', 'idJenisLayanan');
    }
}
