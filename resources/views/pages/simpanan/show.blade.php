@extends('layouts.main')

@section('title', 'Simpanan')
@section('subtitle', 'Detail Simpanan')

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
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <h5 class="card-title ">Detail Simpanan</h5>
                            <div class="row d-flex justify-content-between">
                                <div class="col-md-8">
                                    <p class="mt-4">
                                        No Anggota : {{ $simpanan->anggota->no_anggota }}
                                    </p>
                                    <p class="mt-4">
                                        Nama : {{ $simpanan->anggota->nama }}
                                    </p>
                                    <p class="mt-4">
                                        Alamat : {{ $simpanan->anggota->alamat }}
                                    </p>
                                    <p class="mt-4">
                                        Tanggal Masuk : {{ $simpanan->anggota->tanggal_masuk }}
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mt-4">
                                        Saldo : Rp {{ number_format($simpanan->total_saldo, 2, ',', '.') }}
                                    </p>
                                    <p class="mt-4">
                                        Simpanan Pokok : Rp {{ number_format($total_simpanan_pokok, 2, ',', '.') }}
                                    </p>
                                    <p class="mt-4">
                                        Simpanan Wajib : Rp {{ number_format($total_simpanan_wajib, 2, ',', '.') }}
                                    </p>
                                    <p class="mt-4">
                                        Simpanan Sukarela : Rp
                                        {{ number_format($total_simpanan_sukarela, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive p-0">
                    <table class="table table-hover table-bordered align-items-center" id="myTable">
                        <thead style="font-size: 10pt">
                            <tr style="background-color: rgb(187, 246, 201)">
                                <th class="text-center">No</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Jenis Transaksi</th>
                                <th class="text-center">Simpanan Pokok</th>
                                <th class="text-center">Simpanan Wajib</th>
                                <th class="text-center">Simpanan Sukarela</th>
                                <th class="text-center">Saldo</th>
                                @if (Auth::user()->id_role == 2)
                                <th class="text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="text-center" style="font-size: 10pt">
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="pb-2 mt-4">
                        @if (Auth::user()->id_role == 2)
                            <a href="{{ route('simpanan') }}" class="btn btn-secondary">Kembali</a>
                        @else
                            <a href="{{ route('pegawai.simpanan') }}" class="btn btn-secondary">Kembali</a>
                        @endif
                    </div>
                    <div class="pb-2 mt-4">
                        @if (Auth::user()->id_role == 2)
                            <a href="{{ route('simpanan.export', ['id' => $simpanan->id_simpanan]) }}"
                                class="btn btn-info">Cetak Laporan</a>
                        @else
                            <a href="{{ route('pegawai.simpanan.export', ['id' => $simpanan->id_simpanan]) }}"
                                class="btn btn-info">Cetak Laporan</a>
                        @endif
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
                    $('#myTable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{ route('simpanan.show', ['id' => ':id']) }}'.replace(':id', window.location
                                .href.split('/').pop()),
                            method: 'GET',
                            dataSrc: 'data'
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'created_at',
                                name: 'created_at'
                            },
                            {
                                data: 'jenis_transaksi',
                                name: 'jenis_transaksi'
                            },
                            {
                                data: 'simpanan_pokok',
                                name: 'simpanan_pokok',
                                render: function(data) {
                                    return data !== null ? parseInt(data).toLocaleString('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    }) : '-';
                                }
                            },
                            {
                                data: 'simpanan_wajib',
                                name: 'simpanan_wajib',
                                render: function(data) {
                                    return data !== null ? parseInt(data).toLocaleString('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    }) : '-';
                                }
                            },
                            {
                                data: 'simpanan_sukarela',
                                name: 'simpanan_sukarela',
                                render: function(data) {
                                    return data !== null ? parseInt(data).toLocaleString('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    }) : '-';
                                }
                            },
                            {
                                data: 'subtotal_saldo',
                                name: 'subtotal_saldo',
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
                                    var formattedSubtotal = new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    }).format(data.subtotal_saldo);

                                    var disableSimpananPokok = data.simpanan_pokok != null ? '' :
                                        'readonly';
                                    var roundValueSimpananPokok = data.simpanan_pokok != null ? Math.round(
                                        data.simpanan_pokok) : 0;

                                    var disableSimpananWajib = data.simpanan_wajib != null ? '' :
                                        'readonly';
                                    var roundValueSimpananWajib = data.simpanan_wajib != null ? Math.round(
                                        data.simpanan_wajib) : 0;

                                    var disableSimpananSukarela = data.simpanan_sukarela != null ?
                                        '' : 'readonly';
                                    var roundValueSimpananSukarela = data.simpanan_sukarela != null ? Math
                                        .round(data.simpanan_sukarela) : 0;

                                    var jenis = data.jenis_transaksi == 'Setor' ? 'Tarik' : 'Setor';

                                    return '<div class="row justify-content-center">' +
                                        '<div class="col-auto">' +
                                        '<form action="{{ route('simpanan.update', '') }}/' + data
                                        .id_simpanan +
                                        '" method="POST" enctype="multipart/form-data">' +
                                        '@csrf' +
                                        '@method('PUT')' +
                                        '<button type="button" class="btn btn-warning m-1" data-bs-toggle="modal" data-bs-target="#tarikModal' +
                                        Math.round(data.subtotal_saldo) +
                                        '">Edit</button>' +
                                        '<div class="modal fade" id="tarikModal' + Math.round(data
                                            .subtotal_saldo) +
                                        '" tabindex="-1">' +
                                        '<div class="modal-dialog modal-lg">' +
                                        '<div class="modal-content">' +
                                        '<div class="modal-header">' +
                                        '<h5 class="modal-title">Edit Detail Simpanan</h5>' +
                                        '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                                        '</div>' +
                                        '<div class="modal-body text-start">' +
                                        '<input type="hidden" class="form-control" id="jenis_transaksi" name="jenis_lama" value="' +
                                        data.jenis_transaksi + '" required >' +
                                        '<input type="hidden" class="form-control" id="jenis_transaksi" name="subtotal_saldo_saat_ini" value="' +
                                        data.subtotal_saldo + '" required >' +
                                        '<input type="hidden" class="form-control" id="id_simpanan" name="id_simpanan" value="' +
                                        data.id_simpanan + '" required >' +
                                        '<input type="hidden" class="form-control" id="jenis_anggota" name="jenis_anggota" value="' +
                                        data.jenis_anggota + '" required >' +
                                        '<input type="hidden" class="form-control" id="nominal_lama" name="nominal_lama" value="' +
                                        data.simpanan_sukarela + '" required >' +
                                        '<input type="hidden" class="form-control" id="id_anggota" name="id_anggota" value="update_detail" required >' +
                                        '<div class="mb-3 row">' +
                                        '<label for="jenis_transaksi" class="col-sm-2 col-form-label">Jenis Transaksi</label>' +
                                        '<div class="col-sm-12">' +
                                        '<select class="form-select cursor-pointer" aria-label="Default select example" id="jenis_transaksi" name="jenis_transaksi" >' +
                                        '<option value="" disabled>Pilih Jenis Transaksi</option>' +
                                        '<option value="' + data.jenis_transaksi + '" selected>' + data
                                        .jenis_transaksi +
                                        '</option>' +
                                        '<option value="' + jenis + '">' + jenis + '</option>' +
                                        '</select>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="mb-3 row">' +
                                        '<label for="nominal_simpanan_pokok" class="col-sm-2 col-form-label">Nominal Simpanan Pokok</label>' +
                                        '<div class="col-sm-12">' +
                                        '<input type="text" class="form-control nominal" id="nominal_simpanan_pokok" name="nominal_simpanan_pokok" value="' +
                                        roundValueSimpananPokok + '"  pattern="[0-9]*" ' +
                                        disableSimpananPokok + '>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="mb-3 row">' +
                                        '<label for="nominal_simpanan_wajib" class="col-sm-2 col-form-label">Nominal Simpanan Wajib</label>' +
                                        '<div class="col-sm-12">' +
                                        '<input type="text" class="form-control nominal" id="nominal_simpanan_wajib" name="nominal_simpanan_wajib" value="' +
                                        roundValueSimpananWajib + '"  pattern="[0-9]*" ' +
                                        disableSimpananWajib + '>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="mb-3 row">' +
                                        '<label for="nominal_simpanan_sukarela" class="col-sm-2 col-form-label">Nominal Simpanan Sukarela</label>' +
                                        '<div class="col-sm-12">' +
                                        '<input type="text" class="form-control nominal" id="nominal_simpanan_sukarela" name="nominal_simpanan_sukarela" value="' +
                                        roundValueSimpananSukarela + '" pattern="[0-9]*" ' +
                                        disableSimpananSukarela + '>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="modal-footer">' +
                                        '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>' +
                                        '<button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Simpan</button>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</form>' +
                                        '<a href="{{ route('simpanan.destroy.detail', '') }}/' + data.id +
                                        '" style="font-size: 10pt" class="btn btn-danger m-1 delete-btn" ' +
                                        'data-id="' + data.id +
                                        '">Hapus</a>' +
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
        @else
            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{ route('pegawai.simpanan.show', ['id' => ':id']) }}'.replace(':id', window
                                .location
                                .href.split('/').pop()),
                            method: 'GET',
                            dataSrc: 'data'
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'created_at',
                                name: 'created_at'
                            },
                            {
                                data: 'jenis_transaksi',
                                name: 'jenis_transaksi'
                            },
                            {
                                data: 'simpanan_pokok',
                                name: 'simpanan_pokok',
                                render: function(data) {
                                    return data !== null ? parseInt(data).toLocaleString('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    }) : '-';
                                }
                            },
                            {
                                data: 'simpanan_wajib',
                                name: 'simpanan_wajib',
                                render: function(data) {
                                    return data !== null ? parseInt(data).toLocaleString('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    }) : '-';
                                }
                            },
                            {
                                data: 'simpanan_sukarela',
                                name: 'simpanan_sukarela',
                                render: function(data) {
                                    return data !== null ? parseInt(data).toLocaleString('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    }) : '-';
                                }
                            },
                            {
                                data: 'subtotal_saldo',
                                name: 'subtotal_saldo',
                                render: function(data) {
                                    return parseInt(data).toLocaleString('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    });
                                }
                            },
                            // {
                            //     data: null,
                            //     render: function(data) {
                            //         var formattedSubtotal = new Intl.NumberFormat('id-ID', {
                            //             style: 'currency',
                            //             currency: 'IDR'
                            //         }).format(data.subtotal_saldo);

                            //         var disableSimpananPokok = data.simpanan_pokok != null ? '' :
                            //             'readonly';
                            //         var roundValueSimpananPokok = data.simpanan_pokok != null ? Math.round(
                            //             data.simpanan_pokok) : 0;

                            //         var disableSimpananWajib = data.simpanan_wajib != null ? '' :
                            //             'readonly';
                            //         var roundValueSimpananWajib = data.simpanan_wajib != null ? Math.round(
                            //             data.simpanan_wajib) : 0;

                            //         var disableSimpananSukarela = data.simpanan_sukarela != null ?
                            //             '' : 'readonly';
                            //         var roundValueSimpananSukarela = data.simpanan_sukarela != null ? Math
                            //             .round(data.simpanan_sukarela) : 0;

                            //         var jenis = data.jenis_transaksi == 'Setor' ? 'Tarik' : 'Setor';

                            //         return '<div class="row justify-content-center">' +
                            //             '<div class="col-auto">' +
                            //             '<form action="{{ route('pegawai.simpanan.update', '') }}/' + data
                            //             .id_simpanan +
                            //             '" method="POST" enctype="multipart/form-data">' +
                            //             '@csrf' +
                            //             '@method('PUT')' +
                            //             '<button type="button" class="btn btn-warning m-1" data-bs-toggle="modal" data-bs-target="#tarikModal' +
                            //             Math.round(data.subtotal_saldo) +
                            //             '">Edit</button>' +
                            //             '<div class="modal fade" id="tarikModal' + Math.round(data
                            //                 .subtotal_saldo) +
                            //             '" tabindex="-1">' +
                            //             '<div class="modal-dialog modal-lg">' +
                            //             '<div class="modal-content">' +
                            //             '<div class="modal-header">' +
                            //             '<h5 class="modal-title">Edit Detail Simpanan</h5>' +
                            //             '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                            //             '</div>' +
                            //             '<div class="modal-body text-start">' +
                            //             '<input type="hidden" class="form-control" id="jenis_transaksi" name="jenis_lama" value="' +
                            //             data.jenis_transaksi + '" required >' +
                            //             '<input type="hidden" class="form-control" id="jenis_transaksi" name="subtotal_saldo_saat_ini" value="' +
                            //             data.subtotal_saldo + '" required >' +
                            //             '<input type="hidden" class="form-control" id="id_simpanan" name="id_simpanan" value="' +
                            //             data.id_simpanan + '" required >' +
                            //             '<input type="hidden" class="form-control" id="jenis_anggota" name="jenis_anggota" value="' +
                            //             data.jenis_anggota + '" required >' +
                            //             '<input type="hidden" class="form-control" id="nominal_lama" name="nominal_lama" value="' +
                            //             data.simpanan_sukarela + '" required >' +
                            //             '<input type="hidden" class="form-control" id="id_anggota" name="id_anggota" value="update_detail" required >' +
                            //             '<div class="mb-3 row">' +
                            //             '<label for="jenis_transaksi" class="col-sm-2 col-form-label">Jenis Transaksi</label>' +
                            //             '<div class="col-sm-12">' +
                            //             '<select class="form-select cursor-pointer" aria-label="Default select example" id="jenis_transaksi" name="jenis_transaksi" >' +
                            //             '<option value="" disabled>Pilih Jenis Transaksi</option>' +
                            //             '<option value="' + data.jenis_transaksi + '" selected>' + data
                            //             .jenis_transaksi +
                            //             '</option>' +
                            //             '<option value="' + jenis + '">' + jenis + '</option>' +
                            //             '</select>' +
                            //             '</div>' +
                            //             '</div>' +
                            //             '<div class="mb-3 row">' +
                            //             '<label for="nominal_simpanan_pokok" class="col-sm-2 col-form-label">Nominal Simpanan Pokok</label>' +
                            //             '<div class="col-sm-12">' +
                            //             '<input type="text" class="form-control nominal" id="nominal_simpanan_pokok" name="nominal_simpanan_pokok" value="' +
                            //             roundValueSimpananPokok + '"  pattern="[0-9]*" ' +
                            //             disableSimpananPokok + '>' +
                            //             '</div>' +
                            //             '</div>' +
                            //             '<div class="mb-3 row">' +
                            //             '<label for="nominal_simpanan_wajib" class="col-sm-2 col-form-label">Nominal Simpanan Wajib</label>' +
                            //             '<div class="col-sm-12">' +
                            //             '<input type="text" class="form-control nominal" id="nominal_simpanan_wajib" name="nominal_simpanan_wajib" value="' +
                            //             roundValueSimpananWajib + '"  pattern="[0-9]*" ' +
                            //             disableSimpananWajib + '>' +
                            //             '</div>' +
                            //             '</div>' +
                            //             '<div class="mb-3 row">' +
                            //             '<label for="nominal_simpanan_sukarela" class="col-sm-2 col-form-label">Nominal Simpanan Sukarela</label>' +
                            //             '<div class="col-sm-12">' +
                            //             '<input type="text" class="form-control nominal" id="nominal_simpanan_sukarela" name="nominal_simpanan_sukarela" value="' +
                            //             roundValueSimpananSukarela + '" pattern="[0-9]*" ' +
                            //             disableSimpananSukarela + '>' +
                            //             '</div>' +
                            //             '</div>' +
                            //             '</div>' +
                            //             '<div class="modal-footer">' +
                            //             '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>' +
                            //             '<button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Simpan</button>' +
                            //             '</div>' +
                            //             '</div>' +
                            //             '</div>' +
                            //             '</div>' +
                            //             '</form>' +
                            //             '</div>' +
                            //             '</div>';

                            //     }

                            // }
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
