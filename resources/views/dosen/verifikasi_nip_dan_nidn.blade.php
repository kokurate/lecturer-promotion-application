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
                  <a href="#" class=" text-center logo d-flex align-items-center w-auto">
                    {{-- <img src="/templates/assets/img/logo.png" alt=""> --}}
                    <span class="d-none d-lg-block">Sebelum Masuk Ke Dashboard</span>
                  </a>
                </div><!-- End Logo -->
  
        



                <div class="card mb-3">
  
                  <div class="card-body">
  
                    <div class="pt-4 pb-2">
                      <h5 class="card-title text-center pb-0 fs-4">Tambahkan NIP dan NIDN</h5>
                    </div>
  
                        <form method="POST" action="{{ route('dosen.verify_nip_and_nidn_store') }}">
                            @csrf
                        
                            <div class="form-group my-2">
                                <label for="nip" class="mt-3 form-label"><strong>NIP</strong></label>
                                <input id="nip" class="form-control{{ $errors->has('nip') ? ' is-invalid' : '' }}" type="text" name="nip" value="{{ old('nip') }}" required autocomplete="off">
                                @error('nip')
                                    <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                                @enderror
                            </div>
                        
                            <div class="form-group my-2">
                                <label for="nidn" class="mt-3 form-label"><strong>NIDN</strong></label>
                                <input id="nidn" class="form-control{{ $errors->has('nidn') ? ' is-invalid' : '' }}" type="text" name="nidn" value="{{ old('nidn') }}" required autocomplete="off">
                                @error('nidn')
                                    <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                                @enderror
                            </div>

                            <div class="d-flex justify-content-center">
                                <button type="submit" class="my-3 btn " style="background-color:#012970; color:#ffffff;">Submit</button>
                            </div>
                        </form>
  
                  </div>
                </div>
  
                {{-- <a class="dropdown-item d-flex align-items-center" href="#">
                  
                  <span> --}}
                  <form action="{{ route('logout') }}" method="post">
                    @csrf
                    {{-- <button type="submit" class="" style="text-decoration: none; border:none; border-radius:none;">Log Out</button> --}}
                    <button type="submit" class="btn text-decoration-none border-0 p-0 m-0">Log Out</button>
  
                  </form> 
                {{-- </span>
                </a> --}}
  
              </div>
            </div>
          </div>
  
        </section>
  
      </div>
    </main><!-- End #main -->
  
  
  
    @endsection