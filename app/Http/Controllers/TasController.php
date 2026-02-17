<?php

namespace App\Http\Controllers;

use App\Models\Tas;
use Illuminate\Http\Request;
use App\Models\StokMasuk;
use Illuminate\Support\Facades\DB; // Tambah ini jika perlu DB

class TasController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    public function index(Request $request)
    {
        $query = Tas::query();
        
        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_tas', 'like', "%{$search}%")
                  ->orWhere('model_tas', 'like', "%{$search}%")
                  ->orWhere('nama_tas', 'like', "%{$search}%");
            });
        }
        
        // Filter warna
        if ($request->has('warna') && $request->warna != '') {
            $query->where('warna_tas', $request->warna);
        }
        
        // Filter status stok
        if ($request->has('status') && $request->status != '') {
            switch ($request->status) {
                case 'aman':
                    $query->where('stok', '>', 10);
                    break;
                case 'menipis':
                    $query->whereBetween('stok', [1, 10]);
                    break;
                case 'kritis':
                    $query->where('stok', 0);
                    break;
            }
        }
        
        // Sortir
        $query->orderBy('created_at', 'desc');
        
        // Pagination
        $tas = $query->paginate(15);
        
        // PERBAIKAN: Hitung statistik dari query utama (bukan dari hasil pagination)
        $totalStokQuery = clone $query;
        $totalStok = $totalStokQuery->sum('stok');
        
        // Untuk total nilai, kita hitung manual
        $totalNilai = 0;
        $stokKritis = 0;
        
        // Ambil semua data (tanpa pagination) untuk perhitungan
        $allTas = $query->get();
        foreach ($allTas as $item) {
            $totalNilai += $item->stok * $item->harga;
            if ($item->stok == 0) {
                $stokKritis++;
            }
        }
        
        return view('tas.index', compact('tas', 'totalStok', 'totalNilai', 'stokKritis'));
    }

    public function create()
    {
        return view('tas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_tas' => 'required|unique:tas,kode_tas',
            'nama_tas' => 'required',
            'model_tas' => 'required',
            'harga' => 'required|numeric|min:1',
            'warna_tas' => 'required|array|min:1',
            'stok' => 'required|array|min:1',
            'tanggal_masuk' => 'required|array|min:1',
        ]);
        
        // Hitung total stok dari semua warna
        $totalStok = array_sum($request->stok);
        
        // Simpan data tas (hanya data master)
        $tas = Tas::create([
            'kode_tas' => $request->kode_tas,
            'nama_tas' => $request->nama_tas,
            'model_tas' => $request->model_tas,
            'harga' => $request->harga,
            'warna_tas' => implode(', ', $request->warna_tas), // Simpan sebagai string
            'stok' => $totalStok,
        ]);
        
        // Simpan detail stok masuk untuk setiap warna
        foreach ($request->warna_tas as $index => $warna) {
            $tas->stokMasuk()->create([
                'warna' => $warna,
                'jumlah' => $request->stok[$index] ?? 0,
                'tanggal_masuk' => $request->tanggal_masuk[$index] ?? now(),
                'keterangan' => 'Stok awal'
            ]);
        }
        
        return redirect()->route('tas.index')
            ->with('success', 'Tas berhasil ditambahkan.');
    }

    public function show(Tas $ta)
    {
        $ta = Tas::with(['stokMasuk' => function($query) {
            $query->orderBy('tanggal_masuk', 'desc');
        }])->findOrFail($ta->id);
        return view('tas.show', compact('ta'));
    }

    public function edit(Tas $ta)
    {
        $tas = Tas::with('stokMasuk')->findOrFail($ta->id);
        return view('tas.edit', compact('tas'));
    }

    // public function update(Request $request, Tas $ta)
    // {
    //     $request->validate([
    //         'kode_tas' => 'required|max:50|unique:tas,kode_tas,' . $ta->id,
    //         'model_tas' => 'required|max:100',
    //         'nama_tas' => 'required|max:100',
    //         'warna_tas' => 'required|max:50',
    //         'stok' => 'required|integer|min:0'
    //     ]);

    //     $ta->update($request->all());

    //     return redirect()->route('tas.index')
    //         ->with('success', 'Data tas berhasil diupdate!');
    // }

    public function update(Request $request, $id)
    {
        // Validasi hanya data dasar dulu
        $validated = $request->validate([
            'kode_tas' => 'required|unique:tas,kode_tas,' . $id,
            'nama_tas' => 'required',
            'model_tas' => 'required',
            'harga' => 'required|numeric|min:1',
        ]);
        
        $tas = Tas::findOrFail($id);
        
        // Update data dasar
        $tas->update([
            'kode_tas' => $request->kode_tas,
            'nama_tas' => $request->nama_tas,
            'model_tas' => $request->model_tas,
            'harga' => $request->harga,
        ]);
        
        // Hapus semua stok masuk lama
        $tas->stokMasuk()->delete();
        
        // Buat stok masuk baru dari form
        if ($request->has('warna_tas')) {
            $totalStok = 0;
            $warnaList = [];
            
            foreach ($request->warna_tas as $index => $warna) {
                $stok = $request->stok[$index] ?? 0;
                $tanggalMasuk = $request->tanggal_masuk[$index] ?? now();
                
                // Simpan stok masuk
                $tas->stokMasuk()->create([
                    'warna' => $warna,
                    'jumlah' => $stok,
                    'tanggal_masuk' => $tanggalMasuk,
                    'keterangan' => 'Update dari edit form'
                ]);
                
                $totalStok += $stok;
                $warnaList[] = $warna;
            }
            
            // Update total stok dan string warna
            $tas->update([
                'stok' => $totalStok,
                'warna_tas' => implode(', ', $warnaList)
            ]);
        }
        
        return redirect()->route('tas.show', $tas->id)
            ->with('success', 'Data tas berhasil diperbarui.');
    }

    public function destroy(Tas $ta)
    {
        $ta->delete();

        return redirect()->route('tas.index')
            ->with('success', 'Data tas berhasil dihapus!');
    }

    public function quickView($id)
    {
        $tas = Tas::with(['stokMasuk' => function($query) {
            $query->orderBy('tanggal_masuk', 'desc');
        }])->findOrFail($id);
        
        // Hitung statistik
        $totalStok = $tas->stok;
        $nilaiStok = $tas->stok * $tas->harga;
        
        // Warna badge styling
        $warnaColors = [
            'Black' => ['bg' => '#000000', 'text' => '#ffffff'],
            'Brown' => ['bg' => '#8B4513', 'text' => '#ffffff'],
            'Coffee' => ['bg' => '#D2691E', 'text' => '#ffffff'],
            'Cream' => ['bg' => '#FFFDD0', 'text' => '#000000'],
            'Maroon' => ['bg' => '#800000', 'text' => '#ffffff'],
            'Caramel' => ['bg' => '#AF6E4D', 'text' => '#ffffff'],
            'Khaki' => ['bg' => '#f0e68c', 'text' => '#000000'],
            'Merah' => ['bg' => '#e74c3c', 'text' => '#ffffff'],
            'Biru' => ['bg' => '#3498db', 'text' => '#ffffff'],
            'Hijau' => ['bg' => '#27ae60', 'text' => '#ffffff'],
            'Kuning' => ['bg' => '#f39c12', 'text' => '#000000'],
            'Ungu' => ['bg' => '#8e44ad', 'text' => '#ffffff'],
            'Pink' => ['bg' => '#e91e63', 'text' => '#ffffff'],
            'Navy' => ['bg' => '#1a3a52', 'text' => '#ffffff'],
            'Abu-abu' => ['bg' => '#95a5a6', 'text' => '#000000'],
            'Coklat' => ['bg' => '#A0522D', 'text' => '#ffffff'],
            'Hitam' => ['bg' => '#333333', 'text' => '#ffffff']
        ];
        
        // Generate HTML untuk modal
        $html = view('tas.partials.quickview', compact('tas', 'warnaColors', 'totalStok', 'nilaiStok'))->render();
        
        return response()->json([
            'success' => true,
            'html' => $html,
            'tas' => [
                'kode_tas' => $tas->kode_tas,
                'nama_tas' => $tas->nama_tas
            ]
        ]);
    }
}
