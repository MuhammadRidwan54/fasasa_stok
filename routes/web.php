<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [AuthenticatedSessionController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'login']);
Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');

// Protected routes - hanya untuk admin yang sudah login
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Master Tas
    Route::resource('tas', TasController::class);
    
    // Quick view untuk tas
    Route::get('/tas/{id}/quickview', [TasController::class, 'quickView'])->name('tas.quickview');

    Route::get('/tas/{id}/stok-warna', [TasController::class, 'getStokPerWarna'])->name('tas.stok-warna');
    
    // Laporan Routes
    Route::prefix('laporan')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/create', [LaporanController::class, 'create'])->name('laporan.create');
        Route::post('/', [LaporanController::class, 'store'])->name('laporan.store');
        Route::get('/{laporan}', [LaporanController::class, 'show'])->name('laporan.show');
        Route::get('/{laporan}/edit', [LaporanController::class, 'edit'])->name('laporan.edit');
        Route::put('/{laporan}', [LaporanController::class, 'update'])->name('laporan.update');
        Route::delete('/{laporan}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
        Route::patch('/laporan/{laporan}/return-stok', [LaporanController::class, 'returnStok'])->name('laporan.return-stok');
        
        
        // Export routes
        Route::post('/export/pdf', [LaporanController::class, 'exportPDF'])->name('laporan.export.pdf');
        Route::post('/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
        Route::post('/export/excel-advanced', [LaporanController::class, 'exportExcelAdvanced'])->name('laporan.export.excel.advanced');
        Route::post('/export/excel-multi', [LaporanController::class, 'exportExcelMultiSheet'])->name('laporan.export.excel.multi');
        Route::get('/laporan/export-excel-multi-sheet', [LaporanController::class, 'exportExcelMultiSheet'])->name('laporan.export-excel-multi-sheet');
        Route::post('/export/csv', [LaporanController::class, 'exportCSV'])->name('laporan.export.csv');
        
        // GET routes untuk compatibility
        Route::get('/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel.get');
        Route::get('/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf.get');
    });
});

// Fallback route
Route::fallback(function () {
    return redirect()->route('login');
});