<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.profile.index');
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
        $users = User::find($id);

        if (!$users) {
            return back()->withErrors(['error' => 'Pengguna tidak ditemukan. Silahkan coba kembali']);
        }

        if ($request->new_password == null) {
            $validator = Validator::make($request->all(), [
                'nik' => 'required|digits:16|unique:users,nik,' . $id . ',id_users',
                'nama' => 'required',
                'username' => 'required|unique:users,username,' . $id . ',id_users',
                'jeniskelamin' => 'required|in:Laki-Laki,Perempuan',
                'alamat' => 'required',
                'no_telp' => 'required|numeric',
            ]);


            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $users->nik = $request->nik;
            $users->nama = $request->nama;
            $users->username = $request->username;
            $users->jenis_kelamin = $request->jeniskelamin;
            $users->alamat = $request->alamat;
            $users->no_telp = $request->no_telp;
        } else {
            $validator = Validator::make($request->all(), [
                'nik' => 'required|digits:16|unique:users,nik,' . $id . ',id_users',
                'nama' => 'required',
                'username' => 'required|unique:users,username,' . $id . ',id_users',
                'jeniskelamin' => 'required|in:Laki-Laki,Perempuan',
                'alamat' => 'required',
                'no_telp' => 'required|numeric',
                'new_password' => 'required|min:8',
            ]);


            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $users->nik = $request->nik;
            $users->nama = $request->nama;
            $users->username = $request->username;
            $users->jenis_kelamin = $request->jeniskelamin;
            $users->alamat = $request->alamat;
            $users->no_telp = $request->no_telp;
            $users->password = Hash::make($request->new_password);
        }

        if ($users->save()) {
            if (Auth::user()->id_role == 1) {
                return redirect()->route('admin.profile')->with('success', 'Data Profile berhasil diperbarui.');
            } elseif (Auth::user()->id_role == 2) {
                return redirect()->route('profile')->with('success', 'Data Profile berhasil diperbarui.');
            } else {
                return redirect()->route('pegawai.profile')->with('success', 'Data Profile berhasil diperbarui.');
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
        //
    }
}