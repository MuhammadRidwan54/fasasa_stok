<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans'; // Pastikan ini benar
    
    protected $fillable = [
        'tas_id',
        'tanggal',
        'platform', // Tambahkan ini
        'warna',    // Tambahkan ini
        'jumlah_terjual',
        'sisa_stok',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function tas()
    {
        return $this->belongsTo(Tas::class);
    }

    // Method untuk mendapatkan platform dalam bentuk icon
    public function getPlatformIconAttribute()
    {
        return match($this->platform) {
            'shopee' => 'bi-shop',
            'tiktok' => 'bi-tiktok',
            'offline' => 'bi-shop-window',
            default => 'bi-three-dots'
        };
    }

    // Method untuk mendapatkan warna badge platform
    public function getPlatformColorAttribute()
    {
        return match($this->platform) {
            'shopee' => '#ee4d2d', // Warna oranye Shopee
            'tiktok' => '#000000', // Hitam
            'offline' => '#3498db', // Biru
            default => '#95a5a6'   // Abu-abu
        };
    }

    // Method untuk mendapatkan nama platform lengkap
    public function getPlatformNameAttribute()
    {
        return match($this->platform) {
            'shopee' => 'Shopee',
            'tiktok' => 'TikTok Shop',
            'offline' => 'Offline Store',
            default => 'Lainnya'
        };
    }
    
    // Method untuk mendapatkan total harga
    public function getTotalHargaAttribute()
    {
        return $this->jumlah_terjual * $this->tas->harga;
    }
}