@extends('layouts.main')

@section('title', 'Anggota')
@if (Auth::user()->id_role == 3)
    @section('subtitle', 'Lihat Anggota')
@else
    @section('subtitle', 'Edit Anggota')
@endif

@section('content')
    <main class="container">
        @if (Auth::user()->id_role == 1)
            <form action="{{ route('admin.anggota.update', $users->id_anggota) }}" method='POST'
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
            @elseif (Auth::user()->id_role == 2)
                <form action="{{ route('anggota.update', $users->id_anggota) }}" method='POST' enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                @else
                    <form action="{{ route('pegawai.anggota.update', $users->id_anggota) }}" method='POST'
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
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
                <label for="nik" class="col-sm-2 col-form-label">NIK</label>
                @if (Auth::user()->id_role == 3)
                    <input type="text" class="form-control" id="nik" name='nik' value="{{ $users->nik }}"
                        placeholder="Masukkan NIK" required readonly>
                @else
                    <input type="text" class="form-control" id="nik" name='nik' value="{{ $users->nik }}"
                        placeholder="Masukkan NIK" required>
                @endif
            </div>

            <div class="col-md-6 mb-3">
                <label for="nama" class="col-md-6 col-form-label">Nama Lengkap</label>
                @if (Auth::user()->id_role == 3)
                    <input type="text" class="form-control" id="nama" name='nama' value="{{ $users->nama }}"
                        placeholder="Masukkan Nama Lengkap" required readonly>
                @else
                    <input type="text" class="form-control" id="nama" name='nama' value="{{ $users->nama }}"
                        placeholder="Masukkan Nama Lengkap" required>
                @endif
            </div>

            <div class="col-md-6 mb-3">
                <label for="jeniskelamin" class="col-md-6 col-form-label">Jenis Kelamin</label>
                <select class="form-select cursor-pointer" aria-label="Default select example" id="jeniskelamin"
                    name="jeniskelamin" required>
                    @if (Auth::user()->id_role == 3)
                        <option value="" disabled>Pilih Jenis Kelamin</option>
                        <option value="{{ $users->jenis_kelamin }}" selected>{{ $users->jenis_kelamin }}</option>
                    @else
                        @if ($users->jenis_kelamin == 'Laki-Laki')
                            <option value="" disabled>Pilih Jenis Kelamin</option>
                            <option value="{{ $users->jenis_kelamin }}" selected>{{ $users->jenis_kelamin }}</option>
                            <option value="Perempuan">Perempuan</option>
                        @elseif ($users->jenis_kelamin == 'Perempuan')
                            <option value="" disabled>Pilih Jenis Kelamin</option>
                            <option value="{{ $users->jenis_kelamin }}" selected>{{ $users->jenis_kelamin }}</option>
                            <option value="Laki-Laki">Laki-Laki</option>
                        @else
                            <option value="" selected disabled>Pilih Jenis Kelamin</option>
                            <option value="Laki-Laki">Laki-Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        @endif
                    @endif
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="alamat" class="col-md-6 col-form-label">Alamat</label>
                @if (Auth::user()->id_role == 3)
                    <input type="text" class="form-control" name='alamat' value="{{ $users->alamat }}"
                        placeholder="Masukkan Alamat" required readonly>
                @else
                    <input type="text" class="form-control" name='alamat' value="{{ $users->alamat }}"
                        placeholder="Masukkan Alamat" required>
                @endif
            </div>

            <div class="col-md-6 mb-3">
                <label for="noTelp" class="col-md-6 col-form-label">No Handphone</label>
                @if (Auth::user()->id_role == 3)
                    <input type="text" class="form-control" id="noTelp" name='noTelp' value="{{ $users->no_telp }}"
                        placeholder="Masukkan No Handphone" required readonly>
                @else
                    <input type="text" class="form-control" id="noTelp" name='noTelp' value="{{ $users->no_telp }}"
                        placeholder="Masukkan No Handphone" required>
                @endif
            </div>

            <div class="col-md-6 mb-3">
                <label for="pekerjaan" class="col-md-6 col-form-label">Pekerjaan</label>
                @if (Auth::user()->id_role == 3)
                    <input type="text" class="form-control" name='pekerjaan' id="pekerjaan"
                        value="{{ $users->pekerjaan }}" placeholder="Masukkan Pekerjaan" required readonly>
                @else
                    <input type="text" class="form-control" name='pekerjaan' id="pekerjaan"
                        value="{{ $users->pekerjaan }}" placeholder="Masukkan Pekerjaan" required>
                @endif
            </div>

            <div class="col-md-6 mb-3">
                <label for="tanggal_masuk" class="col-md-6 col-form-label">Tanggal Masuk</label>
                @if (Auth::user()->id_role == 3)
                    <input type="date" class="form-control" name='tanggalmasuk' id="tanggal_masuk"
                        value="{{ $users->tanggal_masuk }}" required readonly>
                @else
                    <input type="date" class="form-control" name='tanggalmasuk' id="tanggal_masuk"
                        value="{{ $users->tanggal_masuk }}" required>
                @endif
            </div>

            <div class="col-md-6 mb-3">
                <label for="jenisanggota" class="col-md-6 col-form-label">Jenis Anggota</label>
                <select class="form-select cursor-pointer" aria-label="Default select example" id="jenisanggota"
                    name="jenisanggota" required>
                    <option value="" disabled>Pilih Jenis Anggota</option>
                    <option value="{{ $users->jenis_anggota }}" selected>{{ $users->jenis_anggota }}</option>
                </select>
            </div>

            <div class="mb-3 row mt-5">
                <div class="col-sm-12 d-flex justify-content-between">
                    @if (Auth::user()->id_role == 1)
                        <a href="{{ route('admin.anggota') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-warning">Perbarui</button>
                    @elseif (Auth::user()->id_role == 2)
                        <a href="{{ route('anggota') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-warning">Perbarui</button>
                    @else
                        <a href="{{ route('pegawai.anggota') }}" class="btn btn-secondary">Kembali</a>
                    @endif
                </div>
            </div>
        </div>
        </form>
    </main>
@endsection
