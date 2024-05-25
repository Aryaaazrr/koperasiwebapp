@extends('layouts.main')

@section('title', 'Pinjaman')
@section('subtitle', 'Detail Pinjaman')

@section('content')

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
                        <h5 class="card-title text-primary">Detail Pinjaman</h5>
                        <div class="row d-flex justify-content-between">
                            <div class="col-md-8">
                                <p class="mt-4">
                                    No Anggota : {{ $pinjaman->anggota->no_anggota }}
                                </p>
                                <p class="mt-4">
                                    Nama : {{ $pinjaman->anggota->nama }}
                                </p>
                                <p class="mt-4">
                                    Besar Pinjaman : Rp {{ number_format($pinjaman->total_pinjaman, 2, ',', '.') }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="mt-4">
                                    No. Pinjaman : {{ $pinjaman->no_pinjaman }}
                                </p>
                                <p class="mt-4">
                                    Tanggal Realisasi : {{ $pinjaman->tanggal_realisasi }}
                                </p>
                                <p class="mt-4">
                                    Angsuran : Rp {{ number_format($angsuran->subtotal_angsuran, 2, ',', '.') }} x
                                    {{ $pinjaman->angsuran }}
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
                            <th class="text-center w-8">Angsuran Ke-</th>
                            <th class="text-center">Tanggal Jatuh Tempo</th>
                            <th class="text-center">Angsuran Pokok</th>
                            <th class="text-center">Bunga</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="text-center" style="font-size: 10pt">
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between">
                <div class="pb-2 mt-4">
                    @if (Auth::user()->id_role == 2)
                        <a href='{{ route('pinjaman') }}' class="btn btn-secondary">Kembali</a>
                    @else
                        <a href='{{ route('pegawai.pinjaman') }}' class="btn btn-secondary">Kembali</a>
                    @endif
                </div>
                <div class="pb-2 mt-4">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#basicModal">Bayar
                        Pinjaman</button>
                    @if (Auth::user()->id_role == 2)
                        <a href="{{ route('pinjaman.export', ['id' => $angsuran->id_pinjaman]) }}"
                            class="btn btn-info">Cetak
                            Laporan</a>
                    @else
                        <a href="{{ route('pegawai.pinjaman.export', ['id' => $angsuran->id_pinjaman]) }}"
                            class="btn btn-info">Cetak
                            Laporan</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="modal fade" id="basicModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    @if (Auth::user()->id_role == 2)
                        <form action="{{ route('pinjaman.update', $pinjaman->id_pinjaman) }}" method="POST"
                            enctype="multipart/form-data">
                        @else
                            <form action="{{ route('pegawai.pinjaman.update', $pinjaman->id_pinjaman) }}" method="POST"
                                enctype="multipart/form-data">
                    @endif
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Bayar Pinjaman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start">
                        <input type="hidden" class="form-control" id="id_anggota" name="id_anggota"
                            value="{{ $pinjaman->anggota->id_anggota }}" required>
                        <div class="mb-3 row">
                            <label for="angsuran" class="col-sm-4 col-form-label">Angusan Pinjaman</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="angsuran" name="angsuran"
                                    placeholder="Masukkan Angsuran" max="{{ $pinjaman->angsuran }}" min="1"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Bayar</button>
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
                    ajax: {
                        url: '{{ route('pinjaman.show', ['id' => ':id']) }}'.replace(':id', window.location
                            .href.split('/').pop()),
                        method: 'GET',
                        dataSrc: 'data'
                    },
                    columns: [{
                            data: 'angsuran_ke_',
                            name: 'angsuran_ke_'
                        },
                        {
                            data: 'tanggal_jatuh_tempo',
                            name: 'tanggal_jatuh_tempo'
                        },
                        {
                            data: 'angsuran_pokok',
                            name: 'angsuran_pokok',
                            render: function(data) {
                                return data !== null ? parseInt(data).toLocaleString('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR'
                                }) : '-';
                            }
                        },
                        {
                            data: 'bunga',
                            name: 'bunga',
                            render: function(data) {
                                return data !== null ? parseInt(data).toLocaleString('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR'
                                }) : '-';
                            }
                        },
                        {
                            data: 'status_pelunasan',
                            name: 'status_pelunasan'
                        },
                        {
                            data: 'keterangan',
                            name: 'keterangan',
                            render: function(data, type, row) {
                                return data ? data : '-';
                            }
                        },
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
                    ajax: {
                        url: '{{ route('pegawai.pinjaman.show', ['id' => ':id']) }}'.replace(':id', window
                            .location
                            .href.split('/').pop()),
                        method: 'GET',
                        dataSrc: 'data'
                    },
                    columns: [{
                            data: 'angsuran_ke_',
                            name: 'angsuran_ke_'
                        },
                        {
                            data: 'tanggal_jatuh_tempo',
                            name: 'tanggal_jatuh_tempo'
                        },
                        {
                            data: 'angsuran_pokok',
                            name: 'angsuran_pokok',
                            render: function(data) {
                                return data !== null ? parseInt(data).toLocaleString('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR'
                                }) : '-';
                            }
                        },
                        {
                            data: 'bunga',
                            name: 'bunga',
                            render: function(data) {
                                return data !== null ? parseInt(data).toLocaleString('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR'
                                }) : '-';
                            }
                        },
                        {
                            data: 'status_pelunasan',
                            name: 'status_pelunasan'
                        },
                        {
                            data: 'keterangan',
                            name: 'keterangan',
                            render: function(data, type, row) {
                                return data ? data : '-';
                            }
                        },
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
@endsection
