@extends('layouts.main')

@section('title', 'Pegawai')
@section('subtitle', 'Tambah Pegawai')

@section('content')
    <main class="container">
        @if (Auth::user()->id_role == 1)
            <form action="{{ route('admin.pegawai.store') }}" method='POST' enctype="multipart/form-data">
                @csrf
            @elseif (Auth::user()->id_role == 2)
                <form action="{{ route('pegawai.store') }}" method='POST' enctype="multipart/form-data">
                    @csrf
                @else
                    <form action="{{ route('pegawai.pegawai.store') }}" method='POST' enctype="multipart/form-data">
                        @csrf
        @endif
        <div class="row my-3 p-3 bg-body rounded shadow-sm">
            {!! session('msg') !!}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>
            @endif
            <div class="col-md-6 mb-3">
                <label for="nik" class="col-md-6 col-form-label">NIK</label>
                <input type="text" class="form-control" id="nik" name='nik' value="{{ old('nik') }}"
                    placeholder="Masukkan NIK" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="nama" class="col-md-6 col-form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama" name='nama' value="{{ old('nama') }}"
                    placeholder="Masukkan Nama Lengkap" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="username" class="col-md-6 col-form-label">Username</label>
                <input type="username" class="form-control" id="username" name='username' value="{{ old('username') }}"
                    placeholder="Masukkan Username" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="jeniskelamin" class="col-md-6 col-form-label">Jenis Kelamin</label>
                <select class="form-select cursor-pointer" aria-label="Default select example" id="jeniskelamin"
                    name="jeniskelamin" value="{{ old('jeniskelamin') }}" required>
                    <option value="" selected disabled>Pilih Jenis Kelamin</option>
                    <option value="Laki-Laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="alamat" class="col-md-6 col-form-label">Alamat</label>
                <input type="text" class="form-control" name='alamat' value="{{ old('alamat') }}"
                    placeholder="Masukkan Alamat" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="noTelp" class="col-md-6 col-form-label">No Handphone</label>
                <input type="text" class="form-control" id="noTelp" name='noTelp' value="{{ old('noTelp') }}"
                    placeholder="Masukkan No Handphone" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="password" class="col-md-6 col-form-label">Password</label>
                <input type="password" class="form-control" id="password" name='password' value="{{ old('password') }}"
                    placeholder="Masukkan Password" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="role" class="col-md-6 col-form-label">Role Pengguna</label>
                <select class="form-select cursor-pointer" aria-label="Default select example" id="role" name="role"
                    value="{{ old('role') }}" required>
                    <option value="" selected disabled>Pilih Role Pengguna</option>
                    <option value="2">Pengurus Koperasi</option>
                    <option value="3">Pegawai Koperasi</option>
                </select>
            </div>

            <div class="mb-3 mt-4">
                <div class="col-sm-12 d-flex justify-content-between">
                    @if (Auth::user()->id_role == 1)
                        <a href="{{ route('admin.pegawai') }}" class="btn btn-secondary">Kembali</a>
                    @elseif (Auth::user()->id_role == 2)
                        <a href="{{ route('pegawai') }}" class="btn btn-secondary">Kembali</a>
                    @else
                        <a href="{{ route('pegawai.pegawai') }}" class="btn btn-secondary">Kembali</a>
                    @endif
                    <button type="submit" class="btn btn-primary">SIMPAN</button>
                </div>
            </div>
        </div>
        </form>
    </main>
@endsection
