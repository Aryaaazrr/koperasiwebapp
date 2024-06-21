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
                <div class="p-0">
                    <div class="d-flex justify-content-between">
                        <div class="p-0">
                            @if (Auth::user()->id_role == 2)
                                <a href='{{ route('pinjaman.create') }}'
                                    class="btn
                            btn-primary">+ Tambah Data</a>
                            @else
                                <a href='{{ route('pegawai.pinjaman.create') }}' class="btn btn-primary">+ Tambah Data</a>
                            @endif
                        </div>
                        <div class="p-0">
                            @if (Auth::user()->id_role == 2)
                                <a href='{{ route('pinjaman.edit') }}' class="btn btn-danger">Kolektibilitas Kredit</a>
                            @else
                                <a href='{{ route('pegawai.pinjaman.edit') }}' class="btn btn-danger">Kolektibilitas
                                    Kredit</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>

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
                <div class="pb-2">
                    <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="belum-tab" data-bs-toggle="tab" data-bs-target="#belum"
                                type="button" role="tab" aria-controls="belum" aria-selected="true">Belum
                                Lunas</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="lunas-tab" data-bs-toggle="tab" data-bs-target="#lunas"
                                type="button" role="tab" aria-controls="lunas" aria-selected="true">Lunas</button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="belum" role="tabpanel" aria-labelledby="belum-tab" tabindex="0">
                        <div class="table-responsive p-0">
                            <table class="table table-hover w-100 table-bordered align-items-center" id="belumTable">
                                <thead style="font-size: 10pt">
                                    <tr style="background-color: rgb(187, 246, 201)">
                                        <th class="text-center">No</th>
                                        <th class="text-center">No. Pinjaman</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Besar Pinjaman</th>
                                        <th class="text-center">Lama Angsuran/Bulan</th>
                                        <th class="text-center">Total Kewajiban</th>
                                        <th class="text-center">Status Pinjaman</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center" style="font-size: 10pt">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="lunas" role="tabpanel" aria-labelledby="lunas-tab" tabindex="0">
                        <div class="table-responsive p-0">
                            <table class="table table-hover w-100 table-bordered align-items-center" id="lunasTable">
                                <thead style="font-size: 10pt">
                                    <tr style="background-color: rgb(187, 246, 201)">
                                        <th class="text-center">No</th>
                                        <th class="text-center">No. Pinjaman</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Besar Pinjaman</th>
                                        <th class="text-center">Lama Angsuran/Bulan</th>
                                        <th class="text-center">Total Kewajiban</th>
                                        <th class="text-center">Status Pinjaman</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center" style="font-size: 10pt">
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                    $('#belumTable').DataTable({
                        processing: true,
                        ordering: true,
                        responsive: true,
                        serverSide: true,
                        ajax: "{{ route('pinjaman.belum.lunas') }}",
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
                                    result += '</div>' +
                                        '</div>';
                                    return result;
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

                    $('#lunasTable').DataTable({
                        processing: true,
                        ordering: true,
                        responsive: true,
                        serverSide: true,
                        ajax: "{{ route('pinjaman.lunas') }}",
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
                                    result += '</div>' +
                                        '</div>';
                                    return result;
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
