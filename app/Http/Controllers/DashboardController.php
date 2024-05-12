<?php

namespace App\Http\Controllers;

use App\Charts\AnggotaChart;
use App\Charts\JenisAnggotaChart;
use App\Charts\SHUChart;
use App\Charts\TransaksiChart;
use App\Models\Anggota;
use App\Models\DetailPinjaman;
use App\Models\DetailSimpanan;
use App\Models\HistoryTransaksi;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SHUChart $lineChart, TransaksiChart $pieChart, AnggotaChart $lineChartAnggota, JenisAnggotaChart $pieChartJenisAnggota)
    {
        if (Auth::user()->id_role == 1) {
            $jumlahAnggota = Anggota::count();
            $jumlahPegawai = User::where('id_role', '=', '3')->count();
            $jumlahSimpanan = Simpanan::count();
            $jumlahPinjaman = Pinjaman::count();
            $anggotaChart = $lineChartAnggota->build();
            $jenisAnggotaChart = $pieChartJenisAnggota->build();

            return view('pages.dashboard.index', compact('jumlahAnggota', 'jumlahPegawai', 'jumlahSimpanan', 'jumlahPinjaman', 'anggotaChart', 'jenisAnggotaChart'));
        } elseif (Auth::user()->id_role == 2) {
            $jumlahAnggota = Anggota::count();
            $jumlahPegawai = User::where('id_role', '=', '3')->count();
            $jumlahSimpanan = Simpanan::count();
            $jumlahPinjaman = Pinjaman::count();
            $saldoSimpananPokok = DetailSimpanan::where('jenis_transaksi', 'Setor')->sum('simpanan_pokok');
            $saldoSimpananWajib = DetailSimpanan::where('jenis_transaksi', 'Setor')->sum('simpanan_wajib');
            $saldoSimpananSukarela = DetailSimpanan::where('jenis_transaksi', 'Setor')->sum('simpanan_sukarela');

            $rekap = HistoryTransaksi::with('users', 'anggota', 'detail_simpanan', 'pinjaman', 'detail_pinjaman')->orderBy('created_at', 'desc')->get();

            $shuChart = $lineChart->build();
            $transaksiChart = $pieChart->build();

            $jumlahMasuk = 0.00;
            $jumlahKeluar = 0.00;
            $totalPemasukan = 0.00;
            $totalPengeluaran = 0.00;

            foreach ($rekap as $item) {
                if ($item->id_detail_simpanan != null) {
                    $detail_simpanan = DetailSimpanan::find($item->id_detail_simpanan);
                    $simpanan_pokok = $detail_simpanan->simpanan_pokok;
                    $simpanan_wajib = $detail_simpanan->simpanan_wajib;
                    $simpanan_sukarela = $detail_simpanan->simpanan_sukarela;
                    if ($item->tipe_transaksi == 'Pemasukan') {
                        $jenis_transaksi = 'Setor Simpanan';
                        $jumlahMasuk = $simpanan_pokok + $simpanan_wajib + $simpanan_sukarela;
                        $totalPemasukan += $jumlahMasuk;
                    } else {
                        $jenis_transaksi = 'Tarik Simpanan';
                        $jumlahKeluar = $simpanan_pokok + $simpanan_wajib + $simpanan_sukarela;
                        $totalPengeluaran += $jumlahKeluar;
                    }
                } elseif ($item->id_pinjaman != null) {
                    $pinjaman = Pinjaman::find($item->id_pinjaman);
                    $jumlahKeluar = $pinjaman->total_pinjaman;
                    $totalPengeluaran += $jumlahKeluar;
                    $jenis_transaksi = 'Pengajuan Pinjaman';
                } else {
                    $detail_pinjaman = DetailPinjaman::find($item->id_detail_pinjaman);
                    $jumlahMasuk = $detail_pinjaman->angsuran_pokok;
                    $totalPemasukan += $jumlahMasuk;
                    $jenis_transaksi = 'Angsuran Pinjaman';
                }
            }

            $pendapatan = abs($totalPemasukan - $totalPengeluaran);

            return view('pages.dashboard.index', compact('jumlahAnggota', 'jumlahPegawai', 'jumlahSimpanan', 'jumlahPinjaman', 'saldoSimpananPokok', 'saldoSimpananWajib', 'saldoSimpananSukarela', 'pendapatan', 'shuChart', 'transaksiChart'));
        } else {
            $jumlahAnggota = Anggota::count();
            $jumlahPegawai = User::where('id_role', '=', '3')->count();
            $jumlahSimpanan = Simpanan::count();
            $jumlahPinjaman = Pinjaman::count();

            return view('pages.dashboard.index', compact('jumlahAnggota', 'jumlahPegawai', 'jumlahSimpanan', 'jumlahPinjaman'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
