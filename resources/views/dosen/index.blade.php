@extends('layouts.master')

@section('content')

@include('layouts.header')
  


  <main id="main" class="main">


    <section class="section">
      <div class="row">
        <div class="col-lg">

          <div class="card">
            <div class="card-body">
              <h2 class="mt-5" style="color:#012970;">Selamat Datang, <strong>{{ auth()->user()->name }}</strong></h2>
              <?php
                $valuefakultas = auth()->user()->fakultas;
                $substringsfakultas = explode(' ', $valuefakultas);
                $fakultas = $substringsfakultas[0]; 

                $valueafterfakultas = auth()->user()->fakultas;
                $substringsafterfakultas = explode(' ', $valueafterfakultas);
                $afterfakultas = implode(' ', array_slice($substringsafterfakultas, 1))
              ?>
              <h3 style="color:#012970;">Dosen {{ auth()->user()->jurusan_prodi }}, {{ $fakultas }} <strong>{{ $afterfakultas }}</strong></h3>
              <p style="margin-top: 25px;"></p>
              <h4 style="color:#012970">{{ auth()->user()->pangkat->jabatan_fungsional }}, {{ auth()->user()->pangkat->pangkat }}, {{ auth()->user()->pangkat->golongan }}</h4>
              <p><strong>Kenaikan Pangkat  : 
               
                @if(auth()->user()->status_kenaikan_pangkat)
                    @if(auth()->user()->status_kenaikan_pangkat->status == 'Tersedia')
                        <span style="color:#00ff66">Tersedia</span><br>
                        <span class="mt-3">Untuk Naik Ke Golongan {{ auth()->user()->status_kenaikan_pangkat->golongan ?? ''}}</span>
                    @elseif(auth()->user()->status_kenaikan_pangkat->status == 'Belum Tersedia')
                        <span style="color:#00ff66">Tersedia</span><br>
                    @elseif(auth()->user()->status_kenaikan_pangkat->status == NULL || auth()->user()->status_kenaikan_pangkat->status == 'Permintaan Kenaikan Pangkat Reguler')
                        <span style="color:#cc0808"> Belum Tersedia </span>
                    @endif
                @else
                    <span style="color:#cc0808">Belum Tersedia </span>
                @endif

              </strong></p>
              
        <div class="text-center my-5">
            <div class="text-align-center">
              @if(auth()->user()->status_kenaikan_pangkat)
                  @if(auth()->user()->status_kenaikan_pangkat->status == 'Belum Tersedia')
                      <a href="{{ route('dosen.status_kenaikan_pangkat') }}" class="btn btn-success btn-lg rounded-pill" style="background-color:#0fcc08; padding-left: 50px; padding-right: 50px;border:none;">Lihat Status <br> Kenaikan Pangkat</a>
                  @elseif(auth()->user()->status_kenaikan_pangkat->status == 'Tersedia')
                      <a href="{{ route('dosen.tambah_pangkat_reguler', $user->email) }}" class="btn btn-primary btn-lg rounded-pill" style="background-color:#012970; padding-left: 50px; padding-right: 50px;border:none;">Ajukan Kenaikan <br> Pangkat</a>
                  @elseif(auth()->user()->status_kenaikan_pangkat->status == 'Permintaan Kenaikan Pangkat Reguler') <!-- Nothing -->
                  @elseif(auth()->user()->status_kenaikan_pangkat->status == NULL)                    
                    <form action="{{ route('dosen.sudah_bisa_naik_pangkat_reguler') }}" method="post">
                      @csrf
                      <input type="hidden" name="status" value="Tersedia">
                      <button class="btn btn-primary btn-lg rounded-pill" type="submit"  style="background-color:#012970; padding-left: 40px; padding-right: 40px; border:none;" onclick="confirmNaikPangkat(event)">
                          Saya Sudah Bisa <br> Naik Pangkat
                      </button>
                    </form>
                  @endif
              @else
              
              @endif
          

            </div>
        </div>
         

            </div>
          </div>

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
          function confirmNaikPangkat(event) {
              event.preventDefault();
              Swal.fire({
                  title: 'Apakah Anda yakin sudah bisa naik pangkat?',
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