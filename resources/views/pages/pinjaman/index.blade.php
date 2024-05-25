@extends('layouts.main')

@section('title', 'Pinjaman')
@section('subtitle', 'Data Pinjaman')

@section('content')

    <body class="bg-light">
        <main class="container">
            <div class="my-3 p-3 bg-body rounded shadow-sm">
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
                <div class="d-flex justify-content-between">
                    <div class="pb-2">
                        @if (Auth::user()->id_role == 2)
                            <a href='{{ route('pinjaman.create') }}' class="btn btn-primary">+ Tambah Data</a>
                        @else
                            <a href='{{ route('pegawai.pinjaman.create') }}' class="btn btn-primary">+ Tambah Data</a>
                        @endif
                    </div>
                    <div class="pb-2">
                        @if (Auth::user()->id_role == 2)
                            <a href='{{ route('pinjaman.edit') }}' class="btn btn-secondary">Kredit Macet</a>
                        @else
                            <a href='{{ route('pegawai.pinjaman.edit') }}' class="btn btn-secondary">Kredit Macet</a>
                        @endif
                    </div>
                </div>
                <div class="table-responsive p-0">
                    <table class="table table-hover table-bordered align-items-center" id="myTable">
                        <thead style="font-size: 10pt">
                            <tr style="background-color: rgb(187, 246, 201)">
                                <th class="text-center">No</th>
                                <th class="text-center">No. Pinjaman</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Besar Pinjaman</th>
                                <th class="text-center">Angsuran</th>
                                <th class="text-center">Sisa Lancar</th>
                                <th class="text-center">Status Pinjaman</th>
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
                        ajax: "{{ route('pinjaman') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'no_pinjaman',
                                name: 'no_pinjaman'
                            },
                            {
                                data: 'nama',
                                name: 'nama'
                            },
                            {
                                data: 'total_pinjaman',
                                name: 'total_pinjaman',
                                render: function(data) {
                                    return parseInt(data).toLocaleString('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    });
                                }
                            },
                            {
                                data: 'angsuran',
                                name: 'angsuran'
                            },
                            {
                                data: 'sisa_lancar_keseluruhan',
                                name: 'sisa_lancar_keseluruhan',
                                render: function(data) {
                                    return parseInt(data).toLocaleString('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    });
                                }
                            },
                            {
                                data: 'status_pinjaman',
                                name: 'status_pinjaman'
                            },
                            {
                                data: null,
                                render: function(data) {
                                    var result = '<div class="row justify-content-center">' +
                                        '<div class="col-auto">' +
                                        '<a href="{{ route('pinjaman.show', '') }}/' + data.id_pinjaman +
                                        '" style="font-size: 10pt" class="btn btn-secondary m-1 edit-btn" ' +
                                        'data-id="' + data.id_pinjaman +
                                        '">Lihat</a>';

                                    if (data.status_pinjaman == 'Belum Lunas') {
                                        result += '<a href="{{ route('pinjaman.destroy', '') }}/' + data
                                            .id_pinjaman +
                                            '" style="font-size: 10pt" class="btn btn-danger m-1 delete-btn" ' +
                                            'data-id="' + data.id_pinjaman +
                                            '">Hapus</a>';
                                    }

                                    result += '</div>' +
                                        '</div>';

                                    return result;
                                }

                            }
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
                        ajax: "{{ route('pegawai.pinjaman') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'no_pinjaman',
                                name: 'no_pinjaman'
                            },
                            {
                                data: 'nama',
                                name: 'nama'
                            },
                            {
                                data: 'total_pinjaman',
                                name: 'total_pinjaman',
                                render: function(data) {
                                    return parseInt(data).toLocaleString('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    });
                                }
                            },
                            {
                                data: 'angsuran',
                                name: 'angsuran'
                            },
                            {
                                data: 'sisa_lancar_keseluruhan',
                                name: 'sisa_lancar_keseluruhan',
                                render: function(data) {
                                    return parseInt(data).toLocaleString('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    });
                                }
                            },
                            {
                                data: 'status_pinjaman',
                                name: 'status_pinjaman'
                            },
                            {
                                data: null,
                                render: function(data) {
                                    return '<div class="row justify-content-center">' +
                                        '<div class="col-auto">' +
                                        '<a href="{{ route('pegawai.pinjaman.show', '') }}/' + data
                                        .id_pinjaman +
                                        '" style="font-size: 10pt" class="btn btn-warning m-1 edit-btn" ' +
                                        'data-id="' + data.id +
                                        '">Lihat</a>' +
                                        '</div>' +
                                        '</div>';
                                }
                            }
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
