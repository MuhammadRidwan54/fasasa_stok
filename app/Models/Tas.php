<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tas extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_tas',
        'model_tas',
        'nama_tas',
        'warna_tas',
        'stok',
        'harga'
    ];

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }
}