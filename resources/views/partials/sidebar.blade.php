<div class="min-height-500 bg-success position-absolute w-100"></div>
<aside
    class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 me-4"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" target="_blank">
            {{-- <img src="/icon/koperasilogo.png" class="navbar-brand-img h-100" alt="main_logo"> --}}
            <img src="/icon/logobaru.png" class="navbar-brand-img h-100" alt="main_logo"
                style="max-height: 40px; max-width:200px">
            {{-- <span class="ms-1 font-weight-bold" style="align-content: center">Koperasi Simpan Pinjam<br>Bangun Karya Desa</span> --}}

        </a>
    </div>


    <div class="horizontal dark mt-0  ">
        {{-- <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main"> --}}

        <ul class="navbar-nav">

            <li class="nav-item ">
                @if (Auth::user()->id_role == 1)
                    <a class="@if (request()->is('admin/dashboard')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('/icon/menuicon/dashboard.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2" style="max-height: 30px; max-width: 25px"
                            alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Dashboard</span>
                    </a>
                @elseif (Auth::user()->id_role == 2)
                    <a class="@if (request()->is('kepala/dashboard')) nav-link @else nav-link collapsed @endif"
                        href="{{ route('dashboard') }}">
                        <img src="{{ asset('/icon/menuicon/dashboard.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2" style="max-height: 30px; max-width: 25px"
                            alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Dashboard</span>
                    </a>
                @else
                    <a class="@if (request()->is('pegawai/dashboard')) nav-link @else nav-link collapsed @endif"
                        href="{{ route('pegawai.dashboard') }}">
                        <img src="{{ asset('/icon/menuicon/dashboard.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2" style="max-height: 30px; max-width: 25px"
                            alt="main_logo">
                        <span class="nav-link-text ms-1"style="font-size: 13pt">Dashboard</span>
                    </a>
                @endif
            </li>

            <li class="nav-item ">
                @if (Auth::user()->id_role == 1)
                    <a class="@if (request()->is('admin/pegawai')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('admin.pegawai') }}">
                        <img src="{{ asset('/icon/menuicon/computer-worker.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2" style="max-height: 30px; max-width: 25px"
                            alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Pegawai</span>
                    </a>
                @elseif (Auth::user()->id_role == 2)
                    <a class="@if (request()->is('kepala/pegawai')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('pegawai') }}">
                        <img src="{{ asset('/icon/menuicon/computer-worker.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2" style="max-height: 30px; max-width: 25px"
                            alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Pegawai</span>
                    </a>
                {{-- @else
                    <a class="@if (request()->is('pegawai')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('pegawai.pegawai') }}">
                        <img src="{{ asset('/icon/menuicon/computer-worker.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2" style="max-height: 30px; max-width: 25px"
                            alt="main_logo">
                        <span class="nav-link-text ms-1">Pegawai</span>
                    </a> --}}
                @endif
            </li>

            <li class="nav-item ">
                @if (Auth::user()->id_role == 1)
                    <a class="@if (request()->is('admin/anggota')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('admin.anggota') }}">
                        <img src="{{ asset('/icon/menuicon/customers.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2" style="max-height: 30px; max-width: 25px"
                            alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Anggota</span>
                    </a>
                @elseif (Auth::user()->id_role == 2)
                    <a class="@if (request()->is('kepala/anggota')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('anggota') }}">
                        <img src="{{ asset('/icon/menuicon/customers.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2" style="max-height: 30px; max-width: 25px"
                            alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Anggota</span>
                    </a>
                @else
                    <a class="@if (request()->is('anggota')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('pegawai.anggota') }}">
                        <img src="{{ asset('/icon/menuicon/customers.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2" style="max-height: 30px; max-width: 25px"
                            alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Anggota</span>
                    </a>
                @endif
            </li>

            <li class="nav-item ">
                @if (Auth::user()->id_role == 2)
                    <a class="@if (request()->is('kepala/simpanan')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('simpanan') }}">
                        <img src="{{ asset('/icon/menuicon/reduction.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2" style="max-height: 30px; max-width: 25px"
                            alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Simpanan</span>
                    </a>
                @elseif (Auth::user()->id_role == 3)
                    <a class="@if (request()->is('simpanan')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('pegawai.simpanan') }}">
                        <img src="{{ asset('/icon/menuicon/reduction.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2" style="max-height: 30px; max-width: 25px"
                            alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Simpanan</span>
                    </a>
                @endif
            </li>

            <li class="nav-item ">
                @if (Auth::user()->id_role == 2)
                    <a class="@if (request()->is('kepala/pinjaman')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('pinjaman') }}">
                        <img src="{{ asset('/icon/menuicon/loan.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2"
                            style="max-height: 30px; max-width: 25px" alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Pinjaman</span>
                    </a>
                @elseif (Auth::user()->id_role == 3)
                    <a class="@if (request()->is('pinjaman')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('pegawai.pinjaman') }}">
                        <img src="{{ asset('/icon/menuicon/loan.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2"
                            style="max-height: 30px; max-width: 25px" alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Pinjaman</span>
                    </a>
                @endif
            </li>

            <li class="nav-item ">
                @if (Auth::user()->id_role == 2)
                    <a class="@if (request()->is('kepala/laporan')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('laporan') }}">
                        <img src="{{ asset('/icon/menuicon/seo-report.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2"
                            style="max-height: 30px; max-width: 25px" alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Laba/Rugi</span>
                    </a>
                @elseif (Auth::user()->id_role == 3)
                    <a class="@if (request()->is('laporan')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('pegawai.laporan') }}">
                        <img src="{{ asset('/icon/menuicon/seo-report.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2"
                            style="max-height: 30px; max-width: 25px" alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Laba/Rugi</span>
                    </a>
                @endif
            </li>

            <li class="nav-item ">
                @if (Auth::user()->id_role == 2)
                    <a class="@if (request()->is('kepala/rekap')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('rekap') }}">
                        <img src="{{ asset('/icon/menuicon/checklist.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2"
                            style="max-height: 30px; max-width: 25px" alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Rekap Transaksi</span>
                    </a>
                @elseif (Auth::user()->id_role == 3)
                    <a class="@if (request()->is('laporan')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('pegawai.rekap') }}">
                        <img src="{{ asset('/icon/menuicon/checklist.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2"
                            style="max-height: 30px; max-width: 25px" alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Rekap Transaksi</span>
                    </a>
                @endif
            </li>


            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">SETTINGS</h6>
            </li>

            <li class="nav-item ">
                @if (Auth::user()->id_role == 1)
                    <a class="@if (request()->is('admin/profile')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('admin.profile') }}">
                        <img src="{{ asset('icon/menuicon/profile.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2"
                            style="max-height: 30px; max-width: 25px" alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Profile</span>
                    </a>
                @elseif (Auth::user()->id_role == 2)
                    <a class="@if (request()->is('kepala/profile')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('profile') }}">
                        <img src="{{ asset('icon/menuicon/profile.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2"
                            style="max-height: 30px; max-width: 25px" alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Profile</span>
                    </a>
                @else
                    <a class="@if (request()->is('pegawai/profile')) nav-link active @else nav-link collapsed @endif"
                        href="{{ route('pegawai.profile') }}">
                        <img src="{{ asset('icon/menuicon/profile.png') }}"
                            class="navbar-brand-img h-50 2em text-center me-2"
                            style="max-height: 30px; max-width: 25px" alt="main_logo">
                        <span class="nav-link-text ms-1" style="font-size: 13pt">Profile</span>
                    </a>
                @endif
            </li>
        </ul>
    </div>
    </div>
</aside>
