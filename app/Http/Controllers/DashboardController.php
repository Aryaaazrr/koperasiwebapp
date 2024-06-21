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
use App\Models\Laporan;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SHUChart $lineChart, TransaksiChart $pieChart, AnggotaChart $lineChartAnggota, JenisAnggotaChart $pieChartJenisAnggota, Request $request)
    {
        $jumlahAnggota = Anggota::count();
        $jumlahPegawai = User::where('id_role', '!=', '1')->count();
        $jumlahSimpanan = Simpanan::count();
        $jumlahPinjaman = Pinjaman::count();

        $jumlahAnggotaBulanLalu = Anggota::whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();

        $pertumbuhanAnggota = 0;
        if ($jumlahAnggotaBulanLalu > 0) {
            $pertumbuhanAnggota = (($jumlahAnggota - $jumlahAnggotaBulanLalu) / $jumlahAnggota) * 100;
        } else {
            $pertumbuhanAnggota = 100;
        }

        $selectedYear = $request->get('tahun');

        if (Auth::user()->id_role == 1) {
            $anggotaTahun = Anggota::selectRaw('YEAR(created_at) as year')
                ->distinct()
                ->pluck('year')
                ->sortDesc()
                ->toArray();

            if ($selectedYear) {
                $anggotaData = Anggota::whereYear('created_at', $selectedYear)->get();
                $jenisData = Anggota::whereYear('created_at', $selectedYear)->get();
            } else {
                $anggotaData = Anggota::all();
                $jenisaData = Anggota::all();
            }

            $anggotaChart = $lineChartAnggota->build($selectedYear);
            $jenisAnggotaChart = $pieChartJenisAnggota->build($selectedYear);

            return view('pages.dashboard.index', compact('jumlahAnggota', 'jumlahPegawai', 'jumlahSimpanan', 'jumlahPinjaman', 'anggotaChart', 'jenisAnggotaChart', 'pertumbuhanAnggota', 'anggotaTahun', 'selectedYear'));
        } else {
            $setor = DetailSimpanan::where('jenis_transaksi', '=', 'Setor')
                ->get();
            $tarik = DetailSimpanan::where('jenis_transaksi', '=', 'Tarik')
                ->get();

            $shuTahun = Laporan::selectRaw('YEAR(created_at) as year')
                ->distinct()
                ->pluck('year')
                ->sortDesc()
                ->toArray();
            $transaksiTahun = HistoryTransaksi::selectRaw('YEAR(created_at) as year')
                ->distinct()
                ->pluck('year')
                ->sortDesc()
                ->toArray();

            $totalSimpananPokok = $setor->sum('simpanan_pokok');
            $totalSimpananWajib = $setor->sum('simpanan_wajib');
            $totalSimpananSukarela = $setor->sum('simpanan_sukarela');

            $totalPenarikanPokok = $tarik->sum('simpanan_pokok');
            $totalPenarikanWajib = $tarik->sum('simpanan_wajib');
            $totalPenarikanSukarela = $tarik->sum('simpanan_sukarela');

            $totalSimpananPokok -= $totalPenarikanPokok;
            $totalSimpananWajib -= $totalPenarikanWajib;
            $totalSimpananSukarela -= $totalPenarikanSukarela;

            $rekap = HistoryTransaksi::with('users', 'anggota', 'detail_simpanan', 'pinjaman', 'detail_pinjaman')->orderBy('created_at', 'desc')->get();

            $shuChart = $lineChart->build($selectedYear);
            $transaksiChart = $pieChart->build($selectedYear);

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

            $pendapatan = $totalPemasukan - $totalPengeluaran;

            $cek_detail_pinjaman = DetailPinjaman::where('status_pelunasan', 'Belum Lunas')->with(['pinjaman', 'users'])->get();
            foreach ($cek_detail_pinjaman as $row) {
                if ($row->status_pelunasan == 'Belum Lunas') {
                    if ($row->tanggal_jatuh_tempo < Carbon::now()) {
                        $row->status_pelunasan = 'Lewat Jatuh Tempo';
                        $row->save();
                    }
                }

                if ($row->status_pelunasan == 'Lewat Jatuh Tempo') {
                    if ($row->tanggal_jatuh_tempo > Carbon::now()) {
                        $row->status_pelunasan = 'Belum Lunas';
                        $row->save();
                    }
                }
            }

            $detail_pinjaman = DetailPinjaman::where('status_pelunasan', 'Lewat Jatuh Tempo')->with(['pinjaman', 'users'])->get();
            $rowData = [];

            if ($request->ajax()) {
                foreach ($detail_pinjaman as $row) {
                    if ($row->status_pelunasan == 'Belum Lunas') {
                        if ($row->tanggal_jatuh_tempo < Carbon::now()) {
                            $row->status_pelunasan = 'Lewat Jatuh Tempo';
                            $row->save();
                        }
                    }

                    if ($row->status_pelunasan == 'Lewat Jatuh Tempo') {
                        if ($row->tanggal_jatuh_tempo > Carbon::now()) {
                            $row->status_pelunasan = 'Belum Lunas';
                            $row->save();
                        }
                    }

                    $pinjaman = $row->pinjaman;
                    $pinjaman = Pinjaman::where('id_pinjaman', $pinjaman->id_pinjaman)->with('anggota')->first();

                    $rowData[] = [
                        'DT_RowIndex' => $row->id_pinjaman,
                        'id_pinjaman' => $pinjaman->id_pinjaman,
                        'nama_anggota' => $pinjaman->anggota->nama,
                        'tanggal_jatuh_tempo' => $row->tanggal_jatuh_tempo,
                        'angsuran_ke_' => $row->angsuran_ke_,
                        'angsuran_pokok' => $row->angsuran_pokok,
                        'bunga' => $row->bunga,
                        'subtotal_angsuran' => $row->subtotal_angsuran,
                        'status_pelunasan' => $row->status_pelunasan
                    ];
                }

                return DataTables::of($rowData)->toJson();
            }

            return view('pages.dashboard.index', compact('jumlahAnggota', 'jumlahPegawai', 'jumlahSimpanan', 'jumlahPinjaman', 'totalSimpananPokok', 'totalSimpananWajib', 'totalSimpananSukarela', 'pendapatan', 'shuChart', 'transaksiChart', 'pertumbuhanAnggota', 'shuTahun', 'transaksiTahun', 'selectedYear'));
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