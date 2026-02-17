<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Tas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf; // Perbaiki ini
use App\Exports\LaporanExport;
use App\Exports\LaporanTemplateExport;
use App\Imports\LaporanImport;
use App\Exports\LaporanAdvancedExport;
use App\Exports\LaporanMultiSheetExport;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    public function index(Request $request)
    {
        // Ambil query dasar
        $query = Laporan::with('tas');
        
        // Filter berdasarkan platform (offline/all)
        if ($request->filter == 'offline') {
            $query->where('platform', 'offline');
        }
        
        // Filter tanggal
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }
        
        if ($request->filled('ke_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->ke_tanggal);
        }
        
        // Filter platform
        if ($request->filled('platform') && $request->platform != '') {
            $query->where('platform', $request->platform);
        }
        
        // Filter periode
        if ($request->filled('periode')) {
            switch($request->periode) {
                case 'hari_ini':
                    $query->whereDate('tanggal', Carbon::today());
                    break;
                case 'kemarin':
                    $query->whereDate('tanggal', Carbon::yesterday());
                    break;
                case 'minggu_ini':
                    $query->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'bulan_ini':
                    $query->whereMonth('tanggal', Carbon::now()->month)
                        ->whereYear('tanggal', Carbon::now()->year);
                    break;
                case 'tahun_ini':
                    $query->whereYear('tanggal', Carbon::now()->year);
                    break;
            }
        }
        
        // Sorting berdasarkan field tanggal
        if ($request->filled('sort')) {
            switch($request->sort) {
                case 'tanggal_asc':
                    $query->orderBy('tanggal', 'asc');
                    break;
                case 'platform':
                    $query->orderBy('platform', 'asc')->orderBy('tanggal', 'desc');
                    break;
                case 'total_desc':
                    // Untuk sorting berdasarkan total, kita perlu subquery
                    $laporansForStats = $query->get();
                    
                    // Hitung total penjualan untuk sorting
                    $sortedIds = $laporansForStats->sortByDesc(function($item) {
                        return $item->jumlah_terjual * $item->tas->harga;
                    })->pluck('id')->toArray();
                    
                    // Urutkan berdasarkan ID yang sudah di-sort
                    $query->orderByRaw('FIELD(id, ' . implode(',', $sortedIds) . ')');
                    break;
                case 'tanggal_desc':
                default:
                    $query->orderBy('tanggal', 'desc');
                    break;
            }
        } else {
            // Default sorting: berdasarkan tanggal penjualan terbaru
            $query->orderBy('tanggal', 'desc');
        }
        
        // Ambil semua data untuk statistik (tanpa pagination)
        $laporansForStats = $query->get();
        
        // Hitung statistik
        $totalQty = $laporansForStats->sum('jumlah_terjual');
        $totalPenjualan = $laporansForStats->sum(function($laporan) {
            return $laporan->jumlah_terjual * $laporan->tas->harga;
        });
        
        // Hitung platform aktif (unik)
        $platformAktif = $laporansForStats->unique('platform')->count();
        
        // Clone query untuk pagination (tanpa sorting custom yang kompleks)
        if ($request->sort == 'total_desc') {
            // Jika sorting total_desc, kita perlu approach berbeda untuk pagination
            $ids = $sortedIds;
            $currentPage = $request->get('page', 1);
            $perPage = 10;
            $offset = ($currentPage - 1) * $perPage;
            $paginatedIds = array_slice($ids, $offset, $perPage);
            
            $laporans = Laporan::with('tas')
                ->whereIn('id', $paginatedIds)
                ->orderByRaw('FIELD(id, ' . implode(',', $paginatedIds) . ')')
                ->paginate($perPage);
                
            // Set manual pagination
            $laporans->setPath($request->url());
            $laporans->appends($request->query());
        } else {
            // Paginate untuk tabel (gunakan query yang sama dengan sorting simple)
            $laporans = $query->paginate(10);
        }
        
        // Load stokMasuk untuk setiap laporan (untuk menghitung stok per warna)
        $laporans->load('tas.stokMasuk');
        
        return view('laporan.index', compact(
            'laporans', 
            'totalQty', 
            'totalPenjualan', 
            'platformAktif'
        ));
    }

    // Method untuk export Excel
    public function exportExcel(Request $request)
    {
        // Simpan filter ke session untuk digunakan di export
        session([
            'export_dari_tanggal' => $request->dari_tanggal,
            'export_ke_tanggal' => $request->ke_tanggal,
            'export_platform' => $request->platform,
            'export_periode' => $request->periode,
        ]);
        
        $filename = 'laporan-penjualan-' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new LaporanExport, $filename);
    }

    // Method untuk export PDF
    public function exportPdf(Request $request)
    {
        // Query laporan dengan filter
        $query = Laporan::with('tas');
        
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }
        
        if ($request->filled('ke_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->ke_tanggal);
        }
        
        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }
        
        // Tambahkan periode filter jika ada
        if ($request->filled('periode')) {
            $periode = $request->periode;
            $now = now();
            
            switch ($periode) {
                case 'hari_ini':
                    $query->whereDate('tanggal', $now->toDateString());
                    break;
                case 'kemarin':
                    $query->whereDate('tanggal', $now->subDay()->toDateString());
                    break;
                case 'minggu_ini':
                    $query->whereBetween('tanggal', [
                        $now->startOfWeek()->toDateString(),
                        $now->endOfWeek()->toDateString()
                    ]);
                    break;
                case 'bulan_ini':
                    $query->whereBetween('tanggal', [
                        $now->startOfMonth()->toDateString(),
                        $now->endOfMonth()->toDateString()
                    ]);
                    break;
                case 'tahun_ini':
                    $query->whereBetween('tanggal', [
                        $now->startOfYear()->toDateString(),
                        $now->endOfYear()->toDateString()
                    ]);
                    break;
            }
        }
        
        $laporans = $query->orderBy('tanggal', 'desc')->get();
        
        // Hitung total
        $totalTerjual = $laporans->sum('jumlah_terjual');
        $totalPendapatan = $laporans->sum(function($item) {
            return $item->jumlah_terjual * $item->tas->harga;
        });
        
        $data = [
            'laporans' => $laporans,
            'totalTerjual' => $totalTerjual,
            'totalPendapatan' => $totalPendapatan,
            'periode' => $request->periode ?? null,
        ];
        
        // Generate PDF
        $pdf = PDF::loadView('laporan.export-pdf', $data);
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'landscape');
        
        // Download PDF
        return $pdf->download('laporan-penjualan-' . date('Y-m-d') . '.pdf');
    }

    /**
 * Download template Excel untuk import
 */
    public function downloadTemplate()
    {
        return Excel::download(new LaporanTemplateExport, 'template-import-laporan.xlsx');
    }

    public function exportExcelAdvanced(Request $request)
    {
        // Simpan filter ke session
        session([
            'export_dari_tanggal' => $request->dari_tanggal,
            'export_ke_tanggal' => $request->ke_tanggal,
            'export_platform' => $request->platform,
            'export_periode' => $request->periode,
        ]);
        
        $filename = 'laporan-analitik-' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new LaporanAdvancedExport, $filename);
    }

    public function exportExcelMultiSheet(Request $request)
    {
        // Simpan filter ke session
        session([
            'export_dari_tanggal' => $request->dari_tanggal,
            'export_ke_tanggal' => $request->ke_tanggal,
            'export_platform' => $request->platform,
            'export_periode' => $request->periode,
        ]);
        
        $filename = 'laporan-penjualan-detail-' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new LaporanMultiSheetExport, $filename);
    }
    
    /**
     * Export laporan ke CSV
     */
    public function exportCSV(Request $request)
    {
        // Query laporan dengan filter
        $query = Laporan::with('tas');
        
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }
        
        if ($request->filled('ke_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->ke_tanggal);
        }
        
        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }
        
        if ($request->filled('periode')) {
            $periode = $request->periode;
            $now = now();
            
            switch ($periode) {
                case 'hari_ini':
                    $query->whereDate('tanggal', $now->toDateString());
                    break;
                case 'kemarin':
                    $query->whereDate('tanggal', $now->subDay()->toDateString());
                    break;
                case 'minggu_ini':
                    $query->whereBetween('tanggal', [
                        $now->startOfWeek()->toDateString(),
                        $now->endOfWeek()->toDateString()
                    ]);
                    break;
                case 'bulan_ini':
                    $query->whereBetween('tanggal', [
                        $now->startOfMonth()->toDateString(),
                        $now->endOfMonth()->toDateString()
                    ]);
                    break;
                case 'tahun_ini':
                    $query->whereBetween('tanggal', [
                        $now->startOfYear()->toDateString(),
                        $now->endOfYear()->toDateString()
                    ]);
                    break;
            }
        }
        
        $laporans = $query->orderBy('tanggal', 'desc')->get();
        
        $filename = 'laporan-penjualan-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($laporans) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'No', 'Tanggal', 'Kode Tas', 'Nama Tas', 'Warna', 
                'Terjual (pcs)', 'Platform', 'Harga Satuan', 'Total', 
                'Sisa Stok', 'Status Stok', 'Keterangan'
            ]);
            
            // Data
            foreach ($laporans as $index => $laporan) {
                $platformNames = [
                    'shopee' => 'Shopee',
                    'tiktok' => 'TikTok',
                    'offline' => 'Offline',
                    'lainnya' => 'Lainnya'
                ];
                
                $statusStok = $laporan->sisa_stok > 10 ? 'Aman' : 
                             ($laporan->sisa_stok > 0 ? 'Menipis' : 'Kritis');
                
                fputcsv($file, [
                    $index + 1,
                    $laporan->tanggal->format('d/m/Y'),
                    $laporan->tas->kode_tas,
                    $laporan->tas->nama_tas,
                    $laporan->tas->warna_tas,
                    $laporan->jumlah_terjual,
                    $platformNames[$laporan->platform] ?? $laporan->platform,
                    $laporan->tas->harga,
                    $laporan->jumlah_terjual * $laporan->tas->harga,
                    $laporan->sisa_stok,
                    $statusStok,
                    $laporan->keterangan ?? '-'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        // Ubah $tasList menjadi $tas
        $tas = Tas::with('stokMasuk')->get();
        return view('laporan.create', compact('tas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'platform' => 'required|in:shopee,tiktok,offline,lainnya',
            'tas_id' => 'required|exists:tas,id',
            'warna' => 'required|string',
            'jumlah_terjual' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);
        
        // Ambil tas terkait
        $tas = Tas::with('stokMasuk')->findOrFail($request->tas_id);
        
        // Hitung stok untuk warna tertentu dari stok_masuk
        $stokWarna = $tas->stokMasuk->where('warna', $request->warna)->sum('jumlah');
        
        if ($stokWarna < $request->jumlah_terjual) {
            return back()->withErrors([
                'jumlah_terjual' => 'Stok tidak mencukupi untuk warna ' . $request->warna . '. Stok tersedia: ' . $stokWarna
            ]);
        }
        
        // Simpan laporan dengan informasi warna
        $laporan = Laporan::create([
            'tanggal' => $request->tanggal,
            'tas_id' => $request->tas_id,
            'platform' => $request->platform,
            'warna' => $request->warna,
            'jumlah_terjual' => $request->jumlah_terjual,
            'sisa_stok' => $tas->stok - $request->jumlah_terjual,
            'keterangan' => $request->keterangan,
        ]);
        
        // Update stok total tas
        $tas->update([
            'stok' => $tas->stok - $request->jumlah_terjual
        ]);
        
        // Kurangi stok dari record stok_masuk untuk warna tertentu
        $this->updateStokMasuk($tas, $request->warna, $request->jumlah_terjual);
        
        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil ditambahkan.');
    }

    // Helper method untuk update stok masuk
    private function updateStokMasuk($tas, $warna, $jumlah)
    {
        $stokMasukRecords = $tas->stokMasuk()
            ->where('warna', $warna)
            ->orderBy('tanggal_masuk', 'asc')
            ->get();
        
        $remaining = $jumlah;
        
        foreach ($stokMasukRecords as $record) {
            if ($remaining <= 0) break;
            
            if ($record->jumlah >= $remaining) {
                $record->decrement('jumlah', $remaining);
                $remaining = 0;
            } else {
                $remaining -= $record->jumlah;
                $record->update(['jumlah' => 0]);
            }
        }
    }

    public function show(Laporan $laporan)
    {
        // Load relasi stokMasuk untuk menghitung stok per warna
        $laporan->load('tas.stokMasuk');
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