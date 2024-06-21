<?php

namespace App\Charts;

use App\Models\Anggota;
use ArielMejiaDev\LarapexCharts\LineChart;
use Carbon\Carbon;

class AnggotaChart
{
    protected $chart;

    public function __construct(LineChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($tahun = null): LineChart
    {
        $tahun = $tahun ?: date('Y');
        $bulan = 12;

        $dataAnggota = [];
        $dataBulan = [];

        for ($i = 1; $i <= $bulan; $i++) {
            $jumlahAnggota = Anggota::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $i)
                ->count();

            $dataBulan[] = Carbon::create()->month($i)->format('F');
            $dataAnggota[] = $jumlahAnggota;
        }

        $totalAnggotaBulanIni = array_sum($dataAnggota);
        $subtitle = 'Total Anggota Baru Tahun ' . $tahun . ': ' . $totalAnggotaBulanIni;

        $this->chart->setTitle('Pertumbuhan Anggota')
            ->setSubtitle($subtitle)
            ->addData('Jumlah Anggota', $dataAnggota)
            ->setXAxis($dataBulan);

        return $this->chart;
    }
}