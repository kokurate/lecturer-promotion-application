@extends('layouts.master')

@section('content')

<body>

    <main>
      <div class="container">
  
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
  
                



                <div class="card mb-3">
  
                  <div class="card-body">
  
                    <div class="pt-4 pb-2">
                      <h5 class="card-title text-center pb-0 fs-4">Registrasi Akun Pegawai</h5>
                      <p class="text-center small">Masukkan Data</p>
                    </div>
                    
                    <form action="{{ route('admin.register_pegawai_store')  }}" method="post" class="row g-3 needs-validation" novalidate>
                      @csrf
                      
                        <div class="col">
                            <label for="fakultas" class="mt-3 form-label"><strong>Fakultas</strong></label>
                            <select name="fakultas" class="form-select">
                                <option selected>Pilih Fakultas</option>
                                @foreach($fakultas as $data)
                                @if(old('fakultas') == $data->nama)
                                    <option value="{{ $data->nama }}" selected>{{ $data->nama }}</option>
                                @else
                                    <option value="{{ $data->nama }}">{{ $data->nama }}</option>
                                @endif
                                @endforeach
                            </select>
                            @error('fakultas')
                                    <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                            @enderror
                        </div>
                      
                      <div class="col-12">
                        <label for="yourUsername" class="form-label">Nama</label>
                        <div class="input-group has-validation">
                          <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" placeholder="" required>
                          <div class="invalid-feedback">Masukan Nama Anda</div>
                        </div>
                        @error('name')
                            <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                        @enderror
                      </div>

                      <div class="col-12">
                        <label for="yourUsername" class="form-label">Email</label>
                        <div class="input-group has-validation">
                          <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" placeholder="" required>
                          <div class="invalid-feedback">Masukan Email Anda</div>
                        </div>
                        @error('email')
                            <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                        @enderror
                      </div>
  
                      

                      <div class="col-12">
                        <label for="yourPassword" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <div class="invalid-feedback">Silahkan Masukan Password Anda </div>
                        @error('password')
                            <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                        @enderror
                      </div>
  
                  
                      <div class="col-12">
                        <button class="btn btn-primary w-100" type="submit">Login</button>
                      </div>
                     
                    </form>
  
                  </div>
                </div>

                <div class="col-12">
                    <p class="small mb-0 text-center"><a href="{{ route('admin.index') }}">Kembali</a></p>
                </div>
  <br><br><br>
             
  
              </div>
            </div>
          </div>
  
        </section>
  
      </div>
    </main><!-- End #main -->
  
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  
    @endsection