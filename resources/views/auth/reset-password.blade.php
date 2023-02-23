@extends('layouts.master')

@section('content')

<body>

    <main>
      <div class="container">
  
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
  
                <!-- <div class="d-flex justify-content-center py-4">
                  <a href="index.html" class="logo d-flex align-items-center w-auto">
                    {{-- <img src="/templates/assets/img/logo.png" alt=""> --}}
                    <span class="d-none d-lg-block">Kenaikan Pangkat Dosen</span>
                  </a>
                </div> -->

                <!-- End Logo -->
  
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
                      <h5 class="card-title text-center pb-0 fs-4">Reset Password</h5>
                      <p class="text-center small">Ubah Password Anda</p>
                    </div>
  
                    <form action="{{ route('store_reset_password', $user->my_token)  }}" method="post" class="row g-3 needs-validation" novalidate>
                      @csrf
                      <div class="col-12">
                        <label for="yourUsername" class="form-label">Email : {{ $user->email }}</label>
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
                        <button class="btn btn-primary w-100" type="submit">Submit</button>
                      </div>
                      
                    </form>
  
                  </div>
                </div>
  
                
              </div>
            </div>
          </div>
  
        </section>
  
      </div>
    </main><!-- End #main -->
  
  
    @endsection