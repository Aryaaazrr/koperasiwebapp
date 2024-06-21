@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
    <section>
        <div class="page-header min-vh-100 min-vw-200" >
            <div class="container">
                <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-13 mx-auto">
                <div class="card"> 
                <div class="row">
                        <div class="card card-plain">
                            <div class="card-header pb-0 text-start">
                                <h4 class="font-weight-bolder">Sign In</h4>
                                <p class="mb-0">Enter your username and password to sign in</p>
                            </div>
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $item)
                                                <li>{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="" method="POST">
                                    {{-- <form role="form" action="{{ route('login_proses') }}" method="POST"> --}}
                                    @csrf
                                    <div class="mb-3">
                                        <input type="text" name="username" class="form-control form-control-lg"
                                        placeholder="Username" aria-label="Username" value="{{ old('username') }}">
                                    </div>
                                    {{-- @error('email') --}}
                                    {{-- <small>{{ $message }}</small> --}}
                                    {{-- @enderror --}}
                                    <div class="mb-3">
                                        <input type="password" name="password" class="form-control form-control-lg"
                                            placeholder="Password" aria-label="Password">
                                    </div>
                                    {{-- @error('password') --}}
                                    {{-- <small>{{ $message }}</small> --}}
                                    {{-- @enderror --}}
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Sign
                                            in</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative  h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden">
                                <img src="../icon/pose1.png" style="max-height: 450px; max-width: 300px" >
                               
                            </div>
                        </div> --}}
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
