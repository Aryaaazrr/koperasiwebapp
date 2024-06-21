@extends('layouts.main')

@section('title', 'Anggota')
@section('subtitle', 'Data Anggota')

@section('content')

    <body class="bg-light">
        <main class="container">
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <!-- TOMBOL TAMBAH DATA -->
                <div class="d-flex justify-content-between">
                    <div class="pb-2">
                        @if (Auth::user()->id_role == 1)
                            <a href='{{ route('admin.anggota.create') }}' class="btn btn-primary">+ Tambah Data</a>
                        @elseif (Auth::user()->id_role == 2)
                            <a href='{{ route('anggota.create') }}' class="btn btn-primary">+ Tambah Data</a>
                        @else
                            <a href='{{ route('pegawai.anggota.create') }}' class="btn btn-primary">+ Tambah Data</a>
                        @endif
                    </div>
                    <div class="pb-2">
                        @if (Auth::user()->id_role == 1)
                            <a href='{{ route('admin.anggota.export') }}' class="btn btn-secondary">Cetak PDF</a>
                        @elseif (Auth::user()->id_role == 2)
                            <a href='{{ route('anggota.export') }}' class="btn btn-secondary">Cetak PDF</a>
                        @else
                            <a href='{{ route('pegawai.anggota.export') }}' class="btn btn-secondary">Cetak PDF</a>
                        @endif
                    </div>
                </div>

                <div class="table-responsive p-0">
                    <table class="table table-hover table-bordered align-items-center" id="myTable">
                        <thead style="font-size: 10pt">
                            <tr style="background-color: rgb(187, 246, 201)">
                                <th class="text-center">No</th>
                                <th class="text-center">No. Anggota</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Jenis Anggota</th>
                                <th class="text-center">Alamat</th>
                                <th class="text-center">Pekerjaan</th>
                                <th class="text-center">No. Handphone</th>
                                <th class="text-center">Aksi</th>

                            </tr>
                        </thead>
                        <tbody class="text-center" style="font-size: 10pt">
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- AKHIR DATA -->
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

        @if (Auth::user()->id_role == 1)
            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable({
                        processing: true,
                        ordering: true,
                        responsive: true,
                        serverSide: true,
                        ajax: "{{ route('admin.anggota') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'no_anggota',
                                name: 'no_anggota'
                            },
                            {
                                data: 'nama',
                                name: 'nama'
                            },
                            {
                                data: 'jenis_anggota',
                                name: 'jenis_anggota'
                            },
                            {
                                data: 'alamat',
                                name: 'alamat'
                            },
                            {
                                data: 'pekerjaan',
                                name: 'pekerjaan'
                            },
                            {
                                data: 'no_telp',
                                name: 'no_telp'
                            },
                            {
                                data: null,
                                render: function(data) {
                                    return '<div class="row justify-content-center">' +
                                        '<div class="col-auto">' +
                                        '<a href="{{ route('admin.anggota.edit', '') }}/' + data
                                        .id_anggota +
                                        '" style="font-size: 10pt" class="btn btn-warning m-1 edit-btn" ' +
                                        'data-id="' + data.id +
                                        '">Edit</a>' +
                                        '<a href="{{ route('admin.anggota.destroy', '') }}/' + data
                                        .id_anggota +
                                        '" style="font-size: 10pt" class="btn btn-danger m-1 delete-btn" ' +
                                        'data-id="' + data.id +
                                        '">Hapus</a>' +
                                        '</div>' +
                                        '</div>';
                                }
                            }
                        ],
                        order: [
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
        @elseif (Auth::user()->id_role == 2)
            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable({
                        processing: true,
                        ordering: true,
                        responsive: true,
                        serverSide: true,
                        ajax: "{{ route('anggota') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'no_anggota',
                                name: 'no_anggota'
                            },
                            {
                                data: 'nama',
                                name: 'nama'
                            },
                            {
                                data: 'jenis_anggota',
                                name: 'jenis_anggota'
                            },
                            {
                                data: 'alamat',
                                name: 'alamat'
                            },
                            {
                                data: 'pekerjaan',
                                name: 'pekerjaan'
                            },
                            {
                                data: 'no_telp',
                                name: 'no_telp'
                            },
                            {
                                data: null,
                                render: function(data) {
                                    return '<div class="row justify-content-center">' +
                                        '<div class="col-auto">' +
                                        '<a href="{{ route('anggota.edit', '') }}/' + data.id_anggota +
                                        '" style="font-size: 10pt" class="btn btn-warning m-1 edit-btn" ' +
                                        'data-id="' + data.id +
                                        '">Edit</a>' +
                                        '<div class="col-auto">' +
                                        '<a href="{{ route('anggota.destroy', '') }}/' + data.id_anggota +
                                        '" style="font-size: 10pt" class="btn btn-danger m-1 delete-btn" ' +
                                        'data-id="' + data.id +
                                        '">Hapus</a>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>';
                                }
                            }
                        ],
                        order: [
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
                        ajax: "{{ route('pegawai.anggota') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            // {
                            //     data: 'nik',
                            //     name: 'nik'
                            // },
                            {
                                data: 'no_anggota',
                                name: 'no_anggota'
                            },
                            {
                                data: 'nama',
                                name: 'nama'
                            },
                            {
                                data: 'jenis_anggota',
                                name: 'jenis_anggota'
                            },
                            {
                                data: 'tanggal_masuk',
                                name: 'tanggal_masuk'
                            },
                            {
                                data: 'alamat',
                                name: 'alamat'
                            },
                            {
                                data: 'pekerjaan',
                                name: 'pekerjaan'
                            },
                            {
                                data: 'no_telp',
                                name: 'no_telp'
                            },
                            {
                                data: null,
                                render: function(data) {
                                    // var simpananLink =
                                    //     '<a href="{{ route('pegawai.simpanan.create') }}" style="font-size: 10pt" class="btn btn-primary m-1 edit-btn" ' +
                                    //     'data-id="' + data.id +
                                    //     '">Simpanan</a>';

                                    // var pinjamanLink =
                                    //     '<a href="{{ route('pegawai.pinjaman.create') }}" style="font-size: 10pt" class="btn btn-info m-1 delete-btn" ' +
                                    //     'data-id="' + data.id +
                                    //     '">Pinjaman</a>';

                                    var simpananLink =
                                        '<a href="{{ route('pegawai.simpanan.create') }}" style="font-size: 10pt" class="btn btn-primary m-1 edit-btn" ' +
                                        'data-id="' + data.id +
                                        '">Simpanan</a>';

                                    var pinjamanLink =
                                        '<a href="{{ route('pegawai.pinjaman.create') }}" style="font-size: 10pt" class="btn btn-info m-1 delete-btn" ' +
                                        'data-id="' + data.id +
                                        '">Pinjaman</a>';

                                    if (data.has_simpanan) {
                                        simpananLink =
                                            '<a href="{{ route('pegawai.simpanan.show', '') }}/' +
                                            data.id_anggota +
                                            '" style="font-size: 10pt" class="btn btn-primary m-1 edit-btn" ' +
                                            'data-id="' + data.id +
                                            '">Simpanan</a>';

                                    }

                                    if (data.has_pinjaman) {
                                        pinjamanLink =
                                            '<a href="{{ route('pegawai.pinjaman.show', '') }}/' +
                                            data.id_anggota +
                                            '" style="font-size: 10pt" class="btn btn-info m-1 delete-btn" ' +
                                            'data-id="' + data.id +
                                            '">Pinjaman</a>';

                                    }

                                    return '<div class="row justify-content-center">' +
                                        '<div class="col-auto">' + simpananLink + '</div>' +
                                        '<div class="col-auto">' + pinjamanLink + '</div>' +
                                        '</div>';
                                }
                            },
                            {
                                data: null,
                                render: function(data) {
                                    return '<div class="row justify-content-center">' +
                                        '<div class="col-auto">' +
                                        '<a href="{{ route('pegawai.anggota.edit', '') }}/' + data
                                        .id_anggota +
                                        '" style="font-size: 10pt" class="btn btn-secondary m-1 edit-btn" ' +
                                        'data-id="' + data.id +
                                        '">Lihat</a>' +
                                        '</div>' +
                                        '</div>';
                                }
                            }
                        ],
                        order: [
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
