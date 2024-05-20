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

        $data = Anggota::select('jenis_kelamin', DB::raw('count(*) as total'))
            ->whereYear('tanggal_masuk', $tahun)
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin')->toArray();

        $anggotaPendiri = Anggota::where('jenis_anggota', 'Pendiri')
            ->whereYear('tanggal_masuk', $tahun)
            ->count();
        $anggotaBiasa = Anggota::where('jenis_anggota', 'Biasa')
            ->whereYear('tanggal_masuk', $tahun)
            ->count();

        $this->chart->setTitle('Distribusi Jenis Anggota')
            ->setSubtitle('Total Anggota Pendiri = ' . $anggotaPendiri . ', ' . 'Total Anggota Biasa = ' . $anggotaBiasa)
            ->addData(array_values($data))
            ->setLabels(array_keys($data));

        return $this->chart;
    }
}
