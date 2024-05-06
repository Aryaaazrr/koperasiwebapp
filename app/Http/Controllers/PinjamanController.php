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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PinjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $simpanan = Pinjaman::with('anggota')->get();

        if ($request->ajax()) {
            return DataTables::of($simpanan)
                ->addColumn('DT_RowIndex', function ($simpanan) {
                    return $simpanan->id_pinjaman;
                })
                ->addColumn('nama', function ($simpanan) {
                    return $simpanan->anggota->nama;
                })
                ->toJson();
        }

        return view('pages.pinjaman.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.pinjaman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_anggota' => 'required|exists:anggota,id_anggota',
            'angsuran' => 'required|max:12|min:1',
            'nominal_pinjaman' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/|max:5000000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pinjaman_belum_lunas = Pinjaman::where('id_anggota', $request->id_anggota)
            ->where('status_pinjaman', 'Belum Lunas')
            ->first();
        if ($pinjaman_belum_lunas) {
            return redirect()->back()->with(['error' => 'Anggota memiliki pinjaman yang belum lunas.']);
        }

        try {
            DB::transaction(function () use ($request) {
                $angsuran_pokok = ceil($request->nominal_pinjaman / $request->angsuran);
                $bunga = ceil($request->nominal_pinjaman * 0.01);
                $subtotal_angsuran = ceil($angsuran_pokok + $bunga);
                $sisa_lancar_angsuran = ceil($subtotal_angsuran * $request->angsuran);
                $pendapatan = $this->hitungKas();

                if ($request->nominal_pinjaman > $pendapatan) {
                    throw new \Exception('Saldo koperasi tidak cukup untuk melanjutkan pengajuan pinjaman.');
                }

                $pinjaman = new Pinjaman();
                $pinjaman->id_anggota = $request->id_anggota;
                $pinjaman->no_pinjaman = $this->generateMemberNumber();
                $pinjaman->total_pinjaman = $request->nominal_pinjaman;
                $pinjaman->angsuran = $request->angsuran;
                $pinjaman->sisa_lancar_keseluruhan = round($sisa_lancar_angsuran);
                $pinjaman->status_pinjaman = 'Belum Lunas';
                $pinjaman->tanggal_realisasi = Carbon::now();

                if ($pinjaman->save()) {
                    $id_pinjaman = $pinjaman->id_pinjaman;

                    $history = new HistoryTransaksi();
                    $history->id_users = Auth::user()->id_users;
                    $history->id_pinjaman = $id_pinjaman;
                    $history->tipe_transaksi = 'Pengeluaran';

                    if (!$history->save()) {
                        throw new \Exception('Gagal menyimpan data history transaksi.');
                    }

                    for ($i = 1; $i <= $request->angsuran; $i++) {
                        $detail_pinjaman = new DetailPinjaman();
                        $detail_pinjaman->id_pinjaman = $id_pinjaman;
                        $detail_pinjaman->id_users = Auth::id();
                        $detail_pinjaman->angsuran_pokok = round($angsuran_pokok);
                        $detail_pinjaman->bunga = round($bunga);
                        $detail_pinjaman->subtotal_angsuran = round($subtotal_angsuran);
                        $detail_pinjaman->angsuran_ke_ = $i;

                        $tanggal_jatuh_tempo = Carbon::now()->addMonths($i)->startOfDay();
                        $detail_pinjaman->tanggal_jatuh_tempo = $tanggal_jatuh_tempo;

                        if (!$detail_pinjaman->save()) {
                            throw new \Exception('Gagal menyimpan data detail simpanan.');
                        }
                    }
                } else {
                    throw new \Exception('Gagal menyimpan data simpanan.');
                }
            });

            return redirect()->route('pinjaman')->with('success', 'Data pinjaman berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $pinjaman = Pinjaman::where('id_pinjaman', $id)->with(['anggota', 'detail_pinjaman'])->first();
        $detail_pinjaman = DetailPinjaman::where('id_pinjaman', $id)->with(['pinjaman', 'users'])->get();
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

                $rowData[] = [
                    'DT_RowIndex' => $row->id_pinjaman,
                    'id_pinjaman' => $pinjaman->id_pinjaman,
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
        $angsuran = DetailPinjaman::where('id_pinjaman', $id)->first();

        return view('pages.pinjaman.show', ['pinjaman' => $pinjaman, 'angsuran' => $angsuran]);
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
        $pinjaman = Pinjaman::find($id);

        if (!$pinjaman) {
            return back()->with(['error' => 'Pinjaman tidak ditemukan. Silahkan coba kembali']);
        }

        $validator = Validator::make($request->all(), [
            'angsuran' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $angsuranDibayar = $request->input('angsuran');
        $totalAngsuran = $pinjaman->detail_pinjaman->where('status_pelunasan', 'Belum Lunas')->count();

        if ($angsuranDibayar > $totalAngsuran) {
            return back()->with(['error' => 'Jumlah angsuran yang akan dibayar melebihi jumlah angsuran yang belum dilunasi']);
        }

        $detailPinjaman = $pinjaman->detail_pinjaman()->where('status_pelunasan', 'Belum Lunas')->orderBy('angsuran_ke_')->get();

        foreach ($detailPinjaman as $index => $detail) {
            if ($index < $angsuranDibayar) {
                $detail->status_pelunasan = 'Lunas';
                $detail->save();

                $history = new HistoryTransaksi();
                $history->id_users = Auth::user()->id_users;
                $history->id_detail_pinjaman = $detail->id;
                $history->tipe_transaksi = 'Pemasukan';
                $history->save();
            } else {
                break;
            }
        }

        $subtotal = $pinjaman->detail_pinjaman()->where('status_pelunasan', 'Lunas')->get();
        $totalDibayar = $subtotal->sum('subtotal_angsuran');
        $pinjaman->sisa_lancar_keseluruhan -= $totalDibayar;
        $pinjaman->save();

        $sisaAngsuran = $totalAngsuran - $angsuranDibayar;

        return back()->with(['success' => 'Pelunasan berhasil. Sisa angsuran yang belum dilunasi kurang ' . $sisaAngsuran . 'X']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pinjaman = Pinjaman::where('id_pinjaman', $id)->first();
        $pinjaman->delete();
        return redirect()->route('pinjaman')->with('success', 'Pinjaman berhasil dihapus');
    }

    public function export($id)
    {
        $data = Pinjaman::find($id);
        $dataAnggota = anggota::where('id_anggota', $data->id_anggota)->first();
        if ($data) {
            $pinjaman = Pinjaman::where('id_pinjaman', $id)->with(['anggota', 'detail_pinjaman'])->get();
            $detail_pinjaman = DetailPinjaman::where('id_pinjaman', $id)->with(['pinjaman', 'users'])->get();
            $angsuran = DetailPinjaman::where('id_pinjaman', $id)->first();

            $html = view('pages.report.pinjaman', [
                'pinjaman' => $pinjaman,
                'detailPinjaman' => $detail_pinjaman,
                'angsuran' => $angsuran,
                'id' => $id
            ])->render();

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            $dompdf->stream('Pinjaman_' . $dataAnggota->nama . '.pdf');
        } else {
            return back()->withErrors(['error' => 'Data Pinjaman masih kosong. Silahkan coba kembali.']);
        }
    }

    private function generateMemberNumber()
    {
        $currentDate = now();
        $dateString = $currentDate->format('dmY');

        $randomNumber = '';
        for ($i = 0; $i < 6; $i++) {
            $randomNumber .= mt_rand(0, 9);
        }

        $memberNumber = 'P' . $dateString . $randomNumber;

        return $memberNumber;
    }

    private function hitungKas()
    {
        $setor = DetailSimpanan::where('jenis_transaksi', 'Setor')->get();
        $simpanan_pokok = $setor->sum('simpanan_pokok');
        $simpanan_wajib = $setor->sum('simpanan_wajib');
        $simpanan_sukarela = $setor->sum('simpanan_sukarela');

        $detail_pinjaman = DetailPinjaman::where('status_pelunasan', 'Lunas')->get();
        $angsuran_pokok = $detail_pinjaman->sum('angsuran_pokok');

        $pemasukan = $simpanan_pokok + $simpanan_wajib + $simpanan_sukarela + $angsuran_pokok;

        $tarik = DetailSimpanan::where('jenis_transaksi', 'Tarik')->get();
        $totalPenarikanPokok = $tarik->sum('simpanan_pokok');
        $totalPenarikanWajib = $tarik->sum('simpanan_wajib');
        $totalPenarikanSukarela = $tarik->sum('simpanan_sukarela');

        $pinjaman = Pinjaman::where('tanggal_realisasi', '!=', null)->get();
        $total_pinjaman = $pinjaman->sum('total_pinjaman');

        $pengeluaran = $totalPenarikanPokok + $totalPenarikanWajib + $totalPenarikanSukarela + $total_pinjaman;

        $pendapatan = abs($pemasukan - $pengeluaran);

        return $pendapatan;
    }
}