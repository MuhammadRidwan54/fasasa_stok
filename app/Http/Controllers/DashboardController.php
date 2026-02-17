<?php

namespace App\Http\Controllers;

use App\Models\Tas;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    public function index()
    {
        // Statistik utama
        $totalStok = Tas::sum('stok');
        $totalModel = Tas::count();
        $nilaiStok = Tas::get()->sum(function($item) {
            return $item->stok * $item->harga;
        });
        
        // Hitung stok berdasarkan status
        $stokKritis = Tas::where('stok', 0)->count();
        $stokMenipis = Tas::whereBetween('stok', [1, 5])->count();
        $stokAman = Tas::where('stok', '>', 5)->count();
        
        // Data untuk chart distribusi stok (top 5 tas)
        $topModels = Tas::orderBy('stok', 'desc')->take(5)->get();
        
        // Data untuk chart status stok
        $statusData = [
            'aman' => $stokAman,
            'menipis' => $stokMenipis,
            'kritis' => $stokKritis
        ];
        
        // Data untuk aktivitas terbaru (transaksi)
        $recentTransactions = Laporan::with('tas')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('dashboard.index', compact(
            'totalStok',
            'totalModel',
            'nilaiStok',
            'stokKritis',
            'stokMenipis',
            'stokAman',
            'topModels',
            'statusData',
            'recentTransactions'
        ));
    }
}