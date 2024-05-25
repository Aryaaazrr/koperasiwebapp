@extends('layouts.main')

@section('title', 'Pinjaman')
@section('subtitle', 'Kredit Macet')

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
                <div class="pb-2">
                    @if (Auth::user()->id_role == 2)
                        <a href='{{ route('pinjaman') }}' class="btn btn-secondary">Kembali</a>
                    @else
                        <a href='{{ route('pegawai.pinjaman') }}' class="btn btn-secondary">Kembali</a>
                    @endif
                </div>
                <div class="table-responsive p-0">
                    <table class="table table-hover table-bordered align-items-center" id="myTable">
                        <thead style="font-size: 10pt">
                            <tr style="background-color: rgb(187, 246, 201)">
                                <th class="text-center">No</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Jumlah Pinjaman</th>
                                <th class="text-center">Jumlah Terlambat</th>
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
                        ajax: "{{ route('pinjaman.edit') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'nama_anggota',
                                name: 'nama_anggota'
                            },
                            {
                                data: 'jumlah_pinjaman',
                                name: 'jumlah_pinjaman'
                            },
                            {
                                data: 'jumlah_terlambat',
                                name: 'jumlah_terlambat'
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
                        ajax: "{{ route('pegawai.pinjaman.edit') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'nama_anggota',
                                name: 'nama_anggota'
                            },
                            {
                                data: 'jumlah_pinjaman',
                                name: 'jumlah_pinjaman'
                            },
                            {
                                data: 'jumlah_terlambat',
                                name: 'jumlah_terlambat'
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
    </body>
@endsection
