@extends('layouts.main')

@section('title', 'Laporan')
@section('subtitle', 'Data Laporan')

@section('content')

    <body class="bg-light">
        <main class="container">
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <!-- TOMBOL TAMBAH DATA -->
                <div class="d-flex justify-content-between">
                    <div class="pb-2">
                        @if (Auth::user()->id_role == 2)
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#basicModal">+
                                Tambah Data</button>
                        @else
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#basicModal">+ Tambah Data</button>
                        @endif
                    </div>
                    <div class="pb-2">
                        @if (Auth::user()->id_role == 2)
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#exportModal">Cetak SHU</button>
                        @else
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#exportModal">Cetak SHU</button>
                        @endif
                    </div>
                </div>

                <div class="table-responsive p-0">
                    <table class="table table-hover table-bordered align-items-center" id="myTable">
                        <thead style="font-size: 10pt">
                            <tr style="background-color: rgb(187, 246, 201)">
                                <th class="text-center">No</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center">Klasifikasi</th>
                                <th class="text-center">Nominal</th>
                                @if (Auth::user()->id_role == 2)
                                <th class="text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="text-center" style="font-size: 10pt">
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="basicModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        @if (Auth::user()->id_role == 2)
                            <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                            @else
                                <form action="{{ route('pegawai.laporan.store') }}" method="POST"
                                    enctype="multipart/form-data">
                        @endif
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Beban Operasional</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            {{-- <div class="mb-3 row">
                                    <label for="klasifikasi" class="col-sm-4 col-form-label">Klasifikasi</label>
                                    <div class="col-sm-12">
                                        <select class="form-select cursor-pointer" aria-label="Default select example"
                                            id="klasifikasi" name="klasifikasi" value="{{ old('klasifikasi') }}" required>
                                            <option value="" selected disabled>Pilih Klasifikasi</option>
                                            <option value="Beban Operasional">Beban Operasional</option>
                                        </select>
                                    </div>
                                </div> --}}
                            <div class="mb-3 row">
                                <label for="jumlah_uang" class="col-sm-4 col-form-label">Nominal</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="jumlah_uang" name="jumlah_uang"
                                        placeholder="Masukkan Nominal" value="" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="keterangan" class="col-sm-4 col-form-label">Keterangan</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="keterangan" name="keterangan"
                                        placeholder="Masukkan Keterangan" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="exportModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        @if (Auth::user()->id_role == 2)
                            <form action="{{ route('laporan.export') }}" method="GET" enctype="multipart/form-data">
                            @else
                                <form action="{{ route('pegawai.laporan.export') }}" method="GET"
                                    enctype="multipart/form-data">
                        @endif
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Pilih Rentang Waktu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            <div class="col d-flex justify-content-start align-items-center">
                                <input type="date" name="start_date" class="form-control h-50 me-2 mb-2" required>
                                <input type="date" name="end_date" class="form-control h-50 me-2 mb-2" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Cetak</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        @if (Auth::user()->id_role == 2)
                            <form action="{{ route('laporan.update') }}" method="POST" enctype="multipart/form-data">
                            @else
                                <form action="{{ route('pegawai.laporan.update') }}" method="POST"
                                    enctype="multipart/form-data">
                        @endif
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Sisa Hasil Usaha</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            <input type="hidden" class="form-control" id="id_laporan" name="id_laporan" required>
                            <div class="mb-3 row">
                                <label for="jumlah_uang" class="col-sm-4 col-form-label">Nominal</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="jumlah_uang" name="jumlah_uang"
                                        placeholder="Masukkan Nominal" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="keterangan" class="col-sm-4 col-form-label">Keterangan</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="keterangan" name="keterangan"
                                        placeholder="Masukkan Keterangan" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            @if (Auth::user()->id_role == 2)
                            <button type="submit" class="btn btn-success">Simpan</button>
                        @endif
                        </div>
                        </form>
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
                        ordering: true,
                        responsive: true,
                        serverSide: true,
                        ajax: "{{ route('laporan') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'created_at',
                                name: 'created_at'
                            },
                            {
                                data: 'keterangan',
                                name: 'keterangan'
                            },
                            {
                                data: 'klasifikasi',
                                name: 'klasifikasi'
                            },
                            {
                                data: 'jumlah_uang',
                                name: 'jumlah_uang',
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
                                    var roundValueJumlahUang = data.jumlah_uang != null ? Math.round(
                                        data.jumlah_uang) : 0;

                                    return '<div class="row justify-content-center">' +
                                        '<div class="col-auto">' +
                                        '<button type="button" class="btn btn-warning mt-3 edit-btn" data-bs-toggle="modal" style="font-size: 10pt" data-bs-target="#editModal" data-id="' +
                                        data.id + '" data-keterangan="' + data.keterangan +
                                        '" data-jumlah="' + roundValueJumlahUang + '">Edit</button>' +
                                        '<a href="{{ route('laporan.destroy', '') }}/' + data
                                        .id +
                                        '" style="font-size: 10pt" class="btn btn-danger m-1 delete-btn" ' +
                                        'data-id="' + data.id +
                                        '">Hapus</a>' +
                                        '</div>' +
                                        '</div>';
                                }
                            },
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

                    $('#editModal').on('show.bs.modal', function(event) {
                        var button = $(event.relatedTarget);
                        var id_laporan = button.data('id');
                        var keterangan = button.data('keterangan');
                        var jumlah_uang = button.data('jumlah');
                        var modal = $(this);

                        modal.find('.modal-body #id_laporan').val(id_laporan);
                        modal.find('.modal-body #keterangan').val(keterangan);
                        modal.find('.modal-body #jumlah_uang').val(jumlah_uang);
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
                        ajax: "{{ route('pegawai.laporan') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'created_at',
                                name: 'created_at'
                            },
                            {
                                data: 'keterangan',
                                name: 'keterangan'
                            },
                            {
                                data: 'klasifikasi',
                                name: 'klasifikasi'
                            },
                            {
                                data: 'jumlah_uang',
                                name: 'jumlah_uang',
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
                            //         var roundValueJumlahUang = data.jumlah_uang != null ? Math.round(
                            //             data.jumlah_uang) : 0;

                            //         return '<div class="row justify-content-center">' +
                            //             '<div class="col-auto">' +
                            //             '<button type="button" class="btn btn-warning mt-3 edit-btn" data-bs-toggle="modal" style="font-size: 10pt" data-bs-target="#editModal" data-id="' +
                            //             data.id + '" data-keterangan="' + data.keterangan +
                            //             '" data-jumlah="' + roundValueJumlahUang + '">Edit</button>' +
                            //             '</div>' +
                            //             '</div>';
                            //     }
                            // },
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

                    $('#editModal').on('show.bs.modal', function(event) {
                        var button = $(event.relatedTarget);
                        var id_laporan = button.data('id');
                        var keterangan = button.data('keterangan');
                        var jumlah_uang = button.data('jumlah');
                        var modal = $(this);

                        modal.find('.modal-body #id_laporan').val(id_laporan);
                        modal.find('.modal-body #keterangan').val(keterangan);
                        modal.find('.modal-body #jumlah_uang').val(jumlah_uang);
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
