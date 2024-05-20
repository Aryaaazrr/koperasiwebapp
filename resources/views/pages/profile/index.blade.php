@extends('layouts.main')

@section('title', 'Profile')
@section('subtitle', 'Profile')

@section('content')
    <main class="container">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-8">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Ganti Password</label>
                                        <input class="form-control" type="password" name="new_password"
                                            placeholder="Password Baru">
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <div class="row mt-4">
                                <div class="col-md-12 text-end">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-warning">Perbarui</button>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-profile">
                        <img src="https://th.bing.com/th/id/R.8c4ed00bea132bf2f533e039485b065d?rik=7dA%2b1z6b8SydGw&riu=http%3a%2f%2fjti.polije.ac.id%2fimg%2fpages%2fpage_64f6efc15d8e6.jpg&ehk=9HvXn%2bx4O3QX63WH6JVXihekWTubnjx%2bQ61bNda7uYk%3d&risl=&pid=ImgRaw&r=0"
                            alt="Image placeholder" class="card-img-top">
                        <div class="card-body pt-0">
                            <div class="text-center mt-4">
                                <h5>
                                    {{ Auth::user()->nama }}
                                </h5>
                                <div class="h6 font-weight-300">
                                    <i class="ni location_pin mr-2"></i>{{ Auth::user()->email }}
                                </div>
                                <div class="h6 mt-4">
                                    <i class="ni education_hat mr-2"></i>Politeknik Negeri Jember
                                </div>
                            </div>
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
