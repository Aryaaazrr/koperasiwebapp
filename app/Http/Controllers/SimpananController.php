<?php

namespace App\Http\Controllers;

use App\Exports\ExportSimpananAnggota;
use App\Models\anggota;
use App\Models\DetailSimpanan;
use App\Models\HistoryTransaksi;
use App\Models\simpanan;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class SimpananController extends Controller
{

    public function index(Request $request)
    {
        $simpanan = simpanan::with('anggota')->get();

        if ($request->ajax()) {
            return DataTables::of($simpanan)
                ->addColumn('DT_RowIndex', function ($simpanan) {
                    return $simpanan->id_users;
                })
                ->addColumn('no_anggota', function ($simpanan) {
                    return $simpanan->anggota->no_anggota;
                })
                ->addColumn('jenis_anggota', function ($simpanan) {
                    return $simpanan->anggota->jenis_anggota;
                })
                ->addColumn('nama', function ($simpanan) {
                    return $simpanan->anggota->nama;
                })
                ->addColumn('alamat', function ($simpanan) {
                    return $simpanan->anggota->alamat;
                })
                ->toJson();
        }

        return view('pages.simpanan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.simpanan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_anggota' => 'required|exists:anggota,id_anggota',
            'jenis_simpanan' => 'required|in:Simpanan Pokok,Simpanan Wajib,Simpanan Sukarela',
            'nominal' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $anggota = anggota::find($request->id_anggota);
        if ($anggota->simpanan) {
            return redirect()->back()->with(['error' => 'Anggota sudah memiliki simpanan.']);
        }

        try {
            DB::transaction(function () use ($request) {
                $simpanan = new Simpanan();
                $simpanan->id_anggota = $request->id_anggota;
                $simpanan->total_saldo = $request->nominal;

                if ($simpanan->save()) {
                    $id_simpanan = $simpanan->id_simpanan;

                    $detail_simpanan = new DetailSimpanan();
                    $detail_simpanan->id_simpanan = $id_simpanan;
                    $detail_simpanan->id_users = Auth::id();
                    $detail_simpanan->jenis_transaksi = 'Setor';
                    $detail_simpanan->subtotal_saldo = $request->nominal;

                    if ($request->jenis_simpanan == 'Simpanan Pokok') {
                        if ($request->jenis_anggota == 'Pendiri') {
                            if ($request->nominal == 500000) {
                                $detail_simpanan->simpanan_pokok = $request->nominal;
                            } else {
                                throw new \Exception('Simpanan pokok anggota pendiri harus Rp. 500.000.');
                            }
                        } else {
                            if ($request->nominal == 100000) {
                                $detail_simpanan->simpanan_pokok = $request->nominal;
                            } else {
                                throw new \Exception('Simpanan pokok anggota biasa harus Rp. 100.000.');
                            }
                        }
                    } else {
                        throw new \Exception('Anggota belum memiliki simpanan pokok');
                    }

                    if (!$detail_simpanan->save()) {
                        throw new \Exception('Gagal menyimpan data detail simpanan.');
                    }

                    $history = new HistoryTransaksi();
                    $history->id_users = Auth::user()->id_users;
                    $history->id_anggota = $request->id_anggota;
                    $history->id_detail_simpanan = $detail_simpanan->id;
                    $history->tipe_transaksi = 'Pemasukan';

                    if (!$history->save()) {
                        throw new \Exception('Gagal menyimpan data history transaksi.');
                    }
                } else {
                    throw new \Exception('Gagal menyimpan data simpanan.');
                }
            });

            if (Auth::user()->id_role == 2) {
                return redirect()->route('simpanan')->with('success', 'Data simpanan berhasil ditambahkan.');
            } else {
                return redirect()->route('pegawai.simpanan')->with('success', 'Data simpanan berhasil ditambahkan.');
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $simpanan = simpanan::where('id_simpanan', '=', $id)->with('anggota')->first();
        $detail_simpanan = DetailSimpanan::where('id_simpanan', $id)->with(['simpanan'])->orderBy('created_at', 'desc')->get();
        $setor = DetailSimpanan::where('id_simpanan', $id)
            ->where('jenis_transaksi', '=', 'Setor')
            ->get();
        $tarik = DetailSimpanan::where('id_simpanan', $id)
            ->where('jenis_transaksi', '=', 'Tarik')
            ->get();

        $totalSimpananPokok = $setor->sum('simpanan_pokok');
        $totalSimpananWajib = $setor->sum('simpanan_wajib');
        $totalSimpananSukarela = $setor->sum('simpanan_sukarela');

        $totalPenarikanPokok = $tarik->sum('simpanan_pokok');
        $totalPenarikanWajib = $tarik->sum('simpanan_wajib');
        $totalPenarikanSukarela = $tarik->sum('simpanan_sukarela');

        $totalSimpananPokok -= $totalPenarikanPokok;
        $totalSimpananWajib -= $totalPenarikanWajib;
        $totalSimpananSukarela -= $totalPenarikanSukarela;
        $rowData = [];

        if ($request->ajax()) {
            foreach ($detail_simpanan as $row) {
                $simpanan = $row->simpanan;
                $anggota = $simpanan->anggota;

                $rowData[] = [
                    'id' => $row->id,
                    'DT_RowIndex' => $row->id_simpanan,
                    'id_simpanan' => $simpanan->id_simpanan,
                    'jenis_anggota' => $anggota->jenis_anggota,
                    'total_saldo' => $simpanan->total_saldo,
                    'jenis_transaksi' => $row->jenis_transaksi,
                    'simpanan_pokok' => $row->simpanan_pokok,
                    'simpanan_wajib' => $row->simpanan_wajib,
                    'simpanan_sukarela' => $row->simpanan_sukarela,
                    'subtotal_saldo' => $row->subtotal_saldo,
                    'created_at' => $row->created_at->format('d-m-Y h:i:s')
                ];
            }

            return DataTables::of($rowData)->toJson();
        }

        return view('pages.simpanan.show', [
            'simpanan' => $simpanan,
            'total_simpanan_pokok' => $totalSimpananPokok,
            'total_simpanan_wajib' => $totalSimpananWajib,
            'total_simpanan_sukarela' => $totalSimpananSukarela
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('pages.simpanan.edit', compact($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->id_anggota == 'update_detail') {
            $validator = Validator::make($request->all(), [
                'jenis_transaksi' => 'required|in:Setor,Tarik',
                'nominal_simpanan_pokok' => 'numeric|regex:/^\d+(\.\d{1,2})?$/',
                'nominal_simpanan_wajib' => 'numeric|regex:/^\d+(\.\d{1,2})?$/',
                'nominal_simpanan_sukarela' => 'numeric|regex:/^\d+(\.\d{1,2})?$/'
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $jenisAnggota = $request->jenis_anggota;
            $jenisTransaksiLama = $request->jenis_lama;
            $jenisTransaksi = $request->jenis_transaksi;
            $subtotal_saldo = $request->subtotal_saldo_saat_ini;

            $nominalBaruSimpananPokok = $request->nominal_simpanan_pokok;
            $nominalBaruSimpananWajib = $request->nominal_simpanan_wajib;
            $nominalBaruSimpananSukarela = $request->nominal_simpanan_sukarela;
            $nominalLamaSimpananSukarela = $request->nominal_lama;

            $targetRow = DetailSimpanan::where('id_simpanan', $id)->where('subtotal_saldo', $subtotal_saldo)->first();
            $simpanan = simpanan::where('id_simpanan', $id)->first();

            if ($targetRow) {
                $dataDetailSimpanan = DetailSimpanan::where('id_simpanan', $id)->where('created_at', '>=', $targetRow->created_at)->get();
                $simpananSukarelaSetor = DetailSimpanan::where('id_simpanan', '=', $id)
                    ->where('jenis_transaksi', '=', 'Setor')
                    ->sum('simpanan_sukarela');
                $simpananSukarelaTarik = DetailSimpanan::where('id_simpanan', '=', $id)
                    ->where('jenis_transaksi', '=', 'Tarik')
                    ->sum('simpanan_sukarela');
                $simpananSukarela = $simpananSukarelaSetor - $simpananSukarelaTarik;

                // dd($dataDetailSimpanan);
                foreach ($dataDetailSimpanan as $detailSimpanan) {
                    if ($request->nominal_simpanan_sukarela != 0 && $request->nominal_simpanan_wajib != 0) {
                        if ($jenisTransaksi == 'Setor') {
                            $simpanan_wajib = DetailSimpanan::where('id_simpanan', '=', $id)->whereNotNull('simpanan_wajib')->first('simpanan_wajib');
                            if ($simpanan_wajib) {
                                $simpanan_wajib_sebelum = $simpanan_wajib->simpanan_wajib;
                                if ($simpanan_wajib_sebelum > 0) {
                                    if ($nominalBaruSimpananWajib == $simpanan_wajib_sebelum) {
                                        $selisih = abs($nominalBaruSimpananSukarela - $nominalLamaSimpananSukarela);
                                        if ($nominalBaruSimpananSukarela > $nominalLamaSimpananSukarela) {
                                            $detailSimpanan->subtotal_saldo += $selisih;
                                        } else {
                                            $detailSimpanan->subtotal_saldo -= $selisih;
                                        }
                                        $targetRow->simpanan_wajib = $nominalBaruSimpananWajib;
                                        $targetRow->simpanan_sukarela = $nominalBaruSimpananSukarela;
                                    } else {
                                        if ($nominalBaruSimpananWajib > $simpanan_wajib_sebelum) {
                                            $targetRow->simpanan_wajib = $simpanan_wajib_sebelum;
                                            $targetRow->simpanan_sukarela = $nominalBaruSimpananWajib - $simpanan_wajib_sebelum;
                                        } else {
                                            return back()->with('error', 'Simpanan Wajib harus senilai dengan setoran sebelumnya yaitu Rp ' . number_format($simpanan_wajib_sebelum, 2, ',', '.'));
                                        }
                                    }
                                } else {
                                    $selisih = abs($nominalBaruSimpananSukarela - $nominalLamaSimpananSukarela);
                                    if ($nominalBaruSimpananSukarela > $nominalLamaSimpananSukarela) {
                                        $detailSimpanan->subtotal_saldo += $selisih;
                                    } else {
                                        $detailSimpanan->subtotal_saldo -= $selisih;
                                    }
                                    $targetRow->simpanan_wajib = $nominalBaruSimpananWajib;
                                    $targetRow->simpanan_sukarela = $nominalBaruSimpananSukarela;
                                }
                            } else {
                                $selisih = abs($nominalBaruSimpananSukarela - $nominalLamaSimpananSukarela);
                                if ($nominalBaruSimpananSukarela > $nominalLamaSimpananSukarela) {
                                    $detailSimpanan->subtotal_saldo += $selisih;
                                } else {
                                    $detailSimpanan->subtotal_saldo -= $selisih;
                                }
                                $targetRow->simpanan_wajib = $nominalBaruSimpananWajib;
                                $targetRow->simpanan_sukarela = $nominalBaruSimpananSukarela;
                            }
                        } else {
                            if ($request->nominal_simpanan_wajib != 0) {
                                return back()->with('error', 'Simpanan wajib tidak bisa ditarik');
                            }
                        }
                    } elseif ($request->nominal_simpanan_sukarela != 0) {
                        if ($jenisTransaksi == 'Setor') {
                            $selisih = abs($nominalBaruSimpananSukarela - $nominalLamaSimpananSukarela);
                            if ($nominalBaruSimpananSukarela > $nominalLamaSimpananSukarela) {
                                $detailSimpanan->subtotal_saldo += $selisih;
                            } else {
                                $detailSimpanan->subtotal_saldo -= $selisih;
                            }
                            $targetRow->simpanan_sukarela = $nominalBaruSimpananSukarela;
                        } else {
                            if ($simpananSukarela > $nominalBaruSimpananSukarela) {
                                return back()->with('error', 'Jenis Transaksi berbeda');
                            } else {
                                return back()->with('error', 'Saldo Simpanan sukarela tidak cukup');
                            }
                        }
                    } elseif ($request->nominal_simpanan_wajib != 0) {
                        if ($nominalBaruSimpananSukarela) {
                            if ($nominalBaruSimpananSukarela == 0) {
                                $selisih = abs($nominalBaruSimpananSukarela - $nominalLamaSimpananSukarela);
                                if ($nominalBaruSimpananSukarela > $nominalLamaSimpananSukarela) {
                                    $detailSimpanan->subtotal_saldo += $selisih;
                                } else {
                                    $detailSimpanan->subtotal_saldo -= $selisih;
                                }
                                $targetRow->simpanan_sukarela = null;
                            }
                        } else {
                            if ($jenisTransaksi == 'Setor') {
                                return back()->with('error', 'Simpanan wajib sudah valid');
                            } else {
                                return back()->with('error', 'Simpanan wajib tidak bisa ditarik');
                            }
                        }
                    } else {
                        if ($jenisTransaksi == 'Setor') {
                            return back()->with('error', 'Simpanan pokok sudah valid');
                        } else {
                            return back()->with('error', 'Simpanan pokok tidak bisa ditarik');
                        }
                    }
                    $saved = $detailSimpanan->save();
                    if (!$saved) {
                        return back()->with('error', 'Gagal menyimpan perubahan pada DetailSimpanan.');
                    }
                    $dataUpdate[] = $detailSimpanan;
                }
                $dataUpdateRow[] = $targetRow;

                $savedTargetRow = $targetRow->save();
                if (!$savedTargetRow) {
                    return back()->with('error', 'Gagal menyimpan perubahan pada targetRow.');
                }

                $dataSubtotal = DetailSimpanan::orderBy('created_at', 'desc')
                    ->first();
                $simpanan->total_saldo = $dataSubtotal->subtotal_saldo;
                $savedTotal = $simpanan->save();
                if (!$savedTotal) {
                    return back()->with('error', 'Gagal menyimpan perubahan pada total saldo.');
                }

                if (Auth::user()->id_role == 2) {
                    return back()->with('success', 'Data detail simpanan berhasil diperbarui.');
                } else {
                    return back()->with('success', 'Data detail simpanan berhasil diperbarui.');
                }
            } else {
                return back()->with('error', 'Baris tidak ditemukan.');
            }
        } else {
            $data = simpanan::find($id);
            $detail_simpanan_sukarela = DetailSimpanan::where('id_simpanan', '=', $id)->sum('simpanan_sukarela');

            if (!$data) {
                return back()->with(['error' => 'Simpanan tidak ditemukan. Silahkan coba kembali']);
            }

            $validator = Validator::make($request->all(), [
                'id_anggota' => 'required|exists:anggota,id_anggota',
                'jenis_simpanan' => 'required|in:Simpanan Pokok,Simpanan Wajib,Simpanan Sukarela',
                'nominal' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/'
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            try {
                $saldoSebelumSetoran = $data->total_saldo;
                $jenisTransaksi = $request->jenis_transaksi;
                $jenisSimpanan = $request->jenis_simpanan;
                $jenisAnggota = $request->jenis_anggota;

                if ($jenisTransaksi == 'Setor') {
                    $saldoSebelumSetoran += $request->nominal;
                    $data->total_saldo += $request->nominal;
                } elseif ($jenisTransaksi == 'Tarik') {
                    if ($jenisSimpanan == 'Simpanan Sukarela' && $request->nominal > $detail_simpanan_sukarela) {
                        throw new \Exception('Transaksi gagal. Saldo sukarela tidak cukup');
                    }
                    $saldoSebelumSetoran -= $request->nominal;
                    $data->total_saldo -= $request->nominal;
                }

                DB::transaction(function () use ($data, $saldoSebelumSetoran, $jenisTransaksi, $jenisSimpanan, $request) {
                    $detail_simpanan = new DetailSimpanan();
                    $detail_simpanan->id_simpanan = $data->id_simpanan;
                    $detail_simpanan->id_users = Auth::id();
                    $detail_simpanan->jenis_transaksi = $jenisTransaksi;
                    $detail_simpanan->subtotal_saldo = $saldoSebelumSetoran;

                    if ($jenisSimpanan == 'Simpanan Wajib') {
                        $simpanan_wajib = DetailSimpanan::where('id_simpanan', '=', $data->id_simpanan)->whereNotNull('simpanan_wajib')->first('simpanan_wajib');
                        if ($simpanan_wajib) {
                            $simpanan_wajib_sebelum = $simpanan_wajib->simpanan_wajib;
                            if ($simpanan_wajib_sebelum > 0) {
                                if ($request->nominal == $simpanan_wajib_sebelum) {
                                    $detail_simpanan->simpanan_wajib = $request->nominal;
                                } else {
                                    if ($request->nominal > $simpanan_wajib_sebelum) {
                                        $detail_simpanan->simpanan_wajib = $simpanan_wajib_sebelum;
                                        $detail_simpanan->simpanan_sukarela = $request->nominal - $simpanan_wajib_sebelum;
                                    } else {
                                        throw new \Exception('Simpanan Wajib harus senilai dengan setoran sebelumnya yaitu Rp ' . number_format($simpanan_wajib_sebelum, 2, ',', '.'));
                                    }
                                }
                            } else {
                                $detail_simpanan->simpanan_wajib = $request->nominal;
                            }
                        } else {
                            $detail_simpanan->simpanan_wajib = $request->nominal;
                        }
                    } else {
                        $detail_simpanan->simpanan_sukarela = $request->nominal;
                    }

                    if (!$data->save() || !$detail_simpanan->save()) {
                        throw new \Exception('Gagal menyimpan transaksi simpanan. Silahkan coba lagi');
                    }

                    $history = new HistoryTransaksi();
                    $history->id_users = Auth::user()->id_users;
                    $history->id_anggota = $request->id_anggota;
                    $history->id_detail_simpanan = $detail_simpanan->id;
                    if ($jenisTransaksi == 'Setor') {
                        $history->tipe_transaksi = 'Pemasukan';
                    } else {
                        $history->tipe_transaksi = 'Pengeluaran';
                    }

                    if (!$history->save()) {
                        throw new \Exception('Gagal menyimpan data history transaksi.');
                    }
                });

                if (Auth::user()->id_role == 2) {
                    return redirect()->route('simpanan')->with('success', 'Data simpanan berhasil disimpan.');
                } else {
                    return redirect()->route('pegawai.simpanan')->with('success', 'Data simpanan berhasil disimpan.');
                }
            } catch (\Exception $e) {
                return back()->withErrors(['error' => $e->getMessage()]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = simpanan::where('id_simpanan', $id)->first();
        $detail->delete();
        return redirect()->route('simpanan')->with('success', 'Simpanan berhasil dihapus');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyDetail(string $id)
    {
        $detail = DetailSimpanan::where('id', $id)->first();
        if ($detail) {
            $jenis_transaksi = $detail->jenis_transaksi;
            $simpanan_pokok = $detail->simpanan_pokok;
            $simpanan_wajib = $detail->simpanan_wajib;

            if ($simpanan_pokok > 0) {
                return back()->with('error', 'Simpanan Pokok tidak dapat dihapus.');
            // } elseif ($simpanan_wajib > 0) {
            //     return back()->with('error', 'Simpanan Wajib tidak dapat dihapus.');
            } else {
                $simpanan = Simpanan::where('id_simpanan', $detail->id_simpanan)->first();
                $saldoSebelum = 0.00;

                $dataDetailSimpananSebelum = DetailSimpanan::where('id_simpanan', $detail->id_simpanan)
                    ->where('created_at', '<', $detail->created_at)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($dataDetailSimpananSebelum) {
                    $saldoSebelum = $dataDetailSimpananSebelum->subtotal_saldo;
                }

                $detail->delete();

                $detailSetelah = DetailSimpanan::where('id_simpanan', $detail->id_simpanan)
                    ->where('created_at', '>', $detail->created_at)
                    ->first();

                $saldoSetelah = $saldoSebelum;
                if ($detailSetelah) {
                    $saldoSetelah += $detailSetelah->simpanan_sukarela;
                }

                $detail->subtotal_saldo = $saldoSetelah;
                $simpanan->total_saldo = $saldoSetelah;
                $saved = $simpanan->save();

                if ($saved) {
                    return back()->with('success', 'Data detail simpanan berhasil dihapus.');
                } else {
                    return back()->with('error', 'Gagal menyimpan perubahan pada total saldo.');
                }
            }
        } else {
            return back()->with('error', 'Detail Simpanan tidak ditemukan.');
        }
    }


    public function export($id)
    {
        $data = simpanan::find($id);
        $dataAnggota = anggota::where('id_anggota', $data->id_anggota)->first();
        if ($data) {
            $simpanan = Simpanan::with(['anggota', 'detail_simpanan'])->where('id_simpanan', $id)->get();
            $detail_simpanan = DetailSimpanan::where('id_simpanan', $id)->with(['simpanan'])->get();
            $setor = DetailSimpanan::where('id_simpanan', $id)
                ->where('jenis_transaksi', '=', 'Setor')
                ->get();
            $tarik = DetailSimpanan::where('id_simpanan', $id)
                ->where('jenis_transaksi', '=', 'Tarik')
                ->get();

            $totalSimpananPokok = $setor->sum('simpanan_pokok');
            $totalSimpananWajib = $setor->sum('simpanan_wajib');
            $totalSimpananSukarela = $setor->sum('simpanan_sukarela');

            $totalPenarikanPokok = $tarik->sum('simpanan_pokok');
            $totalPenarikanWajib = $tarik->sum('simpanan_wajib');
            $totalPenarikanSukarela = $tarik->sum('simpanan_sukarela');

            $totalSimpananPokok -= $totalPenarikanPokok;
            $totalSimpananWajib -= $totalPenarikanWajib;
            $totalSimpananSukarela -= $totalPenarikanSukarela;

            $html = view('pages.report.simpanan', [
                'simpanan' => $simpanan,
                'detailSimpanan' => $detail_simpanan,
                'totalSimpananPokok' => $totalSimpananPokok,
                'totalSimpananWajib' => $totalSimpananWajib,
                'totalSimpananSukarela' => $totalSimpananSukarela,
                'id' => $id
            ])->render();

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            $dompdf->stream('Simpanan_' . $dataAnggota->nama . '.pdf');
        } else {
            return back()->withErrors(['error' => 'Data Simpanan masih kosong. Silahkan coba kembali.']);
        }
    }
}