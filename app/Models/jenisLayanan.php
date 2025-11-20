<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jenisLayanan extends Model
{
    protected $table = 'jenisLayanan';
    protected $primaryKey = 'idJenisLayanan';
    protected $fillable = [
        'namaLayanan',
        'deskripsi',
        'aktif',
    ];

    public function paket()
    {
        return $this->hasMany(PaketLayanan::class, 'idJenisLayanan', 'idJenisLayanan');
    }

    public function portofolio()
    {
        return $this->hasMany(Portofolio::class, 'idJenisLayanan', 'idJenisLayanan');
    }
}
