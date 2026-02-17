<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Tas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf; // Perbaiki ini
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    public function index(Request $request)
    {
        $query = Laporan::with('tas');
        
        // Filter tanggal
        if ($request->has('periode') && $request->periode != '') {
            $today = Carbon::today();
            
            switch ($request->periode) {
                case 'hari_ini':
                    $query->whereDate('tanggal', $today);
                    break;
                case 'kemarin':
                    $query->whereDate('tanggal', $today->subDay());
                    break;
                case 'minggu_ini':
                    $query->whereBetween('tanggal', [$today->startOfWeek(), $today->endOfWeek()]);
                    break;
                case 'bulan_ini':
                    $query->whereMonth('tanggal', date('m'))
                        ->whereYear('tanggal', date('Y'));
                    break;
                case 'tahun_ini':
                    $query->whereYear('tanggal', date('Y'));
                    break;
                case 'custom':
                    if ($request->has('dari_tanggal') && $request->dari_tanggal != '') {
                        $query->whereDate('tanggal', '>=', $request->dari_tanggal);
                    }
                    if ($request->has('sampai_tanggal') && $request->sampai_tanggal != '') {
                        $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
                    }
                    break;
            }
        }
        
        // Filter tas
        if ($request->has('tas_id') && $request->tas_id != '') {
            $query->where('tas_id', $request->tas_id);
        }
        
        // Filter platform - TAMBAHKAN INI
        if ($request->has('platform') && $request->platform != '') {
            $query->where('platform', $request->platform);
        }
        
        // Sortir
        $query->orderBy('tanggal', 'desc');
        
        $laporans = $query->paginate(20);
        $tas = Tas::all();
        
        // Statistik
        $totalTerjual = $query->sum('jumlah_terjual');
        $totalPendapatan = 0;
        
        // Hitung total pendapatan
        foreach ($laporans as $laporan) {
            $totalPendapatan += $laporan->jumlah_terjual * $laporan->tas->harga;
        }
        
        // Hitung jumlah platform berbeda
        $totalPlatforms = $query->distinct('platform')->count('platform');
        
        return view('laporan.index', compact(
            'laporans', 
            'tas', 
            'totalTerjual', 
            'totalPendapatan',
            'totalPlatforms'
        ));
    }

    // Method untuk export Excel
    public function exportExcel(Request $request)
    {
        // Filter yang sama dengan index
        $query = Laporan::with('tas');
        
        if ($request->has('periode') && $request->periode != '') {
            $today = Carbon::today();
            
            switch ($request->periode) {
                case 'hari_ini':
                    $query->whereDate('tanggal', $today);
                    break;
                case 'kemarin':
                    $query->whereDate('tanggal', $today->subDay());
                    break;
                case 'minggu_ini':
                    $query->whereBetween('tanggal', [$today->startOfWeek(), $today->endOfWeek()]);
                    break;
                case 'bulan_ini':
                    $query->whereMonth('tanggal', date('m'))
                        ->whereYear('tanggal', date('Y'));
                    break;
                case 'tahun_ini':
                    $query->whereYear('tanggal', date('Y'));
                    break;
                case 'custom':
                    if ($request->has('dari_tanggal') && $request->dari_tanggal != '') {
                        $query->whereDate('tanggal', '>=', $request->dari_tanggal);
                    }
                    if ($request->has('sampai_tanggal') && $request->sampai_tanggal != '') {
                        $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
                    }
                    break;
            }
        }
        
        if ($request->has('tas_id') && $request->tas_id != '') {
            $query->where('tas_id', $request->tas_id);
        }
        
        $laporans = $query->orderBy('tanggal', 'desc')->get();
        
        return Excel::download(new LaporanExport($laporans), 'laporan-penjualan-' . date('Y-m-d') . '.xlsx');
    }

    // Method untuk export PDF
    public function exportPdf(Request $request)
    {
        // Filter yang sama dengan index
        $query = Laporan::with('tas');
        
        if ($request->has('periode') && $request->periode != '') {
            $today = Carbon::today();
            
            switch ($request->periode) {
                case 'hari_ini':
                    $query->whereDate('tanggal', $today);
                    break;
                case 'kemarin':
                    $query->whereDate('tanggal', $today->subDay());
                    break;
                case 'minggu_ini':
                    $query->whereBetween('tanggal', [$today->startOfWeek(), $today->endOfWeek()]);
                    break;
                case 'bulan_ini':
                    $query->whereMonth('tanggal', date('m'))
                          ->whereYear('tanggal', date('Y'));
                    break;
                case 'tahun_ini':
                    $query->whereYear('tanggal', date('Y'));
                    break;
                case 'custom':
                    if ($request->has('dari_tanggal') && $request->dari_tanggal != '') {
                        $query->whereDate('tanggal', '>=', $request->dari_tanggal);
                    }
                    if ($request->has('sampai_tanggal') && $request->sampai_tanggal != '') {
                        $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
                    }
                    break;
            }
        }
        
        if ($request->has('tas_id') && $request->tas_id != '') {
            $query->where('tas_id', $request->tas_id);
        }
        
        $laporans = $query->orderBy('tanggal', 'desc')->get();
        
        // Hitung total
        $totalTerjual = $laporans->sum('jumlah_terjual');
        $totalPendapatan = 0;
        foreach ($laporans as $laporan) {
            $totalPendapatan += $laporan->jumlah_terjual * $laporan->tas->harga;
        }
        
        // PERBAIKAN: Gunakan Pdf facade dengan benar
        $pdf = Pdf::loadView('laporan.export-pdf', compact('laporans', 'totalTerjual', 'totalPendapatan'));
        
        return $pdf->download('laporan-penjualan-' . date('Y-m-d') . '.pdf');
    }

    public function create()
    {
        $tas = Tas::all();
        return view('laporan.create', compact('tas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tas_id' => 'required|exists:tas,id',
            'tanggal' => 'required|date',
            'jumlah_terjual' => 'required|integer|min:0',
            'platform' => 'required|in:shopee,tiktok,lainnya'
        ]);

        // Cek stok tersedia
        $tas = Tas::findOrFail($request->tas_id);
        
        if ($tas->stok < $request->jumlah_terjual) {
            return back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $tas->stok . ' pcs');
        }

        // Update stok tas
        $tas->stok -= $request->jumlah_terjual;
        $tas->save();

        // Simpan laporan
        Laporan::create([
            'tas_id' => $request->tas_id,
            'tanggal' => $request->tanggal,
            'jumlah_terjual' => $request->jumlah_terjual,
            'platform' => $request->platform, // Tambahkan platform
            'sisa_stok' => $tas->stok,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil disimpan dan stok diperbarui!');
    }

    public function show(Laporan $laporan)
    {
        return view('laporan.show', compact('laporan'));
    }

    public function destroy(Laporan $laporan)
    {
        // Kembalikan stok sebelum menghapus
        $tas = $laporan->tas;
        $tas->stok += $laporan->jumlah_terjual;
        $tas->save();
        
        $laporan->delete();

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil dihapus dan stok dikembalikan!');
    }
}