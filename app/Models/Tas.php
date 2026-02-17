<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tas extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_tas',
        'nama_tas',
        'model_tas',
        'warna_tas',
        'harga',
        'stok',
    ];
    
    protected $casts = [
        'harga' => 'integer',
        'stok' => 'integer',
    ];
    
    /**
     * Relasi ke stok masuk
     */
    public function stokMasuk()
    {
        return $this->hasMany(StokMasuk::class);
    }
    
    /**
     * Relasi ke laporan
     */
    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }
}