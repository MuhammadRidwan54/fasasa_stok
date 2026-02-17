<?php

namespace App\Http\Controllers;

use App\Models\Tas;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        // Statistik
        $totalStok = Tas::sum('stok');
        $totalModel = Tas::count();
        $totalNilaiStok = Tas::sum(DB::raw('stok * harga'));
        $stokKritis = Tas::where('stok', '<', 5)->count();
        
        // Data untuk chart
        $penjualanHarian = Laporan::selectRaw('DATE(tanggal) as date, SUM(jumlah_terjual) as total')
            ->whereBetween('tanggal', [now()->subDays(7), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Stok berdasarkan warna
        $stokByWarna = Tas::select('warna_tas', DB::raw('SUM(stok) as total'))
            ->groupBy('warna_tas')
            ->get();
            
        // Tas dengan stok terbanyak
        $tasTerbanyak = Tas::orderBy('stok', 'desc')->take(5)->get();
        
        return view('dashboard.index', compact(
            'totalStok',
            'totalModel',
            'totalNilaiStok',
            'stokKritis',
            'penjualanHarian',
            'stokByWarna',
            'tasTerbanyak'
        ));
    }
}