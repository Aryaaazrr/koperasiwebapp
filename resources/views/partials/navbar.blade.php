<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
    data-scroll="false">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 fs-5 text-white text-decoration-none"
                        href="">{{ Auth::user()->role->nama_role }}</a></li>
                <li class="breadcrumb-item text-sm fs-5 text-white active" aria-current="page">@yield('title')</li>
            </ol>
            <h6 class="font-weight-bolder fs-5 text-white mb-0">@yield('subtitle')</h6>
        </nav>
        {{-- <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5" >
            <li class="breadcrumb-item  text-sm"><a class=" text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item fs-5 text-sm text-white active" aria-current="page">@yield('subtitle')</li>
          </ol>
          <h6 class="font-weight-bolder fs-5 text-white mb-0" >@yield('subtitle')</h6>
        </nav> --}}

        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            </div>

            <ul class="navbar-nav justify-content-end">
                <div class="dropdown-center">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa fa-user me-sm-1 text-dark "></i>
                        {{ Auth::user()->nama }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-lg-end">
                        @if (Auth::user()->id_role == 1)
                            <li><a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a></li>
                        @elseif (Auth::user()->id_role == 2)
                            <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('pegawai.profile') }}">Profile</a></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                    </ul>
                </div>

            </ul>
        </div>
    </div>
</nav>
