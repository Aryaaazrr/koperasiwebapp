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

    Route::group(['middleware' => 'pengurus'], function () {
        Route::get('pengurus/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('pengurus/dashboard/line-chart-anggota', [DashboardController::class, 'index'])->name('pengurus.line.chart');
        Route::get('pengurus/dashboard/pie-chart-anggota', [DashboardController::class, 'index'])->name('pengurus.pie.chart');

        Route::get('pengurus/pegawai', [PegawaiController::class, 'index'])->name('pegawai');
        Route::get('pengurus/pegawai/add', [PegawaiController::class, 'create'])->name('pegawai.create');
        Route::post('pengurus/pegawai/add', [PegawaiController::class, 'store'])->name('pegawai.store');
        Route::get('pengurus/pegawai/edit/{id}', [PegawaiController::class, 'edit'])->name('pegawai.edit');
        Route::put('pengurus/pegawai/edit/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
        Route::get('pengurus/pegawai/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
        Route::get('pengurus/pegawai/export/pdf', [PegawaiController::class, 'export'])->name('pegawai.export');

        Route::get('pengurus/anggota', [AnggotaController::class, 'index'])->name('anggota');
        Route::get('pengurus/anggota/add', [AnggotaController::class, 'create'])->name('anggota.create');
        Route::post('pengurus/anggota/add', [AnggotaController::class, 'store'])->name('anggota.store');
        Route::get('pengurus/anggota/edit/{id}', [AnggotaController::class, 'edit'])->name('anggota.edit');
        Route::put('pengurus/anggota/edit/{id}', [AnggotaController::class, 'update'])->name('anggota.update');
        Route::get('pengurus/anggota/{id}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');
        Route::get('pengurus/anggota/export/pdf', [AnggotaController::class, 'export'])->name('anggota.export');

        Route::get('pengurus/simpanan', [SimpananController::class, 'index'])->name('simpanan');
        Route::get('pengurus/simpanan/add', [SimpananController::class, 'create'])->name('simpanan.create');
        Route::post('pengurus/simpanan/add', [SimpananController::class, 'store'])->name('simpanan.store');
        Route::get('pengurus/simpanan/view/{id}', [SimpananController::class, 'show'])->name('simpanan.show');
        Route::get('pengurus/simpanan/edit/{id}', [SimpananController::class, 'edit'])->name('simpanan.edit');
        Route::put('pengurus/simpanan/edit/{id}', [SimpananController::class, 'update'])->name('simpanan.update');
        Route::get('pengurus/simpanan/{id}', [SimpananController::class, 'destroy'])->name('simpanan.destroy');
        Route::get('pengurus/simpanan/view/delete/{id}', [SimpananController::class, 'destroyDetail'])->name('simpanan.destroy.detail');
        Route::get('pengurus/simpanan/export/pdf/{id}', [SimpananController::class, 'export'])->name('simpanan.export');

        Route::get('pengurus/pinjaman', [PinjamanController::class, 'index'])->name('pinjaman');
        Route::get('pengurus/pinjaman/belum-lunas', [PinjamanController::class, 'belumLunas'])->name('pinjaman.belum.lunas');
        Route::get('pengurus/pinjaman/lunas', [PinjamanController::class, 'lunas'])->name('pinjaman.lunas');
        Route::get('pengurus/pinjaman/add', [PinjamanController::class, 'create'])->name('pinjaman.create');
        Route::post('pengurus/pinjaman/add', [PinjamanController::class, 'store'])->name('pinjaman.store');
        Route::get('pengurus/pinjaman/view/{id}', [PinjamanController::class, 'show'])->name('pinjaman.show');
        Route::get('pengurus/pinjaman/kredit', [PinjamanController::class, 'edit'])->name('pinjaman.edit');
        Route::get('pengurus/pinjaman/diragukan', [PinjamanController::class, 'dataDiragukan'])->name('pinjaman.diragukan');
        Route::get('pengurus/pinjaman/macet', [PinjamanController::class, 'dataMacet'])->name('pinjaman.macet');
        Route::put('pengurus/pinjaman/view/{id}', [PinjamanController::class, 'update'])->name('pinjaman.update');
        Route::get('pengurus/pinjaman/{id}', [PinjamanController::class, 'destroy'])->name('pinjaman.destroy');
        Route::get('pengurus/pinjaman/export/pdf/{id}', [PinjamanController::class, 'export'])->name('pinjaman.export');

        Route::get('pengurus/laporan', [LaporanController::class, 'index'])->name('laporan');
        Route::post('pengurus/laporan/add', [LaporanController::class, 'store'])->name('laporan.store');
        Route::put('pengurus/laporan/update', [LaporanController::class, 'update'])->name('laporan.update');
        Route::get('pengurus/laporan/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
        Route::get('pengurus/laporan/export/pdf', [LaporanController::class, 'export'])->name('laporan.export');

        Route::get('pengurus/rekap', [RekapTransaksiController::class, 'index'])->name('rekap');
        Route::get('pengurus/rekap/filter', [RekapTransaksiController::class, 'index'])->name('rekap.filter');
        Route::get('pengurus/rekap/export/pdf', [RekapTransaksiController::class, 'export'])->name('rekap.export');

        Route::get('pengurus/profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('pengurus/profile/{id}', [ProfileController::class, 'update'])->name('profile.update');
    });

    Route::group(['middleware' => 'pegawai'], function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('pegawai.dashboard');
        Route::get('dashboard/line-chart-anggota', [DashboardController::class, 'index'])->name('pegawai.line.chart');
        Route::get('dashboard/pie-chart-anggota', [DashboardController::class, 'index'])->name('pegawai.pie.chart');

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
        Route::get('pinjaman/view/{id}', [PinjamanController::class, 'show'])->name('pegawai.pinjaman.show');
        Route::get('pinjaman/edit/{id}', [PinjamanController::class, 'edit'])->name('pegawai.pinjaman.edit');
        Route::get('pinjaman/kredit', [PinjamanController::class, 'edit'])->name('pegawai.pinjaman.edit');
        Route::get('pinjaman/diragukan', [PinjamanController::class, 'dataDiragukan'])->name('pegawai.pinjaman.diragukan');
        Route::get('pinjaman/macet', [PinjamanController::class, 'dataMacet'])->name('pegawai.pinjaman.macet');
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