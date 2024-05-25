<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekapTransaksiController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\UserController;
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

Route::middleware('only_sign_in')->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'authenticate']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('logout', [AuthController::class, 'destroy'])->name('logout');

    Route::group(['middleware' => 'admin'], function () {
        Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('admin/dashboard/line-chart-anggota', [DashboardController::class, 'index'])->name('admin.line.chart');
        Route::get('admin/dashboard/pie-chart-anggota', [DashboardController::class, 'index'])->name('admin.pie.chart');

        Route::get('admin/pegawai', [PegawaiController::class, 'index'])->name('admin.pegawai');
        Route::get('admin/pegawai/add', [PegawaiController::class, 'create'])->name('admin.pegawai.create');
        Route::post('admin/pegawai/add', [PegawaiController::class, 'store'])->name('admin.pegawai.store');
        Route::get('admin/pegawai/edit/{id}', [PegawaiController::class, 'edit'])->name('admin.pegawai.edit');
        Route::put('admin/pegawai/edit/{id}', [PegawaiController::class, 'update'])->name('admin.pegawai.update');
        Route::get('admin/pegawai/{id}', [PegawaiController::class, 'destroy'])->name('admin.pegawai.destroy');
        Route::get('admin/pegawai/export/pdf', [PegawaiController::class, 'export'])->name('admin.pegawai.export');

        Route::get('admin/anggota', [AnggotaController::class, 'index'])->name('admin.anggota');
        Route::get('admin/anggota/add', [AnggotaController::class, 'create'])->name('admin.anggota.create');
        Route::post('admin/anggota/add', [AnggotaController::class, 'store'])->name('admin.anggota.store');
        Route::get('admin/anggota/edit/{id}', [AnggotaController::class, 'edit'])->name('admin.anggota.edit');
        Route::put('admin/anggota/edit/{id}', [AnggotaController::class, 'update'])->name('admin.anggota.update');
        Route::get('admin/anggota/{id}', [AnggotaController::class, 'destroy'])->name('admin.anggota.destroy');
        Route::get('admin/anggota/export/pdf', [AnggotaController::class, 'export'])->name('admin.anggota.export');

        Route::get('admin/profile', [ProfileController::class, 'index'])->name('admin.profile');
        Route::put('admin/profile/{id}', [ProfileController::class, 'update'])->name('admin.profile.update');
    });

    Route::group(['middleware' => 'kepala'], function () {
        Route::get('kepala/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('kepala/dashboard/line-chart-anggota', [DashboardController::class, 'index'])->name('kepala.line.chart');
        Route::get('kepala/dashboard/pie-chart-anggota', [DashboardController::class, 'index'])->name('kepala.pie.chart');

        Route::get('kepala/pegawai', [PegawaiController::class, 'index'])->name('pegawai');
        Route::get('kepala/pegawai/add', [PegawaiController::class, 'create'])->name('pegawai.create');
        Route::post('kepala/pegawai/add', [PegawaiController::class, 'store'])->name('pegawai.store');
        Route::get('kepala/pegawai/edit/{id}', [PegawaiController::class, 'edit'])->name('pegawai.edit');
        Route::put('kepala/pegawai/edit/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
        Route::get('kepala/pegawai/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
        Route::get('kepala/pegawai/export/pdf', [PegawaiController::class, 'export'])->name('pegawai.export');

        Route::get('kepala/anggota', [AnggotaController::class, 'index'])->name('anggota');
        Route::get('kepala/anggota/add', [AnggotaController::class, 'create'])->name('anggota.create');
        Route::post('kepala/anggota/add', [AnggotaController::class, 'store'])->name('anggota.store');
        Route::get('kepala/anggota/edit/{id}', [AnggotaController::class, 'edit'])->name('anggota.edit');
        Route::put('kepala/anggota/edit/{id}', [AnggotaController::class, 'update'])->name('anggota.update');
        Route::get('kepala/anggota/{id}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');
        Route::get('kepala/anggota/export/pdf', [AnggotaController::class, 'export'])->name('anggota.export');

        Route::get('kepala/simpanan', [SimpananController::class, 'index'])->name('simpanan');
        Route::get('kepala/simpanan/add', [SimpananController::class, 'create'])->name('simpanan.create');
        Route::post('kepala/simpanan/add', [SimpananController::class, 'store'])->name('simpanan.store');
        Route::get('kepala/simpanan/view/{id}', [SimpananController::class, 'show'])->name('simpanan.show');
        Route::get('kepala/simpanan/edit/{id}', [SimpananController::class, 'edit'])->name('simpanan.edit');
        Route::put('kepala/simpanan/edit/{id}', [SimpananController::class, 'update'])->name('simpanan.update');
        Route::get('kepala/simpanan/{id}', [SimpananController::class, 'destroy'])->name('simpanan.destroy');
        Route::get('kepala/simpanan/view/delete/{id}', [SimpananController::class, 'destroyDetail'])->name('simpanan.destroy.detail');
        Route::get('kepala/simpanan/export/pdf/{id}', [SimpananController::class, 'export'])->name('simpanan.export');

        Route::get('kepala/pinjaman', [PinjamanController::class, 'index'])->name('pinjaman');
        Route::get('kepala/pinjaman/add', [PinjamanController::class, 'create'])->name('pinjaman.create');
        Route::post('kepala/pinjaman/add', [PinjamanController::class, 'store'])->name('pinjaman.store');
        Route::get('kepala/pinjaman/kredit', [PinjamanController::class, 'edit'])->name('pinjaman.edit');
        Route::get('kepala/pinjaman/view/{id}', [PinjamanController::class, 'show'])->name('pinjaman.show');
        Route::put('kepala/pinjaman/view/{id}', [PinjamanController::class, 'update'])->name('pinjaman.update');
        Route::get('kepala/pinjaman/{id}', [PinjamanController::class, 'destroy'])->name('pinjaman.destroy');
        Route::get('kepala/pinjaman/export/pdf/{id}', [PinjamanController::class, 'export'])->name('pinjaman.export');

        Route::get('kepala/laporan', [LaporanController::class, 'index'])->name('laporan');
        Route::post('kepala/laporan/add', [LaporanController::class, 'store'])->name('laporan.store');
        Route::put('kepala/laporan/update', [LaporanController::class, 'update'])->name('laporan.update');
        Route::get('kepala/laporan/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
        Route::get('kepala/laporan/export/pdf', [LaporanController::class, 'export'])->name('laporan.export');

        Route::get('kepala/rekap', [RekapTransaksiController::class, 'index'])->name('rekap');
        Route::get('kepala/rekap/filter', [RekapTransaksiController::class, 'index'])->name('rekap.filter');
        Route::get('kepala/rekap/export/pdf', [RekapTransaksiController::class, 'export'])->name('rekap.export');

        Route::get('kepala/profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('kepala/profile/{id}', [ProfileController::class, 'update'])->name('profile.update');
    });

    Route::group(['middleware' => 'pegawai'], function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('pegawai.dashboard');
        Route::get('dashboard/line-chart-anggota', [DashboardController::class, 'index'])->name('pegawai.line.chart');
        Route::get('dashboard/pie-chart-anggota', [DashboardController::class, 'index'])->name('pegawai.pie.chart');

        Route::get('pegawai', [PegawaiController::class, 'index'])->name('pegawai.pegawai');
        Route::get('pegawai/add', [PegawaiController::class, 'create'])->name('pegawai.pegawai.create');
        Route::post('pegawai/add', [PegawaiController::class, 'store'])->name('pegawai.pegawai.store');
        Route::get('pegawai/edit/{id}', [PegawaiController::class, 'edit'])->name('pegawai.pegawai.edit');
        Route::put('pegawai/edit/{id}', [PegawaiController::class, 'update'])->name('pegawai.pegawai.update');
        Route::get('pegawai/export/pdf', [PegawaiController::class, 'export'])->name('pegawai.pegawai.export');

        Route::get('anggota', [AnggotaController::class, 'index'])->name('pegawai.anggota');
        Route::get('anggota/add', [AnggotaController::class, 'create'])->name('pegawai.anggota.create');
        Route::post('anggota/add', [AnggotaController::class, 'store'])->name('pegawai.anggota.store');
        Route::get('anggota/edit/{id}', [AnggotaController::class, 'edit'])->name('pegawai.anggota.edit');
        Route::put('anggota/edit/{id}', [AnggotaController::class, 'update'])->name('pegawai.anggota.update');
        Route::get('anggota/export/pdf', [AnggotaController::class, 'export'])->name('pegawai.anggota.export');

        Route::get('simpanan', [SimpananController::class, 'index'])->name('pegawai.simpanan');
        Route::get('simpanan/add', [SimpananController::class, 'create'])->name('pegawai.simpanan.create');
        Route::post('simpanan/add', [SimpananController::class, 'store'])->name('pegawai.simpanan.store');
        Route::get('simpanan/view/{id}', [SimpananController::class, 'show'])->name('pegawai.simpanan.show');
        Route::get('simpanan/edit/{id}', [SimpananController::class, 'edit'])->name('pegawai.simpanan.edit');
        Route::put('simpanan/edit/{id}', [SimpananController::class, 'update'])->name('pegawai.simpanan.update');
        Route::get('simpanan/export/pdf/{id}', [SimpananController::class, 'export'])->name('pegawai.simpanan.export');

        Route::get('pinjaman', [PinjamanController::class, 'index'])->name('pegawai.pinjaman');
        Route::get('pinjaman/add', [PinjamanController::class, 'create'])->name('pegawai.pinjaman.create');
        Route::post('pinjaman/add', [PinjamanController::class, 'store'])->name('pegawai.pinjaman.store');
        Route::get('pinjaman/kredit', [PinjamanController::class, 'edit'])->name('pegawai.pinjaman.edit');
        Route::get('pinjaman/view/{id}', [PinjamanController::class, 'show'])->name('pegawai.pinjaman.show');
        Route::put('pinjaman/edit/{id}', [PinjamanController::class, 'update'])->name('pegawai.pinjaman.update');
        Route::get('pinjaman/export/pdf/{id}', [PinjamanController::class, 'export'])->name('pegawai.pinjaman.export');

        Route::get('laporan', [LaporanController::class, 'index'])->name('pegawai.laporan');
        Route::post('laporan/add', [LaporanController::class, 'store'])->name('pegawai.laporan.store');
        Route::put('laporan/update', [LaporanController::class, 'update'])->name('pegawai.laporan.update');
        Route::get('laporan/export/pdf', [LaporanController::class, 'export'])->name('pegawai.laporan.export');

        Route::get('rekap', [RekapTransaksiController::class, 'index'])->name('pegawai.rekap');
        Route::get('rekap/filter', [RekapTransaksiController::class, 'index'])->name('pegawai.rekap.filter');
        Route::get('rekap/export/pdf', [RekapTransaksiController::class, 'export'])->name('pegawai.rekap.export');

        Route::get('profile', [ProfileController::class, 'index'])->name('pegawai.profile');
        Route::put('profile/{id}', [ProfileController::class, 'update'])->name('pegawai.profile.update');
    });
});