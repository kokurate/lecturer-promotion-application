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
                  {{-- <a href="#" class=" text-center logo d-flex align-items-center w-auto">
                     <img src="/templates/assets/img/logo.png" alt=""> 
                    <span class="d-none d-lg-block">Sebelum Masuk Ke Dashboard</span>
                  </a> --}}
                </div><!-- End Logo -->
  
        
                <div class="card mb-3">
  
                  <div class="card-body">
  
                    <div class="pt-4 pb-2">
                      <h5 class="card-title text-center pb-0 fs-4">Forgot Your Password</h5>
                    </div>
  
                        <form method="POST" action="{{ route('store_forgot_password') }}">
                            @csrf
                        
                            <div class="form-group my-2">
                                <label for="email" class="mt-3 form-label"><strong>Email</strong></label>
                                <input id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" value="{{ old('email') }}" required autocomplete="off">
                                @error('email')
                                    <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                                @enderror
                            </div>
                        
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="my-3 btn " style="background-color:#012970; color:#ffffff;">Submit</button>
                            </div>
                        </form>
  
                  </div>
                </div>
  
                <div class="d-flex align-items-center justify-content-center">
                    <a class="dropdown-item d-flex align-items-center text-center" href="{{ route('login') }}">
                      <span>Kembali</span>
                    </a>
                </div>
  
              </div>
            </div>
          </div>
  
        </section>
  
      </div>
    </main><!-- End #main -->
  
  
  
    @endsection