@extends('layouts.main')

@section('title', 'Simpanan')
@section('subtitle', 'Data Simpanan')

@section('content')

    <body class="bg-light">
        <main class="container">
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="pb-2">
                    @if (Auth::user()->id_role == 2)
                        <a href='{{ route('simpanan.create') }}' class="btn btn-primary">+ Tambah Data</a>
                    @else
                        <a href='{{ route('pegawai.simpanan.create') }}' class="btn btn-primary">+ Tambah Data</a>
                    @endif
                </div>
                <div class="table-responsive p-0">
                    <table class="table table-hover table-bordered align-items-center" id="myTable">
                        <thead style="font-size: 10pt">
                            <tr style="background-color: rgb(187, 246, 201)">
                                <th class="text-center">No</th>
                                <th class="text-center">No. Anggota</th>
                                <th class="text-center">Jenis Anggota</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Alamat</th>
                                <th class="text-center">Saldo</th>
                                <th class="text-center">Transaksi</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center" style="font-size: 10pt">
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

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

        @if (Auth::user()->id_role == 2)
            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable({
                        processing: true,
                        ordering: true,
                        responsive: true,
                        serverSide: true,
                        ajax: "{{ route('simpanan') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'no_anggota',
                                name: 'no_anggota'
                            },
                            {
                                data: 'jenis_anggota',
                                name: 'jenis_anggota'
                            },
                            {
                                data: 'nama',
                                name: 'nama'
                            },
                            {
                                data: 'alamat',
                                name: 'alamat'
                            },
                            {
                                data: 'total_saldo',
                                name: 'total_saldo',
                                render: function(data) {
                                    return parseInt(data).toLocaleString('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    });
                                }
                            },
                            {
                                data: null,
                                render: function(data) {
                                    return '<div class="row justify-content-center">' +
                                        '<div class="col-auto">' +
                                        '<form action="{{ route('simpanan.update', '') }}/' + data
                                        .id_simpanan +
                                        '" method="POST" enctype="multipart/form-data">' +
                                        '@csrf' +
                                        '@method('PUT')' +
                                        '<button type="button" class="btn btn-primary m-1" data-bs-toggle="modal" data-bs-target="#tarikModal' +
                                        data.id_anggota +
                                        '">Setor</button>' +
                                        '<div class="modal fade" id="tarikModal' + data.id_anggota +
                                        '" tabindex="-1">' +
                                        '<div class="modal-dialog modal-lg">' +
                                        '<div class="modal-content">' +
                                        '<div class="modal-header">' +
                                        '<h5 class="modal-title">' + data.nama + '</h5>' +
                                        '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                                        '</div>' +
                                        '<div class="modal-body text-start">' +
                                        '<input type="hidden" class="form-control" id="jenis_transaksi" name="jenis_transaksi" value="Setor" required >' +
                                        '<input type="hidden" class="form-control" id="id_simpanan" name="id_simpanan" value="' +
                                        data.id_simpanan + '" required >' +
                                        '<input type="hidden" class="form-control" id="id_anggota" name="id_anggota" value="' +
                                        data.id_anggota + '" required >' +
                                        '<input type="hidden" class="form-control" id="jenis_anggota" name="jenis_anggota" value="' +
                                        data.jenis_anggota + '" required >' +
                                        '<div class="mb-3 row">' +
                                        '<label for="jenis_simpanan" class="col-sm-2 col-form-label">Jenis Simpanan</label>' +
                                        '<div class="col-sm-12">' +
                                        '<select class="form-select cursor-pointer" aria-label="Default select example" id="jenis_simpanan" name="jenis_simpanan" >' +
                                        '<option value="" selected disabled>Pilih Jenis Simpanan</option>' +
                                        '<option value="Simpanan Wajib">Simpanan Wajib</option>' +
                                        '<option value="Simpanan Sukarela">Simpanan Sukarela</option>' +
                                        '</select>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="mb-3 row">' +
                                        '<label for="nominal" class="col-sm-2 col-form-label">Nominal</label>' +
                                        '<div class="col-sm-12">' +
                                        '<input type="text" class="form-control nominal" id="nominal" name="nominal" placeholder="Masukkan Nominal"  pattern="[0-9]*">' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="modal-footer">' +
                                        '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>' +
                                        '<button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Setor Simpanan</button>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</form>' +
                                        '<form action="{{ route('simpanan.update', '') }}/' + data
                                        .id_simpanan +
                                        '" method="POST" enctype="multipart/form-data">' +
                                        '@csrf' +
                                        '@method('PUT')' +
                                        '<button type="button" class="btn btn-info m-1" data-bs-toggle="modal" data-bs-target="#basicModal' +
                                        data.id_anggota +
                                        '">Tarik</button>' +
                                        '<div class="modal fade" id="basicModal' + data.id_anggota +
                                        '" tabindex="-1">' +
                                        '<div class="modal-dialog modal-lg">' +
                                        '<div class="modal-content">' +
                                        '<div class="modal-header">' +
                                        '<h5 class="modal-title">' + data.nama + '</h5>' +
                                        '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                                        '</div>' +
                                        '<div class="modal-body text-start">' +
                                        '<input type="hidden" class="form-control" id="jenis_transaksi" name="jenis_transaksi" value="Tarik" required >' +
                                        '<input type="hidden" class="form-control" id="id_simpanan" name="id_simpanan" value="' +
                                        data.id_simpanan + '" required >' +
                                        '<input type="hidden" class="form-control" id="id_anggota" name="id_anggota" value="' +
                                        data.id_anggota + '" required >' +
                                        '<div class="mb-3 row">' +
                                        '<label for="jenis_simpanan" class="col-sm-2 col-form-label">Jenis Simpanan</label>' +
                                        '<div class="col-sm-12">' +
                                        '<select class="form-select cursor-pointer" aria-label="Default select example" id="jenis_simpanan" name="jenis_simpanan" >' +
                                        '<option value="" disabled>Pilih Jenis Simpanan</option>' +
                                        '<option value="Simpanan Sukarela" selected>Simpanan Sukarela</option>' +
                                        '</select>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="mb-3 row">' +
                                        '<label for="nominal" class="col-sm-2 col-form-label">Nominal</label>' +
                                        '<div class="col-sm-12">' +
                                        '<input type="text" class="form-control nominal" id="nominal" name="nominal" placeholder="Masukkan Nominal"  pattern="[0-9]*">' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="modal-footer">' +
                                        '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>' +
                                        '<button type="submit" class="btn btn-info" data-bs-dismiss="modal">Tarik Simpanan</button>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</form>' +
                                        '</div>' +
                                        '</div>';
                                }
                            },
                            {
                                data: null,
                                render: function(data) {
                                    return '<div class="row justify-content-center">' +
                                        '<div class="col-auto">' +
                                        '<a href="{{ route('simpanan.show', '') }}/' + data.id_simpanan +
                                        '" style="font-size: 10pt" class="btn btn-secondary m-1 edit-btn" ' +
                                        'data-id="' + data.id_simpanan +
                                        '">Lihat</a>' +
                                        '<div class="col-auto">' +
                                        '<a href="{{ route('simpanan.destroy', '') }}/' + data.id_simpanan +
                                        '" style="font-size: 10pt" class="btn btn-danger m-1 delete-btn" ' +
                                        'data-id="' + data.id +
                                        '">Hapus</a>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>';
                                }
                            }
                        ],
                        order : [
                            [0, 'desc']
                        ],
                        rowCallback: function(row, data, index) {
                            var dt = this.api();
                            $(row).attr('data-id', data.id);
                            $('td:eq(0)', row).html(dt.page.info().start + index + 1);
                        }
                    });

                    $('.datatable-input').on('input', function() {
                        var searchText = $(this).val().toLowerCase();

                        $('.table tr').each(function() {
                            var rowData = $(this).text().toLowerCase();
                            if (rowData.indexOf(searchText) === -1) {
                                $(this).hide();
                            } else {
                                $(this).show();
                            }
                        });
                    });
                });
            </script>
        @else
            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable({
                        processing: true,
                        ordering: true,
                        responsive: true,
                        serverSide: true,
                        ajax: "{{ route('pegawai.simpanan') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'no_anggota',
                                name: 'no_anggota'
                            },
                            {
                                data: 'jenis_anggota',
                                name: 'jenis_anggota'
                            },
                            {
                                data: 'nama',
                                name: 'nama'
                            },
                            {
                                data: 'alamat',
                                name: 'alamat'
                            },
                            {
                                data: 'total_saldo',
                                name: 'total_saldo',
                                render: function(data) {
                                    return parseInt(data).toLocaleString('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    });
                                }
                            },
                            {
                                data: null,
                                render: function(data) {
                                    return '<div class="row justify-content-center">' +
                                        '<div class="col-auto">' +
                                        '<form action="{{ route('pegawai.simpanan.update', '') }}/' + data
                                        .id_simpanan +
                                        '" method="POST" enctype="multipart/form-data">' +
                                        '@csrf' +
                                        '@method('PUT')' +
                                        '<button type="button" class="btn btn-primary m-1" data-bs-toggle="modal" data-bs-target="#tarikModal' +
                                        data.id_anggota +
                                        '">Setor</button>' +
                                        '<div class="modal fade" id="tarikModal' + data.id_anggota +
                                        '" tabindex="-1">' +
                                        '<div class="modal-dialog modal-lg">' +
                                        '<div class="modal-content">' +
                                        '<div class="modal-header">' +
                                        '<h5 class="modal-title">' + data.nama + '</h5>' +
                                        '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                                        '</div>' +
                                        '<div class="modal-body text-start">' +
                                        '<input type="hidden" class="form-control" id="jenis_transaksi" name="jenis_transaksi" value="Setor" required >' +
                                        '<input type="hidden" class="form-control" id="id_simpanan" name="id_simpanan" value="' +
                                        data.id_simpanan + '" required >' +
                                        '<input type="hidden" class="form-control" id="id_anggota" name="id_anggota" value="' +
                                        data.id_anggota + '" required >' +
                                        '<input type="hidden" class="form-control" id="jenis_anggota" name="jenis_anggota" value="' +
                                        data.jenis_anggota + '" required >' +
                                        '<div class="mb-3 row">' +
                                        '<label for="jenis_simpanan" class="col-sm-2 col-form-label">Jenis Simpanan</label>' +
                                        '<div class="col-sm-12">' +
                                        '<select class="form-select cursor-pointer" aria-label="Default select example" id="jenis_simpanan" name="jenis_simpanan" >' +
                                        '<option value="" selected disabled>Pilih Jenis Simpanan</option>' +
                                        '<option value="Simpanan Wajib">Simpanan Wajib</option>' +
                                        '<option value="Simpanan Sukarela">Simpanan Sukarela</option>' +
                                        '</select>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="mb-3 row">' +
                                        '<label for="nominal" class="col-sm-2 col-form-label">Nominal</label>' +
                                        '<div class="col-sm-12">' +
                                        '<input type="text" class="form-control nominal" id="nominal" name="nominal" placeholder="Masukkan Nominal"  pattern="[0-9]*">' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="modal-footer">' +
                                        '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>' +
                                        '<button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Setor Simpanan</button>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</form>' +
                                        '<form action="{{ route('pegawai.simpanan.update', '') }}/' + data
                                        .id_simpanan +
                                        '" method="POST" enctype="multipart/form-data">' +
                                        '@csrf' +
                                        '@method('PUT')' +
                                        '<button type="button" class="btn btn-info m-1" data-bs-toggle="modal" data-bs-target="#basicModal' +
                                        data.id_anggota +
                                        '">Tarik</button>' +
                                        '<div class="modal fade" id="basicModal' + data.id_anggota +
                                        '" tabindex="-1">' +
                                        '<div class="modal-dialog modal-lg">' +
                                        '<div class="modal-content">' +
                                        '<div class="modal-header">' +
                                        '<h5 class="modal-title">' + data.nama + '</h5>' +
                                        '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                                        '</div>' +
                                        '<div class="modal-body text-start">' +
                                        '<input type="hidden" class="form-control" id="jenis_transaksi" name="jenis_transaksi" value="Tarik" required >' +
                                        '<input type="hidden" class="form-control" id="id_simpanan" name="id_simpanan" value="' +
                                        data.id_simpanan + '" required >' +
                                        '<input type="hidden" class="form-control" id="id_anggota" name="id_anggota" value="' +
                                        data.id_anggota + '" required >' +
                                        '<div class="mb-3 row">' +
                                        '<label for="jenis_simpanan" class="col-sm-2 col-form-label">Jenis Simpanan</label>' +
                                        '<div class="col-sm-12">' +
                                        '<select class="form-select cursor-pointer" aria-label="Default select example" id="jenis_simpanan" name="jenis_simpanan" >' +
                                        '<option value="" disabled>Pilih Jenis Simpanan</option>' +
                                        '<option value="Simpanan Sukarela" selected>Simpanan Sukarela</option>' +
                                        '</select>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="mb-3 row">' +
                                        '<label for="nominal" class="col-sm-2 col-form-label">Nominal</label>' +
                                        '<div class="col-sm-12">' +
                                        '<input type="text" class="form-control nominal" id="nominal" name="nominal" placeholder="Masukkan Nominal"  pattern="[0-9]*">' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="modal-footer">' +
                                        '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>' +
                                        '<button type="submit" class="btn btn-info" data-bs-dismiss="modal">Tarik Simpanan</button>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</form>' +
                                        '</div>' +
                                        '</div>';
                                }
                            },
                            {
                                data: null,
                                render: function(data) {
                                    return '<div class="row justify-content-center">' +
                                        '<div class="col-auto">' +
                                        '<a href="{{ route('pegawai.simpanan.show', '') }}/' + data
                                        .id_simpanan +
                                        '" style="font-size: 10pt" class="btn btn-secondary m-1 edit-btn" ' +
                                        'data-id="' + data.id_simpanan +
                                        '">Lihat</a>' +
                                        '</div>' +
                                        '</div>';
                                }
                            }
                        ],
                        order : [
                            [0, 'desc']
                        ],
                        rowCallback: function(row, data, index) {
                            var dt = this.api();
                            $(row).attr('data-id', data.id);
                            $('td:eq(0)', row).html(dt.page.info().start + index + 1);
                        }
                    });

                    $('.datatable-input').on('input', function() {
                        var searchText = $(this).val().toLowerCase();

                        $('.table tr').each(function() {
                            var rowData = $(this).text().toLowerCase();
                            if (rowData.indexOf(searchText) === -1) {
                                $(this).hide();
                            } else {
                                $(this).show();
                            }
                        });
                    });
                });
            </script>
        @endif
    </body>
@endsection
