<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\DetailPinjaman;
use App\Models\DetailSimpanan;
use App\Models\HistoryTransaksi;
use App\Models\Pinjaman;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RekapTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HistoryTransaksi::with('users', 'anggota', 'detail_simpanan', 'pinjaman', 'detail_pinjaman')->orderBy('created_at', 'desc');

        $jumlahMasuk = 0.00;
        $jumlahKeluar = 0.00;
        $totalPemasukan = 0.00;
        $totalPengeluaran = 0.00;

        if ($request->has(['start_date', 'end_date'])) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } 

        $rekap = $query->get();

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

        if ($request->ajax()) {
            $rowData = [];
            $iteration = 1;

            foreach ($rekap as $item) {
                $jumlahMasuk = 0.00;
                $jumlahKeluar = 0.00;
                $totalPemasukan = 0.00;
                $totalPengeluaran = 0.00;

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

                $rowData[] = [
                    'DT_RowIndex' => $iteration,
                    'nama_pengguna' => $item->users->nama,
                    'anggota' => $item->anggota->nama,
                    'jenis_transaksi' => $jenis_transaksi,
                    'tanggal' => $item->created_at->format('d-m-Y h:i:s'),
                    'jumlah_masuk' => $jumlahMasuk,
                    'jumlah_keluar' => $jumlahKeluar,
                ];

                $iteration++;
            }

            return DataTables::of($rowData)->toJson();
        }

        return view('pages.rekap.index', ['pendapatan' => $pendapatan, 'totalPemasukan' => $totalPemasukan, 'totalPengeluaran' => $totalPengeluaran]);
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

    public function export(Request $request)
    {
        $data = HistoryTransaksi::all();
        if ($data) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $tahun = Carbon::now()->format('Y');

            $rekapData = HistoryTransaksi::with('users', 'detail_simpanan', 'pinjaman', 'detail_pinjaman')->whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at', 'desc')->get();

            $jumlah_masuk = [];
            $jumlah_keluar = [];
            $totalPemasukan = 0.00;
            $totalPengeluaran = 0.00;

            foreach ($rekapData as $item) {
                $jumlahMasuk = 0.00;
                $jumlahKeluar = 0.00;
                if ($item->id_detail_simpanan !== null) {
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
                } elseif ($item->id_pinjaman !== null) {
                    $pinjaman = Pinjaman::find($item->id_pinjaman);
                    if (!$pinjaman) {
                        $jumlahKeluar = 0.00;
                    } else {
                        $jumlahKeluar = $pinjaman->total_pinjaman;
                    }
                    $totalPengeluaran += $jumlahKeluar;
                    $jenis_transaksi = 'Pengajuan Pinjaman';
                } else {
                    $detail_pinjaman = DetailPinjaman::find($item->id_detail_pinjaman);
                    if (!$detail_pinjaman) {
                        $jumlahMasuk = 0.00;
                    } else {
                        $jumlahMasuk = $detail_pinjaman->angsuran_pokok;
                    }
                    $totalPemasukan += $jumlahMasuk;
                    $jenis_transaksi = 'Angsuran Pinjaman';
                }

                array_push($jumlah_masuk, $jumlahMasuk);
                array_push($jumlah_keluar, $jumlahKeluar);
            }

            $pendapatan = abs($totalPemasukan - $totalPengeluaran);

            $html = view('pages.report.rekap', ['rekapData' => $rekapData, 'pendapatan' => $pendapatan, 'totalPemasukan' => $totalPemasukan, 'totalPengeluaran' => $totalPengeluaran, 'tahun' => $tahun, 'jenis_transaksi' => $jenis_transaksi, 'jumlah_masuk' => $jumlah_masuk, 'jumlah_keluar' => $jumlah_keluar])->render();

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $dompdf->stream('Rekap Transaksi.pdf');
        } else {
            return back()->withErrors(['error' => 'Data Rekap Transaksi masih kosong. Silahkan coba kembali.']);
        }
    }
}