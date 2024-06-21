<?php

namespace App\Charts;

use App\Models\DetailPinjaman;
use App\Models\DetailSimpanan;
use App\Models\HistoryTransaksi;
use App\Models\Pinjaman;
use ArielMejiaDev\LarapexCharts\PieChart;
use Carbon\Carbon;

class TransaksiChart
{
    protected $chart;

    public function __construct(PieChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($tahun = null): PieChart
    {
        $tahun = $tahun ?: date('Y');

        $totalPemasukan = 0.00;
        $totalPengeluaran = 0.00;

        $rekap = HistoryTransaksi::with('users', 'anggota', 'detail_simpanan', 'pinjaman', 'detail_pinjaman')
            ->whereYear('created_at', $tahun)
            ->get();

        foreach ($rekap as $item) {
            if ($item->id_detail_simpanan != null) {
                $detail_simpanan = DetailSimpanan::find($item->id_detail_simpanan);
                $simpanan_pokok = $detail_simpanan->simpanan_pokok;
                $simpanan_wajib = $detail_simpanan->simpanan_wajib;
                $simpanan_sukarela = $detail_simpanan->simpanan_sukarela;
                if ($item->tipe_transaksi == 'Pemasukan') {
                    $totalPemasukan += $simpanan_pokok + $simpanan_wajib + $simpanan_sukarela;
                } else {
                    $totalPengeluaran += $simpanan_pokok + $simpanan_wajib + $simpanan_sukarela;
                }
            } elseif ($item->id_pinjaman != null) {
                $pinjaman = Pinjaman::find($item->id_pinjaman);
                $totalPengeluaran += $pinjaman->total_pinjaman;
            } else {
                $detail_pinjaman = DetailPinjaman::find($item->id_detail_pinjaman);
                $totalPemasukan += $detail_pinjaman->angsuran_pokok;
            }
        }

        $this->chart->setTitle('Pemasukan & Pengeluaran')
            ->setSubtitle('Total Pendapatan Koperasi Sekarang Rp ' . number_format($totalPemasukan - $totalPengeluaran, 2, ',', '.'))
            ->addData([$totalPemasukan, $totalPengeluaran])
            ->setLabels(['Pemasukan', 'Pengeluaran'])
            ->setColors(['#36A2EB', '#FF6384']);

        return $this->chart;
    }
}
