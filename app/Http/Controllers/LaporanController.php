<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Tas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LaporanExport;
use App\Exports\LaporanTemplateExport;
use App\Imports\LaporanImport;
use App\Exports\LaporanAdvancedExport;
use App\Exports\LaporanMultiSheetExport;
use Illuminate\Support\Facades\Log;

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
        
        // Filter platform - MULTIPLE SELECT
        if ($request->has('platform') && !empty($request->platform)) {
            $platforms = $request->platform;
            
            // Hapus nilai kosong dari array
            if (is_array($platforms)) {
                $platforms = array_filter($platforms);
                
                if (!empty($platforms)) {
                    $query->whereIn('platform', $platforms);
                }
            } else {
                // Untuk backward compatibility
                $query->where('platform', $platforms);
            }
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
                    if (!empty($sortedIds)) {
                        $query->orderByRaw('FIELD(id, ' . implode(',', $sortedIds) . ')');
                    }
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
        if ($request->sort == 'total_desc' && isset($sortedIds) && !empty($sortedIds)) {
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
            $laporans = $query->paginate(500);
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
        
        // Filter platform - MULTIPLE SELECT
        if ($request->has('platform') && !empty($request->platform)) {
            $platforms = $request->platform;
            
            if (is_array($platforms)) {
                $platforms = array_filter($platforms);
                if (!empty($platforms)) {
                    $query->whereIn('platform', $platforms);
                }
            } else {
                $query->where('platform', $platforms);
            }
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
            'selectedPlatforms' => $request->platform ?? [],
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
        try {
            // Query laporan dengan filter
            $query = Laporan::with('tas');
            
            // Filter tanggal
            if ($request->filled('dari_tanggal')) {
                $query->whereDate('tanggal', '>=', $request->dari_tanggal);
            }
            
            if ($request->filled('ke_tanggal')) {
                $query->whereDate('tanggal', '<=', $request->ke_tanggal);
            }
            
            // Filter platform - MULTIPLE SELECT
            if ($request->has('platform') && !empty($request->platform)) {
                $platforms = $request->platform;
                
                if (is_array($platforms)) {
                    $platforms = array_filter($platforms);
                    if (!empty($platforms)) {
                        $query->whereIn('platform', $platforms);
                    }
                } else {
                    $query->where('platform', $platforms);
                }
            }
            
            // Filter periode
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
            
            // Hitung total untuk ringkasan
            $totalTerjual = $laporans->sum('jumlah_terjual');
            $totalPendapatan = $laporans->sum(function($laporan) {
                return $laporan->jumlah_terjual * $laporan->tas->harga;
            });
            
            // Nama file dengan timestamp
            $filename = 'laporan-penjualan-' . date('Y-m-d_H-i-s') . '.csv';
            
            // Headers untuk download
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];
            
            $callback = function() use ($laporans, $totalTerjual, $totalPendapatan, $request) {
                $file = fopen('php://output', 'w');
                
                // Set BOM untuk UTF-8 (mengatasi masalah karakter di Excel)
                fputs($file, "\xEF\xBB\xBF");
                
                // ===== BARIS 1: JUDUL LAPORAN =====
                fputcsv($file, ['LAPORAN PENJUALAN TAS FASASA']);
                fputcsv($file, ['']);
                
                // ===== BARIS 2-4: INFORMASI PERIODE =====
                $periodeText = 'PERIODE: ';
                if ($request->filled('periode')) {
                    $periodeMap = [
                        'hari_ini' => 'Hari Ini',
                        'kemarin' => 'Kemarin',
                        'minggu_ini' => 'Minggu Ini',
                        'bulan_ini' => 'Bulan Ini',
                        'tahun_ini' => 'Tahun Ini'
                    ];
                    $periodeText .= $periodeMap[$request->periode] ?? ucfirst($request->periode);
                } elseif ($request->filled('dari_tanggal') && $request->filled('ke_tanggal')) {
                    $dari = date('d/m/Y', strtotime($request->dari_tanggal));
                    $ke = date('d/m/Y', strtotime($request->ke_tanggal));
                    $periodeText .= $dari . ' s/d ' . $ke;
                } else {
                    $periodeText .= 'Semua Periode';
                }
                fputcsv($file, [$periodeText]);
                
                // Filter platform info
                if ($request->has('platform') && !empty($request->platform)) {
                    $platforms = $request->platform;
                    if (is_array($platforms)) {
                        $platformNames = array_map(function($p) {
                            $names = ['shopee' => 'Shopee', 'tiktok' => 'TikTok', 'offline' => 'Offline', 'affiliate' => 'Affiliate', 'lainnya' => 'Lainnya'];
                            return $names[$p] ?? $p;
                        }, $platforms);
                        fputcsv($file, ['PLATFORM: ' . implode(', ', $platformNames)]);
                    } else {
                        fputcsv($file, ['PLATFORM: ' . $platforms]);
                    }
                }
                
                fputcsv($file, ['Tanggal Cetak: ' . date('d/m/Y H:i:s') . ' WIB']);
                fputcsv($file, ['']);
                
                // ===== BARIS 5-6: RINGKASAN =====
                fputcsv($file, ['RINGKASAN']);
                fputcsv($file, [
                    'Total Transaksi: ' . $laporans->count() . ' transaksi',
                    'Total Terjual: ' . $totalTerjual . ' pcs',
                    'Total Pendapatan: Rp ' . number_format($totalPendapatan, 0, ',', '.'),
                    'Platform Aktif: ' . $laporans->unique('platform')->count() . ' platform'
                ]);
                fputcsv($file, ['']);
                fputcsv($file, ['']);
                
                // ===== HEADER TABLE =====
                fputcsv($file, [
                    'NO',
                    'TANGGAL',
                    'KODE TAS',
                    'NAMA TAS',
                    'WARNA',
                    'TERJUAL (PCS)',
                    'PLATFORM',
                    'HARGA SATUAN (Rp)',
                    'TOTAL (Rp)',
                    'SISA STOK (PCS)',
                    'STATUS STOK',
                    'KETERANGAN'
                ]);
                
                // ===== DATA TABLE =====
                foreach ($laporans as $index => $laporan) {
                    $platformNames = [
                        'shopee' => 'Shopee',
                        'tiktok' => 'TikTok',
                        'offline' => 'Offline',
                        'affiliate' => 'Affiliate',
                        'lainnya' => 'Lainnya'
                    ];
                    
                    $statusStok = $laporan->sisa_stok > 10 ? 'Aman' : 
                                ($laporan->sisa_stok > 0 ? 'Menipis' : 'Kritis');
                    
                    $hargaSatuan = $laporan->tas->harga;
                    $totalHarga = $laporan->jumlah_terjual * $hargaSatuan;
                    
                    fputcsv($file, [
                        $index + 1,
                        $laporan->tanggal->format('d/m/Y'),
                        $laporan->tas->kode_tas,
                        $laporan->tas->nama_tas,
                        $laporan->warna ?? $laporan->tas->warna_tas,
                        $laporan->jumlah_terjual,
                        $platformNames[$laporan->platform] ?? $laporan->platform,
                        number_format($hargaSatuan, 0, ',', '.'),
                        number_format($totalHarga, 0, ',', '.'),
                        $laporan->sisa_stok,
                        $statusStok,
                        $laporan->keterangan ?? '-'
                    ]);
                }
                
                // ===== BARIS TOTAL =====
                if ($laporans->count() > 0) {
                    fputcsv($file, ['']);
                    fputcsv($file, ['TOTAL KESELURUHAN']);
                    fputcsv($file, [
                        '',
                        '',
                        '',
                        '',
                        '',
                        $totalTerjual,
                        '',
                        '',
                        'Rp ' . number_format($totalPendapatan, 0, ',', '.'),
                        '',
                        '',
                        ''
                    ]);
                }
                
                // ===== FOOTER =====
                fputcsv($file, ['']);
                fputcsv($file, ['']);
                fputcsv($file, ['Catatan:']);
                fputcsv($file, ['- Status Stok: Aman (>10 pcs), Menipis (1-10 pcs), Kritis (0 pcs)']);
                fputcsv($file, ['- Laporan ini dibuat secara otomatis oleh Sistem Stok Fasasa']);
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Export CSV error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengexport data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $tas = Tas::with('stokMasuk')->get();
        return view('laporan.create', compact('tas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'platform' => 'required|in:shopee,tiktok,offline,affiliate,lainnya',
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Laporan $laporan)
    {
        $tas = Tas::with('stokMasuk')->get();
        return view('laporan.edit', compact('laporan', 'tas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Laporan $laporan)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'platform' => 'required|in:shopee,tiktok,offline,affiliate,lainnya',
            'tas_id' => 'required|exists:tas,id',
            'warna' => 'required|string',
            'jumlah_terjual' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);
        
        // Ambil tas terkait
        $tas = Tas::with('stokMasuk')->findOrFail($request->tas_id);
        
        // Hitung stok untuk warna tertentu dari stok_masuk
        $stokWarna = $tas->stokMasuk->where('warna', $request->warna)->sum('jumlah');
        
        // Jika warna berubah atau jumlah berubah, validasi stok
        if ($request->warna != $laporan->warna || $request->jumlah_terjual != $laporan->jumlah_terjual) {
            // Kembalikan stok lama terlebih dahulu untuk perhitungan
            $stokTersedia = $stokWarna + ($laporan->warna == $request->warna ? $laporan->jumlah_terjual : 0);
            
            if ($stokTersedia < $request->jumlah_terjual) {
                return back()->withErrors([
                    'jumlah_terjual' => 'Stok tidak mencukupi untuk warna ' . $request->warna . '. Stok tersedia: ' . $stokTersedia
                ])->withInput();
            }
        }
        
        // Kembalikan stok lama
        $this->returnStokToMasuk($laporan->tas, $laporan->warna, $laporan->jumlah_terjual);
        
        // Update stok total tas (kembalikan dulu)
        $laporan->tas->increment('stok', $laporan->jumlah_terjual);
        
        // Update laporan
        $laporan->update([
            'tanggal' => $request->tanggal,
            'tas_id' => $request->tas_id,
            'platform' => $request->platform,
            'warna' => $request->warna,
            'jumlah_terjual' => $request->jumlah_terjual,
            'sisa_stok' => $tas->stok - $request->jumlah_terjual,
            'keterangan' => $request->keterangan,
        ]);
        
        // Kurangi stok baru
        $this->updateStokMasuk($tas, $request->warna, $request->jumlah_terjual);
        
        // Update stok total tas (kurangi dengan jumlah baru)
        $tas->decrement('stok', $request->jumlah_terjual);
        
        return redirect()->route('laporan.show', $laporan->id)
            ->with('success', 'Laporan berhasil diupdate.');
    }

    /**
     * Return stock for a specific laporan (kembalikan semua stok)
     */
    public function returnStok(Laporan $laporan)
    {
        // Kembalikan stok ke stok_masuk
        $this->returnStokToMasuk($laporan->tas, $laporan->warna, $laporan->jumlah_terjual);
        
        // Update stok total tas
        $laporan->tas->increment('stok', $laporan->jumlah_terjual);
        
        // Update sisa_stok di laporan
        $laporan->update([
            'sisa_stok' => $laporan->tas->stok
        ]);
        
        // Optional: Bisa juga menambahkan catatan di keterangan
        $laporan->update([
            'keterangan' => ($laporan->keterangan ? $laporan->keterangan . ' | ' : '') . 
                            'Stok direturn pada ' . now()->format('d/m/Y H:i')
        ]);
        
        return redirect()->route('laporan.show', $laporan->id)
            ->with('success', 'Stok berhasil dikembalikan. Jumlah stok ' . $laporan->tas->nama_tas . ' (' . $laporan->warna . ') bertambah ' . $laporan->jumlah_terjual . ' pcs.');
    }

    /**
     * Helper method untuk mengembalikan stok ke stok_masuk
     */
    private function returnStokToMasuk($tas, $warna, $jumlah)
    {
        // Cari record stok_masuk dengan warna yang sama, prefer yang terakhir di-update
        $stokMasukRecord = $tas->stokMasuk()
            ->where('warna', $warna)
            ->orderBy('updated_at', 'desc')
            ->first();
        
        if ($stokMasukRecord) {
            // Jika ada, tambahkan stok ke record yang ada
            $stokMasukRecord->increment('jumlah', $jumlah);
        } else {
            // Jika tidak ada, buat record baru
            $tas->stokMasuk()->create([
                'warna' => $warna,
                'jumlah' => $jumlah,
                'tanggal_masuk' => now(),
                'keterangan' => 'Return stok dari laporan'
            ]);
        }
    }
}