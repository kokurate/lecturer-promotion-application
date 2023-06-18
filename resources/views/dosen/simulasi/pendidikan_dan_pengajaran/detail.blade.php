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
              <h2 class="text-center" style="color:#012970;"><strong>Pendidikan dan Pengajaran</strong></h2>
            
              
                <div class="col-lg text-center mt-3">
                    <a href="{{ route('pendidikan-dan-pengajaran') }}" class="btn btn-lg btn-warning rounded-pill" style="color:#012970;padding-left: 25px; padding-right: 25px;">Kembali</a>
                    
                    <form class="inline-form"" action="{{ route('pendidikan_dan_pengajaran_destroy', $record->slug) }}" method="post">
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
                    <p class="mt-3 mb-0" style="color:#012970;"><strong>Nama Kegiatan</strong></p>
                    <p class="mt-0" style="color:#012970;">{{ $record->kegiatan }}</p>

                    <p class="mt-3 mb-0" style="color:#012970;"><strong>Tipe Kegiatan</strong></p>
                    <p class="mt-0" style="color:#012970;">{{ $record->tipe_kegiatan }}</p>
                    <p class="mt-0" style="color:#012970;">{{ $record->komponen_kegiatan ?? '' }}</p>
                </div> <!-- End Card Body -->
            </div> <!-- End card -->
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <p class="mt-3 mb-0" style="color:#012970;"><strong>Angka Kredit : {{ $record->angka_kredit }} </strong></p>
                    <p class="mt-3 mb-0" style="color:#012970;"><strong>@if($record->sks == '-')  @else SKS : {{ $record->sks }} @endif</strong></p>

                    <p class="mt-3 mb-0" style="color:#012970;"><strong>Bukti</strong></p>
                    <a href="{{ asset('storage/'. $record->bukti ) }}" class="text-secondary text-xs mt-3" target="__blank">
                        <i class="fas fa-solid fa-file-pdf ps-3"></i> Lihat Bukti
                    </a>

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

    

@endpush