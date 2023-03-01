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
              <h4 class="text-center">Simbol <span style="color:rgb(255, 0, 0)">***</span> Wajib Diisi</h4>
              
                
            <!-- Vertical Form -->
            <form class="row g-3 my-3" action="{{ route('dosen.tambah_pangkat_reguler_store', $user->email) }}" method="post" enctype="multipart/form-data">
              @csrf
                <div class="col-lg-6">
                  <div class="card">
                    <div class="card-body">
                      <div class="col my-3">
                          <label for="" class="form-label">1. Kartu Pegawai & NIP Baru BKN <span style="color:rgb(255, 0, 0)">***</span></label>
                          {{-- <input class="form-control" type="file" id="formFile"  accept=".pdf"> --}}
                          <select name="kartu_pegawai_nip_baru_bkn" class="form-select">
                            <option selected>Pilih File Pada Storage Anda</option>
                            @foreach($storage as $data)
                              @if(old('kartu_pegawai_nip_baru_bkn') == $data->path)
                                <option value="{{ $data->path }}" selected>{{ $data->nama }}</option>
                              @else
                                <option value="{{ $data->path }}">{{ $data->nama }}</option>
                              @endif
                            @endforeach
                          </select>
                          @error('kartu_pegawai_nip_baru_bkn')
                            <p class="text-danger"><strong>{{ $message }}</strong></p>
                          @enderror
                      </div> 
                      <div class="col my-3">
                          <label for="inputNanme4" class="form-label">2. SK Pengangkatan Pertama (CPNS) <span style="color:rgb(255, 0, 0)">***</span></label>
                          <select name="sk_cpns" class="form-select">
                            <option selected>Pilih File Pada Storage Anda</option>
                            @foreach($storage as $data)
                              @if(old('sk_cpns') == $data->path)
                                <option value="{{ $data->path }}" selected>{{ $data->nama }}</option>
                              @else
                                <option value="{{ $data->path }}">{{ $data->nama }}</option>
                              @endif
                            @endforeach
                          </select>
                          @error('sk_cpns')
                            <p class="text-danger"><strong>{{ $message }}</strong></p>
                          @enderror
                      </div> 
                      <div class="col my-3">
                          <label for="inputNanme4" class="form-label">3. SK Pangkat Terakhir <span style="color:rgb(255, 0, 0)">***</span></label>
                          <select name="sk_pangkat_terakhir" class="form-select">
                            <option selected>Pilih File Pada Storage Anda</option>
                            @foreach($storage as $data)
                              @if(old('sk_pangkat_terakhir') == $data->path)
                                <option value="{{ $data->path }}" selected>{{ $data->nama }}</option>
                              @else
                                <option value="{{ $data->path }}">{{ $data->nama }}</option>
                              @endif
                            @endforeach
                          </select>
                          @error('sk_pangkat_terakhir')
                            <p class="text-danger"><strong>{{ $message }}</strong></p>
                          @enderror
                      </div> 
                      <div class="col my-3">
                          <label for="inputNanme4" class="form-label">4. SK Jabatan Fungsional Terakhir dan PAK <span style="color:rgb(255, 0, 0)">***</span></label>
                          <select name="sk_jabfung_terakhir_dan_pak" class="form-select">
                            <option selected>Pilih File Pada Storage Anda</option>
                            @foreach($storage as $data)
                              @if(old('sk_jabfung_terakhir_dan_pak') == $data->path)
                                <option value="{{ $data->path }}" selected>{{ $data->nama }}</option>
                              @else
                                <option value="{{ $data->path }}">{{ $data->nama }}</option>
                              @endif
                            @endforeach
                          </select>
                          @error('sk_jabfung_terakhir_dan_pak')
                            <p class="text-danger"><strong>{{ $message }}</strong></p>
                          @enderror
                      </div> 
                      <div class="col my-3">
                          <label for="inputNanme4" class="form-label">5. PPK & SKP dua tahun terakhir <span style="color:rgb(255, 0, 0)">***</span></label>
                          <select name="ppk_dan_skp" class="form-select">
                            <option selected>Pilih File Pada Storage Anda</option>
                            @foreach($storage as $data)
                              @if(old('ppk_dan_skp') == $data->path)
                                <option value="{{ $data->path }}" selected>{{ $data->nama }}</option>
                              @else
                                <option value="{{ $data->path }}">{{ $data->nama }}</option>
                              @endif
                            @endforeach
                          </select>
                          @error('ppk_dan_skp')
                            <p class="text-danger"><strong>{{ $message }}</strong></p>
                          @enderror
                      </div> 
                    </div>
                  </div>
                </div> <!-- End-col-lg6 -->

                {{-- <div class="col-lg-2">
                  <p class="text-center">
                  </p>
                </div>  --}}
                <!-- Middle -->

                <div class="col-lg-6">
                  <div class="card">
                    <div class="card-body">
                        <div class="col my-3">
                            <label for="inputNanme4" class="form-label">6. Ijazah Terakhir (jika gelar belum tercantum dalam SK Pangkat Terakhir</label>
                            <select name="ijazah_terakhir" class="form-select">
                              <option value="null" selected>Pilih File Pada Storage Anda</option>
                              @foreach($storage as $data)
                                @if(old('ijazah_terakhir') == $data->path)
                                  <option value="{{ $data->path }}" selected>{{ $data->nama }}</option>
                                @else
                                  <option value="{{ $data->path }}">{{ $data->nama }}</option>
                                @endif
                              @endforeach
                            </select>
                            @error('ijazah_terakhir')
                              <p class="text-danger"><strong>{{ $message }}</strong></p>
                            @enderror
                        </div> 
                        <div class="col my-3">
                            <label for="inputNanme4" class="form-label">7. SK Tugas Belajar atau Surat Izin Studi (sesuai no.5) <span style="color:rgb(255, 0, 0)">***</span></label>
                            <select name="sk_tugas_belajar_atau_surat_izin_studi" class="form-select">
                              <option selected>Pilih File Pada Storage Anda</option>
                              @foreach($storage as $data)
                                @if(old('sk_tugas_belajar_atau_surat_izin_studi') == $data->path)
                                  <option value="{{ $data->path }}" selected>{{ $data->nama }}</option>
                                @else
                                  <option value="{{ $data->path }}">{{ $data->nama }}</option>
                                @endif
                              @endforeach
                            </select>
                            @error('sk_tugas_belajar_atau_surat_izin_studi')
                              <p class="text-danger"><strong>{{ $message }}</strong></p>
                            @enderror
                        </div> 
                        <div class="col my-3">
                            <label for="inputNanme4" class="form-label">Keterangan Membina Mata Kuliah dari Jurusan <span style="color:rgb(255, 0, 0)">***</span></label>
                            <select name="keterangan_membina_mata_kuliah_dari_jurusan" class="form-select">
                              <option selected>Pilih File Pada Storage Anda</option>
                              @foreach($storage as $data)
                                @if(old('keterangan_membina_mata_kuliah_dari_jurusan') == $data->path)
                                  <option value="{{ $data->path }}" selected>{{ $data->nama }}</option>
                                @else
                                  <option value="{{ $data->path }}">{{ $data->nama }}</option>
                                @endif
                              @endforeach
                            </select>
                            @error('keterangan_membina_mata_kuliah_dari_jurusan')
                              <p class="text-danger"><strong>{{ $message }}</strong></p>
                            @enderror
                        </div> 
                        <div class="col my-3">
                            <label for="inputNanme4" class="form-label">Surat Pernyataan Setiap Bidang Tridharma (beserta bukti pendukung) <span style="color:rgb(255, 0, 0)">***</span></label>
                            <select name="surat_pernyataan_setiap_bidang_tridharma" class="form-select">
                              <option selected>Pilih File Pada Storage Anda</option>
                              @foreach($storage as $data)
                                @if(old('surat_pernyataan_setiap_bidang_tridharma') == $data->path)
                                  <option value="{{ $data->path }}" selected>{{ $data->nama }}</option>
                                @else
                                  <option value="{{ $data->path }}">{{ $data->nama }}</option>
                                @endif
                              @endforeach
                            </select>
                            @error('surat_pernyataan_setiap_bidang_tridharma')
                              <p class="text-danger"><strong>{{ $message }}</strong></p>
                            @enderror
                        </div> 
                      </div>
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