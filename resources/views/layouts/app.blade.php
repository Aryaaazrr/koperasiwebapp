<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('icon/logobaru.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('icon/logo.png') }}">
    <title>
        @yield('title') | Koperasi Simpan Pinjam Bangun Karya Desa
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link rel="stylesheet" href="{{ asset('style/assets/css/nucleo-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/assets/css/nucleo-svg.css') }}" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="style/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" link rel="stylesheet" href="{{ asset('style/assets/css/argon-dashboard.css?v=2.0.4') }}" />
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> --}}
</head>

<body class="">
    @include('partials.navbarLogin')
    <main class="main-content  mt-0">
        @yield('content')
    </main>
    <!--   Core JS Files   -->
    <script src="style/assets/js/core/popper.min.js"></script>
    <script src="style/assets/js/core/bootstrap.min.js"></script>
    <script src="style/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="style/assets/js/plugins/smooth-scrollbar.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"> --}}
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="style/assets/js/argon-dashboard.min.js?v=2.0.4"></script>
</body>

</html>
