<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [
        'tas_id',
        'tanggal',
        'jumlah_terjual',
        'platform', // Tambahkan ini
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
            'shopee' => 'bi bi-shop',
            'tiktok' => 'bi bi-tiktok',
            // 'offline' => 'bi bi-shop',
            default => 'bi bi-question-circle'
        };
    }

    // Method untuk mendapatkan warna badge platform
    public function getPlatformColorAttribute()
    {
        return match($this->platform) {
            'shopee' => 'warning',
            'tiktok' => 'dark',
            // 'offline' => 'primary',
            default => 'secondary'
        };
    }

    // Method untuk mendapatkan nama platform lengkap
    public function getPlatformNameAttribute()
    {
        return match($this->platform) {
            'shopee' => 'Shopee',
            'tiktok' => 'TikTok Shop',
            // 'offline' => 'Offline Store',
            default => 'Lainnya'
        };
    }
}
