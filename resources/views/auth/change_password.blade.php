@extends('layouts.master')

@section('content')

@include('layouts.header')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Change Password Page </h1>
        <!-- 
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item">Pages</li>
            <li class="breadcrumb-item active">Blank</li>
            </ol>
        </nav> 
        -->
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-6">

                    {{-- Flash data --}}
                    @if(session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ubah Password Anda Disini</h5>
                            <form action="{{ route('change_password_store') }}" method="post" class="row g-3 needs-validation" novalidate>
                                @csrf
                                <div class="col-12">
                                    <label for="yourUsername" class="form-label">Email : {{ auth()->user()->email }}</label>
                                </div>
                                <div class="col-12">
                                    <label for="yourUsername" class="form-label">Password Baru</label>
                                    <div class="input-group has-validation">
                                        <input type="password" name="password" class="form-control" id="password" required>
                                    </div>
                                    @error('password')
                                    <p style="color:red;">
                                        <span role="alert text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    </p>
                                    @enderror
                                </div>
            
                                <div class="col-12">
                                    <label for="yourPassword" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
            
                            
                                <div class="col-12">
                                    <button class="btn btn-primary w-100" type="submit">Change Password</button>
                                </div>
                                
                            </form>
                    </div>
                </div>

            </div>

            <!--
            <div class="col-lg-6">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Example Card</h5>
                        <p>This is an examle page with no contrnt. You can use it as a starter for your custom pages.</p>
                    </div>
                </div>

            </div>
            -->

        </div>
    </section>

</main><!-- End #main -->

@endsection