@extends('layouts.main')

@section('title', 'Anggota')
@section('subtitle', 'Tambah Anggota')

@section('content')
    <main class="container">
        @if (Auth::user()->id_role == 1)
            <form action="{{ route('admin.anggota.create') }}" method='POST' enctype="multipart/form-data">
                @csrf
            @elseif (Auth::user()->id_role == 2)
                <form action="{{ route('anggota.create') }}" method='POST' enctype="multipart/form-data">
                    @csrf
                @else
                    <form action="{{ route('pegawai.anggota.create') }}" method='POST' enctype="multipart/form-data">
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
                <label for="nama" class="col-md-6 col-form-label">Nama Langkap</label>
                <input type="text" class="form-control" name='nama' id="nama" value="{{ old('nama') }}"
                    placeholder="Masukkan Nama Lengkap" required>
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
                <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name='alamat' id="alamat" value="{{ old('alamat') }}"
                        placeholder="Masukkan Alamat" required>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="no_telp" class="col-md-6 col-form-label">No Telepon</label>
                <input type="text" class="form-control" name='noTelp' id="no_telp" value="{{ old('no_telp') }}"
                    placeholder="Masukkan No Handphone" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="pekerjaan" class="col-md-6 col-form-label">Pekerjaan</label>
                <input type="text" class="form-control" name='pekerjaan' id="pekerjaan" value="{{ old('pekerjaan') }}"
                    placeholder="Masukkan Pekerjaan" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="jenisanggota" class="col-md-6 col-form-label">Jenis Anggota</label>
                <select class="form-select cursor-pointer" aria-label="Default select example" id="jenisanggota"
                    name="jenisanggota" value="{{ old('jenisanggota') }}" required>
                    <option value="" selected disabled>Pilih Jenis Anggota</option>
                    <option value="Pendiri">Pendiri</option>
                    <option value="Biasa">Biasa</option>
                </select>
            </div>

            <div class="mb-3 mt-4">
                <div class="col-sm-12 d-flex justify-content-between">
                    @if (Auth::user()->id_role == 1)
                        <a href="{{ route('admin.anggota') }}" class="btn btn-secondary">Kembali</a>
                    @elseif (Auth::user()->id_role == 2)
                        <a href="{{ route('anggota') }}" class="btn btn-secondary">Kembali</a>
                    @else
                        <a href="{{ route('pegawai.anggota') }}" class="btn btn-secondary">Kembali</a>
                    @endif
                    <button type="submit" class="btn btn-primary">SIMPAN</button>
                </div>
            </div>
        </div>
        </form>
    </main>
@endsection
