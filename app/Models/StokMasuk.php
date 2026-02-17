<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    use HasFactory;

    protected $table = 'stok_masuk';
    
    protected $fillable = [
        'tas_id',
        'warna',
        'tanggal_masuk',
        'jumlah',
        'keterangan'
    ];
    
    protected $casts = [
        'tanggal_masuk' => 'date',
        'jumlah' => 'integer',
    ];
    
    public function tas()
    {
        return $this->belongsTo(Tas::class);
    }
}
