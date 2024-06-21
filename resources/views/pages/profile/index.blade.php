@extends('layouts.main')

@section('title', 'Profile')
@section('subtitle', 'Profile')

@section('content')
    <main class="container">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p>Profile</p>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (Auth::user()->id_role == 1)
                                <form action="{{ route('admin.profile.update', Auth::user()->id_users) }}" method='POST'
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                @elseif (Auth::user()->id_role == 2)
                                    <form action="{{ route('profile.update', Auth::user()->id_users) }}" method='POST'
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                    @else
                                        <form action="{{ route('pegawai.profile.update', Auth::user()->id_users) }}"
                                            method='POST' enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Nama</label>
                                        <input class="form-control" type="text" name="nama"
                                            value="{{ Auth::user()->nama }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Username</label>
                                        <input class="form-control" type="text" name="username"
                                            value="{{ Auth::user()->username }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">NIK</label>
                                        <input class="form-control" type="text" name="nik"
                                            value="{{ Auth::user()->nik }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Alamat</label>
                                        <input class="form-control" type="text" name="alamat"
                                            value="{{ Auth::user()->alamat }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Jenis Kelamin</label>
                                        <select class="form-select cursor-pointer" aria-label="Default select example"
                                            id="jeniskelamin" name="jeniskelamin" required>
                                            @if (Auth::user()->jenis_kelamin == 'Laki-Laki')
                                                <option value="" disabled>Pilih Jenis Kelamin</option>
                                                <option value="{{ Auth::user()->jenis_kelamin }}" selected>
                                                    {{ Auth::user()->jenis_kelamin }}</option>
                                                <option value="Perempuan">Perempuan</option>
                                            @elseif (Auth::user()->jenis_kelamin == 'Perempuan')
                                                <option value="" disabled>Pilih Jenis Kelamin</option>
                                                <option value="{{ Auth::user()->jenis_kelamin }}" selected>
                                                    {{ Auth::user()->jenis_kelamin }}</option>
                                                <option value="Laki-Laki">Laki-Laki</option>
                                            @else
                                                <option value="" selected disabled>Pilih Jenis Kelamin</option>
                                                <option value="Laki-Laki">Laki-Laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">No. Handphone</label>
                                        <input class="form-control" type="text" name="no_telp"
                                            value="{{ Auth::user()->no_telp }}">
                                    </div>
                                </div>
                                @if (Auth::user()->id_role != 3)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Ganti
                                                Password</label>
                                            <input class="form-control" type="password" name="new_password"
                                                placeholder="Password Baru">
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <hr class="horizontal dark">
                            @if (Auth::user()->id_role != 3)
                                <div class="row mt-4">
                                    <div class="col-md-12 text-end">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-warning">Perbarui</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}'
                });
            </script>
        @endif
        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oopss...',
                    text: '{{ $errors->first() }}'
                });
            </script>
        @endif
    </main>
@endsection
