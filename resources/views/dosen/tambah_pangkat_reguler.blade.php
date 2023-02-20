@extends('layouts.master')

@section('content')

@include('layouts.header')
  


  <main id="main" class="main">


    <section class="section">
      <div class="row">
        <div class="col-lg">

          <div class="card">
            <div class="card-body">
              <h2 class="mt-5 text-center" style="color:#012970;"><strong>Unggah Berkas Persyaratan</strong></h2>
              
                
            <!-- Vertical Form -->
            <form class="row g-3 my-3">
                <div class="col-lg-5">
                    <div class="col my-3">
                        <label for="inputNanme4" class="form-label">1. Kartu Pegawai & NIP Baru BKN</label>
                        <input class="form-control" type="file" id="formFile"  accept=".pdf">
                    </div> 
                    <div class="col my-3">
                        <label for="inputNanme4" class="form-label">2. SK Pengangkatan Pertama (CPNS)</label>
                        <input class="form-control" type="file" id="formFile"  accept=".pdf">
                    </div> 
                    <div class="col my-3">
                        <label for="inputNanme4" class="form-label">3. SK Pangkat Terakhir</label>
                        <input class="form-control" type="file" id="formFile"  accept=".pdf">
                    </div> 
                    <div class="col my-3">
                        <label for="inputNanme4" class="form-label">4. SK Jabatan Fungsional Terakhir dan PAK</label>
                        <input class="form-control" type="file" id="formFile"  accept=".pdf">
                    </div> 
                    <div class="col my-3">
                        <label for="inputNanme4" class="form-label">5. PPK & SKP dua tahun terakhir</label>
                        <input class="form-control" type="file" id="formFile"  accept=".pdf">
                    </div> 
                </div> <!-- End-col-lg6 -->

                <div class="col-lg-2">
                  <p class="text-center">
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                    |<br>
                  </p>
                </div> <!-- Middle -->

                <div class="col-lg-5">
                    <div class="col my-3">
                        <label for="inputNanme4" class="form-label">6. Ijazah Terakhir (jika gelar belum tercantum dalam SK Pangkat Terakhir) </label>
                        <input class="form-control" type="file" id="formFile"  accept=".pdf">
                    </div> 
                    <div class="col my-3">
                        <label for="inputNanme4" class="form-label">7. SK Tugas Belajar atau Surat Izin Studi (sesuai no.5)</label>
                        <input class="form-control" type="file" id="formFile"  accept=".pdf">
                    </div> 
                    <div class="col my-3">
                        <label for="inputNanme4" class="form-label">Keterangan Membina Mata Kuliah dari Jurusan</label>
                        <input class="form-control" type="file" id="formFile"  accept=".pdf">
                    </div> 
                    <div class="col my-3">
                        <label for="inputNanme4" class="form-label">Surat Pernyataan Setiap Bidang Tridharma (beserta bukti pendukung)</label>
                        <input class="form-control" type="file" id="formFile"  accept=".pdf">
                    </div> 
                </div>

                <div class="col-lg text-center">
                    <a href="#" class="btn btn-lg btn-warning rounded-pill" style="color:#012970;padding-left: 50px; padding-right: 50px;">Batal</a>
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