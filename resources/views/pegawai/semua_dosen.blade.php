@extends('layouts.master')

@section('content')

@include('layouts.header')

<main id="main" class="main">


    <section class="section">
          <!-- ================ Display None ================= -->
          <div id="myDiv" style="display:none;">
            <form action="{{ route('pegawai.semua_dosen_store') }}" method="post" class="">
                @csrf
                <div class="row">
                    <h2 class="text-center">Tambah Data Dosen</h2>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div class="col-lg-6">
                        <div class="col">
                            <label for="name" class="mt-3 form-label"><strong>Nama Dosen</strong></label>
                            <input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="col">
                            <label for="email" class="mt-3 form-label"><strong>email</strong></label>
                            <input id="email"  class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" value="{{ old('email') }}" required>
                        </div>
                    </div>
                                            
                    
                    <!-- =========== Fakultas and jurusan prodi Section ============================-->

                    <div class="col-lg-6">
                        <div class="col">
                            <label for="fakultas" class="mt-3 form-label"><strong>Fakultas</strong></label>
                            <input id="fakultas" class="form-control" type="text" name="fakultas" 
                            value=
                            "@if(auth()->user()->fakultas == 'Fakultas Teknik')Fakultas Teknik
                            @elseif(auth()->user()->fakultas == 'Fakultas Bahasa Dan Seni')Fakultas Bahasa Dan Seni
                            @elseif(auth()->user()->fakultas == 'Fakultas Ilmu Pendidikan')Fakultas Ilmu Pendidikan
                            @elseif(auth()->user()->fakultas == 'Fakultas Matematika Dan Ilmu Pengetahuan Alam')Fakultas Matematika Dan Ilmu Pengetahuan Alam
                            @elseif(auth()->user()->fakultas == 'Fakultas Ilmu Keolahragaan')Fakultas Ilmu Keolahragaan
                            @elseif(auth()->user()->fakultas == 'Fakultas Ekonomi')Fakultas Ekonomi
                            @elseif(auth()->user()->fakultas == 'Fakultas Ilmu Sosial')Fakultas Ilmu Sosial
                            @endif
                            " disabled>
                        </div>

                        @if(auth()->user()->fakultas == 'Fakultas Ilmu Pendidikan')
                        <!-- ========================== Fakultas Ilmu Pendidikan ==============  -->
                            <div class="col">
                                <label for="jurusan_prodi" class="mt-3 form-label"><strong>Program Studi</strong></label>
                                <select name="jurusan_prodi" class="form-select">
                                    <option selected>Pilih Program Studi</option>
                                    @foreach($fip as $data)
                                    @if(old('jurusan_prodi') == $data->nama)
                                        <option value="{{ $data->nama }}" selected>{{ $data->nama }}</option>
                                    @else
                                        <option value="{{ $data->nama }}">{{ $data->nama }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('jurusan_prodi')
                                        <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                                @enderror
                            </div>
                        
                        @elseif(auth()->user()->fakultas == 'Fakultas Matematika Dan Ilmu Pengetahuan Alam')
                        <!-- ========================== Fakultas Matematika Dan Ilmu Pengetahuan Alam==============  -->
                        @elseif(auth()->user()->fakultas == 'Fakultas Ilmu Keolahragaan')
                        <!-- ========================== Fakultas Ilmu Keolahragaan ==============  -->
                        @elseif(auth()->user()->fakultas == 'Fakultas Teknik')
                        <!-- ========================== Fakultas Teknik ==============  -->
                        @elseif(auth()->user()->fakultas == 'Fakultas Ekonomi')
                        <!-- ========================== Fakultas Ekonomi ==============  -->
                        @elseif(auth()->user()->fakultas == 'Fakultas Ilmu Sosial')
                        <!-- ========================== Fakultas Ilmu Sosial ==============  -->
                        @elseif(auth()->user()->fakultas == 'Fakultas Bahasa Dan Seni')
                        <!-- ========================== Fakultas Bahasa Dan Seni ==============  -->
                            <div class="col">
                                <label for="jurusan_prodi" class="mt-3 form-label"><strong>Program Studi</strong></label>
                                <select name="jurusan_prodi" class="form-select">
                                    <option selected>Pilih Program Studi</option>
                                    @foreach($fbs as $data)
                                    @if(old('jurusan_prodi') == $data->nama)
                                        <option value="{{ $data->nama }}" selected>{{ $data->nama }}</option>
                                    @else
                                        <option value="{{ $data->nama }}">{{ $data->nama }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('jurusan_prodi')
                                        <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                                @enderror
                            </div>
                        
                        @endif



                        <!--  ============================== End  ================ -->
                        
                        
                        <div class="col">
                            <label for="pangkat_id" class="mt-3 form-label"><strong>Golongan</strong></label>
                            <select name="pangkat_id" class="form-select">
                                <option selected>Pilih Pangkat Saat Ini</option>
                                @foreach($pangkat as $data)
                                  @if(old('pangkat_id') == $data->id)
                                    <option value="{{ $data->id }}" selected>{{ $data->golongan }}</option>
                                  @else
                                    <option value="{{ $data->id }}">{{ $data->golongan }}</option>
                                  @endif
                                @endforeach
                              </select>
                              @error('pangkat_id')
                                    <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                            @enderror
                        </div>
                    </div>
                </div> <!-- End Row-->
            </form>
                <div class="row">
                    <hr class="my-3">
                </div> <!-- End ROW -->
            
          </div> <!-- End My div-->
          <!-- End Display None-->


        <!--=== Start The Card =================================================== -->
      <div class="row">
        <div class="col-lg">
          <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-6">
                      <p class="mb-0 mt-4"><strong>Semua Dosen</strong></p>
                    </div>
                    <!-- ======== Trigger Show ======-->
                    <div class="col-6 text-end">
                      <a href="#" class="mb-0 mt-4 btn btn-primary" id="showDiv" style="background-color:#012970 ">+ Tambah</a>
                    </div>
                  </div>

                

                  <!-- ========== Data Table ========-->
                  <div class="row" >
                    <div class="col-lg my-3">
                        <h2>Data Table</h2>
                    </div>
                  </div>
            </div>
          </div>
        </div>
      </div>
    </section>


@endsection

@push('script')
    <script>
        var showDivButton = document.getElementById('showDiv');
        var myDiv = document.getElementById('myDiv');
        var divVisible = false;

        showDivButton.addEventListener('click', function() {
        if (divVisible) {
            myDiv.style.display = 'none';
            divVisible = false;
        } else {
            myDiv.style.display = 'block';
            divVisible = true;
        }
        });
    </script>
@endpush