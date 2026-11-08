<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Kategori & Supplier: admin + petugas inventory
    Route::middleware('role:admin,petugas_inventory')->group(function () {
        Route::resource('kategori', KategoriController::class)->except('show');
        Route::resource('supplier', SupplierController::class);

        Route::resource('barang', BarangController::class);
        Route::resource('barang-masuk', BarangMasukController::class)->parameters(['barang-masuk' => 'barangMasuk']);
        Route::resource('barang-keluar', BarangKeluarController::class)->parameters(['barang-keluar' => 'barangKeluar']);
    });

    // Keuangan: admin + bendahara
    Route::middleware('role:admin,bendahara')->group(function () {
        Route::resource('keuangan', KeuanganController::class)->except('show');
    });

    // Laporan: semua role (inventory: admin + petugas; keuangan: admin + bendahara)
    Route::middleware('role:admin,petugas_inventory')->group(function () {
        Route::get('/laporan/inventory', [LaporanController::class, 'inventory'])->name('laporan.inventory');
    });
    Route::middleware('role:admin,bendahara')->group(function () {
        Route::get('/laporan/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
    });

    // Manajemen pengguna & audit log: hanya admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('pengguna', PenggunaController::class)->except('show');
        Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');
    });
});
