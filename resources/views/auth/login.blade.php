@extends('layouts.master')

@section('content')

<body>

    <main>
      <div class="container">
  
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
  
                <div class="d-flex justify-content-center py-4">
                  <a href="#" class="logo d-flex align-items-center w-auto">
                    {{-- <img src="/templates/assets/img/logo.png" alt=""> --}}
                    <span class="d-none d-lg-block">Kenaikan Pangkat Dosen</span>
                  </a>
                </div><!-- End Logo -->
  
                 {{-- Flash data --}}
                @if(session()->has('berhasil'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('berhasil') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                {{-- Flash data --}}
                @if(session()->has('gagal'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('gagal') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- Flash data --}}
                @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif



                <div class="card mb-3">
  
                  <div class="card-body">
  
                    <div class="pt-4 pb-2">
                      <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                      <p class="text-center small">Enter your email</p>
                    </div>
  
                    <form action="{{ route('authenticate')  }}" method="post" class="row g-3 needs-validation" novalidate>
                      @csrf
                      <div class="col-12">
                        <label for="yourUsername" class="form-label">Email</label>
                        <div class="input-group has-validation">
                          <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" placeholder="example@gmail.com" required>
                          <div class="invalid-feedback">Masukan Email Anda</div>
                        </div>
                      </div>
  
                      <div class="col-12">
                        <label for="yourPassword" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <div class="invalid-feedback">Silahkan Masukan Password Anda </div>
                      </div>
  
                  
                      <div class="col-12">
                        <button class="btn btn-primary w-100" type="submit">Login</button>
                      </div>
                      <div class="col-12">
                        <p class="small mb-0">Lupa Password? <a href="{{ route('show_forgot_password') }}">Buat Password Baru</a></p>
                      </div>
                    </form>
  
                  </div>
                </div>
  
                <div class="credits">
                  <!-- All the links in the footer should remain intact. -->
                  <!-- You can delete the links only if you purchased the pro version. -->
                  <!-- Licensing information: https://bootstrapmade.com/license/ -->
                  <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                  Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                </div>
  
              </div>
            </div>
          </div>
  
        </section>
  
      </div>
    </main><!-- End #main -->
  
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  
    @endsection