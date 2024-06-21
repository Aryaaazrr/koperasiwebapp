<?php

namespace App\Http\Controllers;

use App\Exports\ExportAnggota;
use App\Models\anggota;
use App\Models\User;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class AnggotaController extends Controller
{

    public function index(Request $request)
    {
        $users = Anggota::orderBy('created_at', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($users)
                ->addColumn('DT_RowIndex', function ($user) {
                    return $user->id_anggota;
                })
                ->toJson();
        }

        return view('pages.anggota.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.anggota.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|digits:16|unique:users',
            'nama' => 'required',
            'jeniskelamin' => 'required|in:Laki-Laki,Perempuan',
            'alamat' => 'required',
            'noTelp' => 'required|numeric',
            'pekerjaan' => 'required',
            'jenisanggota' => 'required|in:Pendiri,Biasa',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $anggota = new Anggota();
        $anggota->nik = $request->nik;
        $anggota->no_anggota = $this->generateMemberNumber();
        $anggota->nama = $request->nama;
        $anggota->jenis_kelamin = $request->jeniskelamin;
        $anggota->alamat = $request->alamat;
        $anggota->no_telp = $request->noTelp;
        $anggota->pekerjaan = $request->pekerjaan;
        $anggota->jenis_anggota = $request->jenisanggota;

        if ($anggota->save()) {
            if (Auth::user()->id_role == 1) {
                return redirect()->route('admin.anggota')->with('success', 'Data anggota berhasil disimpan.');
            } elseif (Auth::user()->id_role == 2) {
                return redirect()->route('anggota')->with('success', 'Data anggota berhasil disimpan.');
            } else {
                return redirect()->route('pegawai.anggota')->with('success', 'Data anggota berhasil disimpan.');
            }
        } else {
            return response()->json(['message' => 'Terjadi kesalahan saat menambahkan data'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $users = anggota::find($id);
        return view('pages.anggota.edit', ['users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $anggota = anggota::find($id);

        if (!$anggota) {
            return back()->withErrors(['error' => 'Anggota tidak ditemukan. Silahkan coba kembali']);
        }

        $validator = Validator::make($request->all(), [
            'nik' => 'required|digits:16',
            'nama' => 'required',
            'jeniskelamin' => 'required|in:Laki-Laki,Perempuan',
            'alamat' => 'required',
            'noTelp' => 'required|numeric',
            'pekerjaan' => 'required',
        ]);


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $anggota->nik = $request->nik;
        $anggota->nama = $request->nama;
        $anggota->jenis_kelamin = $request->jeniskelamin;
        $anggota->alamat = $request->alamat;
        $anggota->no_telp = $request->noTelp;
        $anggota->pekerjaan = $request->pekerjaan;

        if ($anggota->save()) {
            if (Auth::user()->id_role == 1) {
                return redirect()->route('admin.anggota')->with('success', 'Data Anggota berhasil diperbarui.');
            } elseif (Auth::user()->id_role == 2) {
                return redirect()->route('anggota')->with('success', 'Data Anggota berhasil diperbarui.');
            } else {
                return redirect()->route('pegawai.anggota')->with('success', 'Data Anggota berhasil diperbarui.');
            }
        } else {
            return response()->json(['message' => 'Terjadi kesalahan saat menambahkan data'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $users = anggota::where('id_anggota', $id)->first();
        if ($users) {
            $users->delete();
            if (Auth::user()->id_role == 1) {
                return redirect()->route('admin.anggota')->with('success', 'Data anggota berhasil dihapus.');
            } elseif (Auth::user()->id_role == 2) {
                return redirect()->route('anggota')->with('success', 'Data anggota berhasil dihapus.');
            } else {
                return redirect()->route('pegawai.anggota')->with('success', 'Data anggota berhasil dihapus.');
            }
        } else {
            return response()->json(['message' => 'Terjadi kesalahan saat menambahkan data'], 500);
        }
    }

    public function export()
    {
        $data = anggota::count();
        if ($data != 0) {
            $anggota = anggota::all();
            $tahun = Carbon::now()->format('Y');
            $html = view('pages.report.anggota', compact('anggota', 'tahun'))->render();

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            $dompdf->stream('Anggota Koperasi.pdf');
        } else {
            return back()->withErrors(['error' => 'Data Anggota masih kosong. Silahkan coba kembali.']);
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

        $memberNumber = $dateString . $randomNumber;

        return $memberNumber;
    }
}