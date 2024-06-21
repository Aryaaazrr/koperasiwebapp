@extends('layouts.main')

@section('title', 'Pinjaman')
@section('subtitle', 'Kolektibilitas Kredit')

@section('content')

    <body class="bg-light">
        <main class="container">
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="pb-2">
                    <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="diragukan-tab" data-bs-toggle="tab"
                                data-bs-target="#diragukan" type="button" role="tab" aria-controls="diragukan"
                                aria-selected="true">Diragukan</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="macet-tab" data-bs-toggle="tab" data-bs-target="#macet"
                                type="button" role="tab" aria-controls="macet" aria-selected="true">Macet</button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="diragukan" role="tabpanel" aria-labelledby="diragukan-tab"
                        tabindex="0">
                        <div class="table-responsive p-0">
                            <table class="table table-hover w-100 table-bordered align-items-center" id="diragukanTable">
                                <thead style="font-size: 10pt">
                                    <tr style="background-color: rgb(187, 246, 201)">
                                        <th class="text-center">No</th>
                                        <th class="text-center">No. Pinjaman</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Besar Pinjaman</th>
                                        <th class="text-center">Lama Angsuran/Bulan</th>
                                        <th class="text-center">Total Kewajiban</th>
                                        <th class="text-center">Status Pinjaman</th>
                                        <th class="text-center">Jumlah Keterlambatan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center" style="font-size: 10pt">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="macet" role="tabpanel" aria-labelledby="macet-tab" tabindex="0">
                        <div class="table-responsive p-0">
                            <table class="table table-hover w-100 table-bordered align-items-center" id="macetTable">
                                <thead style="font-size: 10pt">
                                    <tr style="background-color: rgb(187, 246, 201)">
                                        <th class="text-center">No</th>
                                        <th class="text-center">No. Pinjaman</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Besar Pinjaman</th>
                                        <th class="text-center">Lama Angsuran/Bulan</th>
                                        <th class="text-center">Total Kewajiban</th>
                                        <th class="text-center">Status Pinjaman</th>
                                        <th class="text-center">Jumlah Keterlambatan</th>
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
                    $('#diragukanTable').DataTable({
                        processing: true,
                        ordering: true,
                        responsive: true,
                        serverSide: true,
                        ajax: "{{ route('pinjaman.diragukan') }}",
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
                                data: 'jumlah_terlambat',
                                name: 'jumlah_terlambat'
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

                    $('#macetTable').DataTable({
                        processing: true,
                        ordering: true,
                        responsive: true,
                        serverSide: true,
                        ajax: "{{ route('pinjaman.macet') }}",
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
                                data: 'jumlah_terlambat',
                                name: 'jumlah_terlambat'
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
                    $('#diragukanTable').DataTable({
                        processing: true,
                        ordering: true,
                        responsive: true,
                        serverSide: true,
                        ajax: "{{ route('pegawai.pinjaman.diragukan') }}",
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
                                data: 'jumlah_terlambat',
                                name: 'jumlah_terlambat'
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

                    $('#macetTable').DataTable({
                        processing: true,
                        ordering: true,
                        responsive: true,
                        serverSide: true,
                        ajax: "{{ route('pegawai.pinjaman.macet') }}",
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
                                data: 'jumlah_terlambat',
                                name: 'jumlah_terlambat'
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
        @endif
    </body>
@endsection
