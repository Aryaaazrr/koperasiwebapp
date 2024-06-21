<?php

namespace App\Http\Controllers;

use App\Exports\ExportPegawai;
use App\Models\Anggota;
use App\Models\User;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::where('id_role', '!=', 1)->with('role')->orderBy('created_at', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($users)
                ->addColumn('DT_RowIndex', function ($user) {
                    return $user->id_users;
                })
                ->toJson();
        }

        return view('pages.pegawai.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.pegawai.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|digits:16|unique:users',
            'nama' => 'required',
            'username' => 'required|unique:users',
            'jeniskelamin' => 'required|in:Laki-Laki,Perempuan',
            'alamat' => 'required',
            'noTelp' => 'required|numeric',
            'password' => 'required|min:8',
            'role' => 'required|in:2,3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pegawai = new User();
        $pegawai->nik = $request->nik;
        $pegawai->nama = $request->nama;
        $pegawai->username = $request->username;
        $pegawai->password = Hash::make($request->password);
        $pegawai->jenis_kelamin = $request->jeniskelamin;
        $pegawai->alamat = $request->alamat;
        $pegawai->no_telp = $request->noTelp;
        $pegawai->id_role = $request->role;

        if ($pegawai->save()) {
            if (Auth::user()->id_role == 1) {
                return redirect()->route('admin.pegawai')->with('success', 'Data pegawai berhasil disimpan.');
            } elseif (Auth::user()->id_role == 2) {
                return redirect()->route('pegawai')->with('success', 'Data pegawai berhasil disimpan.');
            } else {
                return redirect()->route('pegawai.pegawai')->with('success', 'Data pegawai berhasil disimpan.');
            }
        } else {
            return response()->json(['message' => 'Terjadi kesalahan saat menambahkan data'], 500);
        }
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
        $users = User::with('role')->find($id);
        return view('pages.pegawai.edit', ['users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pegawai = User::find($id);

        if (!$pegawai) {
            return back()->withErrors(['error' => 'Pegawai tidak ditemukan. Silahkan coba kembali']);
        }

        if ($request->new_password == null) {
            $validator = Validator::make($request->all(), [
                'nik' => 'required|digits:16',
                'nama' => 'required',
                'username' => 'required|unique:users,username,' . $id . ',id_users',
                'jeniskelamin' => 'required|in:Laki-Laki,Perempuan',
                'alamat' => 'required',
                'noTelp' => 'required|numeric',
                'role' => 'required|in:2,3',
            ]);


            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $pegawai->nik = $request->nik;
            $pegawai->nama = $request->nama;
            $pegawai->username = $request->username;
            $pegawai->jenis_kelamin = $request->jeniskelamin;
            $pegawai->alamat = $request->alamat;
            $pegawai->no_telp = $request->noTelp;
            $pegawai->id_role = $request->role;
        } else {
            $validator = Validator::make($request->all(), [
                'nik' => 'required|digits:16',
                'nama' => 'required',
                'username' => 'required|unique:users,username,' . $id . ',id_users',
                'jeniskelamin' => 'required|in:Laki-Laki,Perempuan',
                'alamat' => 'required',
                'noTelp' => 'required|numeric',
                'new_password' => 'required|min:8',
                'role' => 'required|in:2,3',
            ]);


            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $pegawai->nik = $request->nik;
            $pegawai->nama = $request->nama;
            $pegawai->username = $request->username;
            $pegawai->jenis_kelamin = $request->jeniskelamin;
            $pegawai->alamat = $request->alamat;
            $pegawai->no_telp = $request->noTelp;
            $pegawai->id_role = $request->role;
            $pegawai->password = Hash::make($request->new_password);
        }

        if ($pegawai->save()) {
            if (Auth::user()->id_role == 1) {
                return redirect()->route('admin.pegawai')->with('success', 'Data pegawai berhasil diperbarui.');
            } elseif (Auth::user()->id_role == 2) {
                return redirect()->route('pegawai')->with('success', 'Data pegawai berhasil diperbarui.');
            } else {
                return redirect()->route('pegawai.pegawai')->with('success', 'Data pegawai berhasil diperbarui.');
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
        $users = User::where('id_users', $id)->first();
        if ($users) {
            $users->delete();
            if (Auth::user()->id_role == 1) {
                return redirect()->route('admin.pegawai')->with('success', 'Data pegawai berhasil dihapus.');
            } elseif (Auth::user()->id_role == 2) {
                return redirect()->route('pegawai')->with('success', 'Data pegawai berhasil dihapus.');
            } else {
                return redirect()->route('pegawai.pegawai')->with('success', 'Data pegawai berhasil dihapus.');
            }
        } else {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data'], 500);
        }
    }

    public function export()
    {
        $data = User::where('id_role', '=', 3)->count();
        if ($data != 0) {
            $users = User::where('id_role', 3)->get();
            $tahun = Carbon::now()->format('Y');
            $html = view('pages.report.pegawai', compact('users', 'tahun'))->render();

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $dompdf->stream('Pegawai Koperasi.pdf');
        } else {
            return back()->withErrors(['error' => 'Data Pegawai masih kosong. Silahkan coba kembali.']);
        }
    }
}
