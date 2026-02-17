<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CafeDetailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisTiketController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\StokBarangController;
use App\Http\Controllers\TiketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\CafeController;
use App\Http\Controllers\BarangMasukController;
use App\Models\Karyawan;
use App\Http\Controllers\KaryawanController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {

    // ------------------- ADMIN -------------------
    Route::middleware('checkRole:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/admin/dashboard/data', [DashboardController::class, 'getChartData'])->name('dashboard.data');

        Route::get('admin/kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::get('admin/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
        Route::post('admin/kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::get('admin/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
        Route::put('admin/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('admin/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        Route::get('admin/jenis_tiket', [JenisTiketController::class, 'index'])->name('jenis_tiket.index');
        Route::get('admin/jenis_tiket/create', [JenisTiketController::class, 'create'])->name('jenis_tiket.create');
        Route::post('admin/jenis_tiket', [JenisTiketController::class, 'store'])->name('jenis_tiket.store');
        Route::get('admin/jenis_tiket/{id}/edit', [JenisTiketController::class, 'edit'])->name('jenis_tiket.edit');
        Route::put('admin/jenis_tiket/{id}', [JenisTiketController::class, 'update'])->name('jenis_tiket.update');
        Route::delete('admin/jenis_tiket/{id}', [JenisTiketController::class, 'destroy'])->name('jenis_tiket.destroy');

        Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
        Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
        Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
        Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
        Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
        Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

        Route::get('admin/satuan', [SatuanController::class, 'index'])->name('satuan.index');
        Route::get('admin/satuan/create', [SatuanController::class, 'create'])->name('satuan.create');
        Route::post('admin/satuan', [SatuanController::class, 'store'])->name('satuan.store');
        Route::get('admin/satuan/{id}/edit', [SatuanController::class, 'edit'])->name('satuan.edit');
        Route::put('admin/satuan/{id}', [SatuanController::class, 'update'])->name('satuan.update');
        Route::delete('admin/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy');

        Route::get('admin/stok_barang', [StokBarangController::class, 'index'])->name('stok_barang.index');
        Route::get('admin/stok_barang/create', [StokBarangController::class, 'create'])->name('stok_barang.create');
        Route::post('admin/stok_barang', [StokBarangController::class, 'store'])->name('stok_barang.store');
        Route::get('admin/stok_barang/{id}/edit', [StokBarangController::class, 'edit'])->name('stok_barang.edit');
        Route::put('admin/stok_barang/{id}', [StokBarangController::class, 'update'])->name('stok_barang.update');
        Route::delete('admin/stok_barang/{id}', [StokBarangController::class, 'destroy'])->name('stok_barang.destroy');

        Route::get('admin/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
        Route::get('admin/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('admin/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::get('admin/karyawan_detail/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan_detail.edit');
        Route::put('admin/karyawan_detail/{id}', [KaryawanController::class, 'update'])->name('karyawan_detail.update');
        Route::delete('admin/karyawan/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
        Route::get('admin/karyawan/data', [KaryawanController::class, 'getData'])->name('karyawan.data');


        Route::get('admin/menu', [MenuController::class, 'index'])->name('menu.index');
        Route::get('admin/menu/create', [MenuController::class, 'create'])->name('menu.create');
        Route::post('admin/menu', [MenuController::class, 'store'])->name('menu.store');
        Route::get('admin/menu/{id}/edit', [MenuController::class, 'edit'])->name('menu.edit');
        Route::put('admin/menu/{id}', [MenuController::class, 'update'])->name('menu.update');
        Route::delete('admin/menu/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');

        Route::get('admin/riwayat_cafe', [CafeController::class, 'index'])->name('riwayat_cafe.index');
        Route::delete('admin/riwayat_cafe/{id}', [CafeController::class, 'destroy'])->name('riwayat_cafe.destroy');
        Route::get('admin/riwayat_cafe/{id}', [CafeDetailController::class, 'index'])->name('riwayat_cafe.detail');
        Route::get('admin/riwayat_cafe/details/{id}/edit', [CafeDetailController::class, 'edit'])->name('riwayat_cafe.details.edit');
        Route::put('admin/riwayat_cafe/details/{id}', [CafeDetailController::class, 'update'])->name('riwayat_cafe.details.update');
        Route::delete('admin/riwayat_cafe/details/{id}', [CafeDetailController::class, 'destroy'])->name('riwayat_cafe.details.destroy');

        Route::get('admin/barang_masuk', [BarangMasukController::class, 'index'])->name('barang_masuk.index');
        Route::get('admin/barang_masuk/create', [BarangMasukController::class, 'create'])->name('barang_masuk.create');
        Route::post('admin/barang_masuk', [BarangMasukController::class, 'store'])->name('barang_masuk.store');
        Route::get('admin/barang_masuk/{id}/edit', [BarangMasukController::class, 'edit'])->name('barang_masuk.edit');
        Route::put('admin/barang_masuk/{id}', [BarangMasukController::class, 'update'])->name('barang_masuk.update');
        Route::delete('admin/barang_masuk/{id}', [BarangMasukController::class, 'destroy'])->name('barang_masuk.destroy');

        Route::get('admin/riwayat_tiket', [TiketController::class, 'riwayat'])->name('riwayat_tiket');
        Route::get('admin/riwayat_tiket/{id}', [TiketController::class, 'riwayatDetail'])->name('riwayat_tiket.detail');
        Route::delete('admin/riwayat_tiket/{id}', [TiketController::class, 'destroy'])->name('riwayat_tiket.destroy');
        Route::delete('admin/riwayat_tiket_detail/{id}', [TiketController::class, 'destroyDetail'])->name('riwayat_tiket_detail.destroy');
        Route::put('admin/riwayat_tiket_detail/{id}', [TiketController::class, 'updateDetail'])->name('riwayat_tiket_detail.update');
        Route::put('admin/tiket-detail/{id}', [TiketController::class, 'updateDetail'])->name('tiketdetail.update');

    });


    Route::middleware('checkRole:admin,kasir')->group(function () {

        Route::get('/kasir_cafe', [CafeController::class, 'kasir'])->name('kasir_cafe');
        Route::post('/kasir_cafe/store', [CafeController::class, 'store'])->name('kasir_cafe.store');
        Route::get('/{id}/struk_cafe', [CafeController::class, 'cetakStruk'])->name('cafe.struk_cafe');

        Route::get('/kasir_tiket', [TiketController::class, 'index'])->name('kasir_tiket');
        Route::post('/kasir_tiket/store', [TiketController::class, 'store'])->name('kasir_tiket.store');
        Route::get('/{id}/struk_tiket', [TiketController::class, 'struk'])->name('tiket.struk');

        // Route::resource('barang_masuk', controller: BarangMasukController::class)->except(['show']);
    });
});
