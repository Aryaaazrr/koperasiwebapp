@extends('layouts.main')

@section('title', 'Rekap Transaksi')
@section('subtitle', 'Data Rekap Transaksi')

@section('content')
    <main class="container">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <!-- TOMBOL TAMBAH DATA -->
            <div class="d-flex justify-content-between">
                <div class="pb-2">
                    @if (Auth::user()->id_role == 1)
                        <a href='{{ route('admin.pegawai.export') }}' class="btn btn-secondary">Cetak PDF</a>
                    @elseif (Auth::user()->id_role == 2)
                        <a href='{{ route('pegawai.export') }}' class="btn btn-secondary">Cetak PDF</a>
                    @else
                        <a href='{{ route('pegawai.pegawai.export') }}' class="btn btn-secondary">Cetak PDF</a>
                    @endif
                </div>
            </div>

            <div class="table-responsive p-0">
                <table class="table table-hover table-bordered align-items-center" id="myTable">
                    <thead style="font-size: 10pt">
                        <tr style="background-color: rgb(187, 246, 201)">
                            <th class="text-center">No</th>
                            <th class="text-center">NIK</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Jenis Kelamin</th>
                            <th class="text-center">Alamat</th>
                            <th class="text-center">No Handphone</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center" style="font-size: 10pt">
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                processing: true,
                ordering: true,
                responsive: true,
                serverSide: true,
                ajax: "{{ route('rekap') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'jenis_kelamin',
                        name: 'jenis_kelamin'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
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
                                '<a href="{{ route('admin.pegawai.edit', '') }}/' + data.id_users +
                                '" style="font-size: 10pt" class="btn btn-info m-1 edit-btn" ' +
                                'data-id="' + data.id +
                                '">Edit</a>' +
                                '<a href="{{ route('admin.pegawai.destroy', '') }}/' + data
                                .id_users +
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
@endsection