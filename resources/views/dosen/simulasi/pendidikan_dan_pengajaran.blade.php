@extends('layouts.master')

@section('content')

@include('layouts.header')
  


  <main id="main" class="main">


    <section class="section">
      <div class="row">
        <div class="col-lg">

          <div class="card">
            <div class="card-body">
              <h2 class="mt-5 text-center" style="color:#012970;"><strong>Tambah Kegiatan Simulasi PAK </strong></h2>
              <h2 class="text-center" style="color:#012970;"><strong>Pendidikan dan Pengajaran</strong></h2>
            
              
                
            <!-- Vertical Form -->
            <form class="row g-3 my-3" action="" method="post" enctype="multipart/form-data">
              @csrf
                    <div class="col-lg-6">
                        {{-- <div class="card">
                            <div class="card-body"> --}}
                            
                                <div class="col-my-3">
                                    <label for="nama" class="mt-3 form-label" style="color:#012970;"><strong>Kegiatan</strong></label>
                                    <input class="form-control{{ $errors->has('kegiatan') ? ' is-invalid' : '' }}" type="text" name="nama" value="{{ old('nama') }}" required>
                        
                                </div>

                                <div class="col my-3">
                                    <label for="inputNanme4" class="form-label" style="color:#012970;"><strong>Tipe Kegiatan</strong></label>
                                    <select name="tipe_kegiatan" class="form-select">
                                    <option value="" selected>Pilih Tipe Kegiatan</option>
                                        
                                    </select>
                                </div> 

                                <div class="col my-3">
                                    <label for="inputNanme4" class="form-label" style="color:#012970;"><strong>Bukti</strong></label>
                                    <input class="form-control{{ $errors->has('path') ? ' is-invalid' : '' }}" type="file" id="pdf_file" name="path" accept=".pdf">
                                
                                    <div id="preview" class="my-2 text-center"></div>
                                </div>
                                    @if ($errors->has('path'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('path') }}
                                        </div>
                                    @endif


                            {{-- </div> <!-- End Card Body-->
                        </div> <!-- End Card --> --}}
                    </div> <!-- End-col-lg6 -->

                {{-- <div class="col-lg-2">
                  <p class="text-center">
                  </p>
                </div>  --}}
                <!-- Middle -->

                    <div class="col-lg-6">
                        {{-- <div class="card">
                            <div class="card-body"> --}}
                                
                                <div class="col my-3">
                                    <label for="inputNanme4" class="form-label" style="color:#012970;"><strong>SKS (Jika Kegiatan Perkuliahan)</strong></label>
                                    <select name="" class="form-select">
                                        <option selected>Pilih File Pada Storage Anda</option>
                                        <option value="">testing</option>
                                    </select>
                                </div>  

                            

                                <div class="col my-3">
                                    <label for="inputNanme6" class="form-label" style="color:#012970;"><strong>T.A</strong></label>
                                    <select name="" class="form-select">
                                        <option selected>Pilih Semester</option>
                                        <option value="">Ganjil</option>
                                        <option value="">Genap</option>
                                    </select>
                                </div>  

                                <div class="col my-3">
                                    <label for="inputNanme6" class="form-label" style="color:#012970;"><strong>Semester</strong></label>
                                    <select name="" class="form-select">
                                        <option selected>Pilih Semester</option>
                                        <option value="">2002</option>
                                        <option value="">Genap</option>
                                    </select>
                                </div>  


                            {{-- </div> <!-- End Card Body -->
                        </div> <!-- End Card -->  --}}
                    </div>

                <div class="col-lg text-center">
                    <a href="{{ route('dosen.index') }}" class="btn btn-lg btn-warning rounded-pill" style="color:#012970;padding-left: 50px; padding-right: 50px;">Batal</a>
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill" style="background-color:#012970; color:#ffffff;padding-left: 50px; padding-right: 50px;">Ajukan</button>
                </div>
            </form><!-- Vertical Form -->

            </div> <!-- End Card Body-->
          </div> <!-- End Card -->
        </div> <!-- --End col LG -->
      </div> <!-- End Row -->
    </section>

  </main><!-- End #main -->

  @include('layouts.footer')

@endsection

@push('script')
    <script>
        // PDF Preview
        const fileInput = document.querySelector('#pdf_file');
        const preview = document.querySelector('#preview');

        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            const reader = new FileReader();

            reader.onload = (e) => {
                const pdfUrl = e.target.result;
                const iframe = document.createElement('iframe');

                iframe.src = pdfUrl;
                iframe.width = '80%';
                iframe.height = '400';

                preview.innerHTML = '';
                preview.appendChild(iframe);
            };

            reader.readAsDataURL(file);
        });
    </script>


@endpush