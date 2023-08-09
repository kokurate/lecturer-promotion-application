@extends('layouts.master')

@section('content')

@include('layouts.header')
  


  <main id="main" class="main">


    <section class="section">
      <div class="row">
        <div class="col-lg">

          <div class="card">
            <div class="card-body">
                <h2 class="mt-5 text-center" style="color:#012970;"><strong>Detail Kegiatan PAK </strong></h2>
                <h2 class="text-center" style="color:#012970;"><strong>{{ $title }}</strong></h2>
            
                <div class="col-lg text-center mt-3">
                    <a href="{{ route('penunjang-tri-dharma-pt') }}" class="btn btn-lg btn-warning rounded-pill" style="color:#012970;padding-left: 25px; padding-right: 25px;">Kembali</a>
                    
                    <form class="inline-form" action="{{ route('penunjang_tri_dharma_pt_destroy', $record->slug) }}" method="post">
                        @method('delete')
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill" style="background-color:#012970; color:#ffffff;padding-left: 25px; padding-right: 25px;" onclick="confirmDelete(event)">Hapus</button>
                    </form>
                    
                </div>
            

            </div> <!-- End Card Body-->
          </div> <!-- End Card -->
        </div> <!-- --End col LG -->
      </div> <!-- End Row -->

      <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <p class="mt-3 mb-0" style="color:#012970;"><strong>Kegiatan/Judul Karya Ilmiah</strong></p>
                    <p class="mt-0" style="color:#012970;">{{ $record->kegiatan }}</p>

                    <p class="mt-3 mb-0" style="color:#012970;"><strong>Tipe Kegiatan</strong></p>
                    <p class="mt-0" style="color:#012970;">{{ $record->tipe_kegiatan }}</p>

                    @if($record->komponen_kegiatan)<p class="mt-0 mb-0" style="color:#012970;"><strong>Komponen Kegiatan</strong></p>@endif
                    <p class="mt-0" style="color:#012970;">{{ $record->komponen_kegiatan ?? '' }}</p>
                
                    @if($record->nilai_kegiatan)<p class="mt-0 mb-0" style="color:#012970;"><strong>Nilai Kegiatan</strong></p>@endif
                    <p class="mt-0" style="color:#012970;">{{ $record->nilai_kegiatan ?? '' }}</p>

                    <p class="mt-3 mb-0" style="color:#012970;"><strong>Tempat/Instansi</strong></p>
                    <p class="mt-0" style="color:#012970;">{{ $record->tempat }}</p>

                    <p class="mt-3 mb-0" style="color:#012970;"><strong>Tanggal Pelaksanaan</strong></p>
                    <p class="mt-0" style="color:#012970;">{{ \Carbon\Carbon::parse($record->tanggal_pelaksanaan)->format('d-m-Y') }}</p>

                    <p class="mt-3 mb-0" style="color:#012970;"><strong>Angka Kredit : {{ $record->angka_kredit }} </strong></p>
                    <p class="mt-3 mb-0" style="color:#012970;"><strong>Bukti</strong></p>
                    <a href="{{ asset('storage/'. $record->bukti ) }}" class="text-secondary text-xs mt-3" target="__blank">
                        <i class="fas fa-solid fa-file-pdf ps-3"></i> Lihat Bukti
                    </a>

                </div> <!-- End Card Body -->
            </div> <!-- End card -->
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    
                    <!-- Vertical Form -->
                    <form class="my-3" action="{{ route('penunjang_tri_dharma_pt_edit_store', $record->slug) }}" method="post" enctype="multipart/form-data">
                        @csrf

                            <div class="col-my-3">
                                <label for="kegiatan" class="mt-3 form-label" style="color:#012970;"><strong>Kegiatan</strong> (optional)</label>
                                <input class="form-control{{ $errors->has('kegiatan') ? ' is-invalid' : '' }}" type="text" name="kegiatan" value="{{ old('kegiatan', $record->kegiatan) }}" required>
                                
                                @if ($errors->has('kegiatan'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('kegiatan') }}
                                    </div>
                                @endif
                            </div>

                            <div class="col-my-3">
                                <label for="angka_kredit" class="mt-3 form-label" style="color:#012970;"><strong>Angka Kredit</strong> (optional)</label>
                                <input id="angka_kredit" class="form-control{{ $errors->has('angka_kredit') ? ' is-invalid' : '' }}" type="text" name="angka_kredit" value="{{ old('angka_kredit', $record->angka_kredit) }}" pattern="[0-9.]*" oninput="this.value = this.value.replace(/,/g, '')" placeholder="" title="Hanya Angka Yang Diperbolehkan" required>
                                
                                @if ($errors->has('angka_kredit'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('angka_kredit') }}
                                    </div>
                                @endif
                            </div>

                            <div class="col-my-3">
                                <label for="tempat" class="mt-3 form-label" style="color:#012970;"><strong>Tempat/ Instansi</strong> (optional)</label>
                                <input id="tempat" class="form-control{{ $errors->has('tempat') ? ' is-invalid' : '' }}" type="text" name="tempat" value="{{ old('tempat', $record->tempat) }}"  required>
                                
                                @if ($errors->has('tempat'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('tempat') }}
                                    </div>
                                @endif
                            </div>

                            
                            <div class="col-my-3">
                                <label for="tanggal_pelaksanaan" class="mt-3 form-label" style="color:#012970;"><strong>Tanggal Pelaksanaan</strong> (optional)</label>
                                <input type="date" id="tanggal_pelaksanaan" class="form-control{{ $errors->has('tanggal_pelaksanaan') ? ' is-invalid' : '' }}"  name="tanggal_pelaksanaan" value="{{ old('tanggal_pelaksanaan', $record->tanggal_pelaksanaan) }}"  required>
                                
                                @if ($errors->has('tanggal_pelaksanaan'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('tanggal_pelaksanaan') }}
                                    </div>
                                @endif
                            </div>


                            <div class="col my-3">
                                <label for="inputNanme4" class="form-label" style="color:#012970;"><strong>Bukti</strong> (optional)</label>
                                <input class="form-control{{ $errors->has('bukti') ? ' is-invalid' : '' }}" type="file" id="pdf_file" name="bukti" accept=".pdf">
                                @if ($errors->has('bukti'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('bukti') }}
                                    </div>
                                @endif

                                <div id="preview" class="my-2 text-center">
                                    
                                </div>

                            </div>
                            <div class="col-lg text-center">
                                <button type="submit" class="my-2 btn btn-primary btn-lg rounded-pill" style="background-color:#012970; color:#ffffff;padding-left: 50px; padding-right: 50px;">Edit</button>
                            </div>

                    </form><!-- Vertical Form -->

                </div> <!-- End Card Body -->
            </div> <!-- End card -->
        </div>

      </div>
    </section>

  </main><!-- End #main -->

  @include('layouts.footer')

@endsection

@push('script')

    <!-- Tambahkan kode berikut di bagian bawah template untuk mengaktifkan sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function confirmDelete(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
        }
    </script>

    <script>
        // sanitized input
        const inputField = document.getElementById('angka_kredit');

        inputField.addEventListener('input', function(e) {
            const inputValue = e.target.value;
            const sanitizedValue = inputValue.replace(/[^0-9.,]/g, ''); // Remove any characters other than numbers, dots, and commas
            e.target.value = sanitizedValue;
        });

    </script>


    <!--  PDF Preview -->
    <script>
            
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