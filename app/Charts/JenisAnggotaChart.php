<?php

namespace App\Charts;

use App\Models\Anggota;
use ArielMejiaDev\LarapexCharts\PieChart;
use Illuminate\Support\Facades\DB;

class JenisAnggotaChart
{
    protected $chart;

    public function __construct(PieChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($tahun = null): PieChart
    {
        $tahun = $tahun ?: date('Y');

        $data = Anggota::select('jenis_anggota', DB::raw('count(*) as total'))
            ->whereYear('created_at', $tahun)
            ->groupBy('jenis_anggota')
            ->pluck('total', 'jenis_anggota')->toArray();

        $anggotaPendiri = Anggota::where('jenis_anggota', 'Pendiri')
            ->whereYear('created_at', $tahun)
            ->count();
        $anggotaBiasa = Anggota::where('jenis_anggota', 'Biasa')
            ->whereYear('created_at', $tahun)
            ->count();

        $this->chart->setTitle('Distribusi Jenis Anggota')
            ->setSubtitle('Total Anggota Pendiri = ' . $anggotaPendiri . ', ' . 'Total Anggota Biasa = ' . $anggotaBiasa)
            ->addData([$anggotaPendiri, $anggotaBiasa])
            ->setLabels(['Anggota Pendiri', 'Anggota Biasa'])
            ->setColors(['#36A2EB', '#FF6384']);

        return $this->chart;
    }
}