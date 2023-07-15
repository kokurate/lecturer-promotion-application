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
              <h2 class="text-center" style="color:#012970;"><strong>Penelitian</strong></h2>
              
              <div class="text-center mt-3">
                <a href="#" class="btn btn-primary" onclick="clickURL()">Click disini jika terjadi error</a>
              </div>
            
              
                
            <!-- Vertical Form -->
            <form class="row g-3 my-3" action="{{ route('penelitian_tambah_store') }}" method="post" enctype="multipart/form-data">
              @csrf
                    <div class="col-lg-6">
                        {{-- <div class="card">
                            <div class="card-body"> --}}

                                <div class="col my-3">
                                    <label for="inputNanme4" class="form-label" style="color:#012970;"><strong>Tipe Kegiatan</strong></label>
                                    <select id="tipe_kegiatan" name="tipe_kegiatan" class="form-select">
                                        {{-- <option selected>Pilih Tipe Kegiatan</option> --}}
                                        <option value="default" selected>Pilih Tipe Kegiatan</option>
                                        @foreach($tipe_kegiatan as $data)
                                            @if(old('tipe_kegiatan') == $data->nama_kegiatan)
                                                <option value="{{ $data->nama_kegiatan }}" selected>{{ $data->nama_kegiatan }}</option>
                                            @else
                                                <option value="{{ $data->nama_kegiatan }}">{{ $data->nama_kegiatan }}</option>
                                            @endif
                                        @endforeach
                                    </select>

                                    <!-- Hidden input field -->
                                    <input type="hidden" id="tipe_kegiatan_id" name="tipe_kegiatan_id">

                                        @error('tipe_kegiatan')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                </div> 
                                
                            
                                <div class="col-my-3">
                                    <label for="kegiatan" class="mt-3 form-label" style="color:#012970;"><strong>Kegiatan/ Judul Karya Ilmiah</strong></label>
                                    <input class="form-control{{ $errors->has('kegiatan') ? ' is-invalid' : '' }}" type="text" name="kegiatan" value="{{ old('kegiatan') }}" required>
                                    
                                    @if ($errors->has('kegiatan'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('kegiatan') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-my-3">
                                    <label for="angka_kredit" class="mt-3 form-label" style="color:#012970;"><strong>Angka Kredit</strong></label>
                                    <input id="angka_kredit" class="form-control{{ $errors->has('angka_kredit') ? ' is-invalid' : '' }}" type="text" name="angka_kredit" value="{{ old('angka_kredit') }}" pattern="[0-9.]*" oninput="this.value = this.value.replace(/,/g, '')" placeholder="" required title="Hanya Angka Yang Diperbolehkan">
                                    
                                    @if ($errors->has('angka_kredit'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('angka_kredit') }}
                                        </div>
                                    @endif
                                </div>

                                

                                <div class="col my-3">
                                    <label for="inputNanme6" class="form-label" style="color:#012970;"><strong>T.A</strong></label>
                                    <input class="form-control" type="text" name="tahun" value="{{ $t_a }}" disabled>
                                </div>  
                                    <!-- For tahun ajaran -->
                                    <input type="hidden" name="tahun_ajaran_id" value="{{ $tahun_ajaran_hidden->id }}">
                                <div class="col my-3">
                                    <label for="inputNanme6" class="form-label" style="color:#012970;"><strong>Semester</strong></label>                                    
                                    <input class="form-control" type="text" name="semester" value="{{ $semester }}" disabled>

                                </div> 

                                <div class="col my-3">
                                    <label for="inputNanme4" class="form-label" style="color:#012970;"><strong>Bukti</strong></label>
                                    <input class="form-control{{ $errors->has('bukti') ? ' is-invalid' : '' }}" type="file" id="pdf_file" name="bukti" accept=".pdf">
                                    @if ($errors->has('bukti'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('bukti') }}
                                        </div>
                                    @endif

                                    <div id="preview" class="my-2 text-center"></div>
                                </div>
                                    


                            {{-- </div> <!-- End Card Body-->
                        </div> <!-- End Card --> --}}
                    </div> <!-- End-col-lg6 -->

                {{-- <div class="col-lg-2">
                  <p class="text-center">
                  </p>
                </div>  --}}
                <!-- Middle -->

                    <div class="col-lg-6">
                      
                        @error('perkuliahan')
                            <p class="text-danger"><strong>{{ $message }}</strong></p>
                            <hr>
                        @enderror
                        @error('buat_error')
                        <p class="text-danger"><strong>{{ $message }}</strong></p>
                        <hr>
                    @enderror
                       
                        {{-- <div class="card">
                            <div class="card-body"> --}}
                                
                                <!-- Input fields that will be shown/hidden based on the selected option -->
                                <!-- ================== Here's the display none ============== -->

                                <!-- Default -->
                                <div class="col my-3">
                                    <div id="default" style="display: none;">
                                        <h4 class="text-center" style="color:#012970;">Komponen Tambahan</h4>
                                        <h4 class="text-center" style="color:#012970;">Akan Tampil disini</h4>
                                    </div>
                                </div>

                                <!-- 1 -->
                                <!-- Menghasilkan karya ilmiah sesuai dengan bidang ilmunya -->
                                <div id="inputField1" style="display: none;">
                                    <!-- Pertama Keluar yang Radio -->
                                        <div class="col my-3">
                                            <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
                                            <br>

                                            <div class="list-group">
                                                <div class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="a_hasil_penelitian" type="radio" name="komponen_kegiatan"  value="Hasil Penelitian Atau Hasil Dipublikasikan Dalam Bentuk Buku" onclick="showInputHasilPenelitian('a')"> 
                                                        <label class="form-check-label" for="a_hasil_penelitian">Hasil Penelitian Atau Hasil Dipublikasikan Dalam Bentuk Buku</label>
                                                    </div>
                                                </div>
                                                <div class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="b_hasil_penelitian" type="radio" name="komponen_kegiatan" value="Hasil Penelitian Atau Hasil Pemikiran Dalam Buku Yang Dipublikasikan Dan Berisi Berbagai Tulisan Dari Berbagai Penulis (Book Chapter)" onclick="showInputHasilPenelitian('b')"> 
                                                        <label class="form-check-label" for="b_hasil_penelitian">Hasil Penelitian Atau Hasil Pemikiran Dalam Buku Yang Dipublikasikan Dan Berisi Berbagai Tulisan Dari Berbagai Penulis (Book Chapter)</label>
                                                    </div>
                                                </div>
                                                <div class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="c_hasil_penelitian" type="radio" name="komponen_kegiatan" value="Hasil Penelitian Atau Hasil Pemikiran Yang Dipublikasikan" onclick="showInputHasilPenelitian('c')"> 
                                                        <label class="form-check-label" for="c_hasil_penelitian">Hasil Penelitian Atau Hasil Pemikiran Yang Dipublikasikan</label>
                                                    </div>
                                                </div>
                                            </div>
                                                
                                                <br>
                                                
                                                <!-- Radio 1 -->
                                                <div id="a_hasil_penelitian_radio" style="display: none;">
                                                    <p class="mt-2 mb-3 text-center" style="color:#012970;"><strong>Pilih 1 poin yang ada di bawah ini</strong></p>
                                                    <div class="list-group">
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="buku_refrensi" type="radio" name="dalam_bentuk_buku" value="II.A.1.a.1" onclick="updateHiddenInputValue('buku_refrensi')"> 
                                                                <label class="form-check-label" for="buku_refrensi">Buku Referensi</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Buku Referensi" id="hidden_buku_refrensi"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="monograf"type="radio" name="dalam_bentuk_buku" value="II.A.1.a.2" onclick="updateHiddenInputValue('monograf')"> 
                                                                <label class="form-check-label" for="monograf">Monograf</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Monograf" id="hidden_monograf"> --}}
                                                            </div>
                                                        </div>
                                                    </div> <!-- END list-group -->
                                                </div>
                                                
                                                <!-- Radio 2 -->
                                                <div id="b_hasil_penelitian_radio" style="display: none;">
                                                    <p class="mt-2 mb-3 text-center" style="color:#012970;"><strong>Pilih 1 poin yang ada di bawah ini</strong></p>
                                                    <div class="list-group">
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Internasional" id="hidden_internasional"> --}}
                                                                <input class="form-check-input" id="internasional" type="radio" name="book_chapter" value="II.A.1.a.2.1" onclick="updateHiddenInputValue('internasional')"> 
                                                                <label class="form-check-label" for="internasional">Internasional</label>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Nasional" id="hidden_nasional"> --}}
                                                                <input class="form-check-input" id="nasional"type="radio" name="book_chapter" value="II.A.1.a.2.2" onclick="updateHiddenInputValue('nasional')"> 
                                                                <label class="form-check-label" for="nasional">Nasional</label>
                                                            </div>
                                                        </div>
                                                    </div> <!-- END list-group -->
                                                    
                                                </div> <!-- End Radio 2 -->

                                                <!-- Radio 3 -->
                                                <div id="c_hasil_penelitian_radio" style="display: none;">
                                                    <p class="mt-2 mb-3 text-center" style="color:#012970;"><strong>Pilih 1 poin yang ada di bawah ini</strong></p>
                                                    <div class="list-group">
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Jurnal Internasional Bereputasi (Terindek Pada Database Internasional Bereputasi Dan Berfakrot Dampak)" id="hidden_radio3_1"> --}}
                                                                <input class="form-check-input" id="radio3_1" type="radio" name="c_jurnal" value="II.A.1.b.1.1" onclick="updateHiddenInputValue('radio3_1')"> 
                                                                <label class="form-check-label" for="radio3_1">Jurnal Internasional Bereputasi (Terindek Pada Database Internasional Bereputasi Dan Berfakrot Dampak)</label>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Jurnal Internasional Terindek Pada Database Internasional Bereputasi" id="hidden_radio3_2"> --}}
                                                                <input class="form-check-input" id="radio3_2"type="radio" name="c_jurnal" value="II.A.1.b.1.2" onclick="updateHiddenInputValue('radio3_2')"> 
                                                                <label class="form-check-label" for="radio3_2">Jurnal Internasional Terindek Pada Database Internasional Bereputasi</label>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Jurnal internasional terindeks pada database internasional di luar kategori 2" id="hidden_radio3_3"> --}}
                                                                <input class="form-check-input" id="radio3_3"type="radio" name="c_jurnal" value="II.A.1.b.1.3" onclick="updateHiddenInputValue('radio3_3')"> 
                                                                <label class="form-check-label" for="radio3_3">Jurnal internasional terindeks pada database internasional di luar kategori 2</label>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Jurnal Nasional Terakreditasi" id="hidden_radio3_4"> --}}
                                                                <input class="form-check-input" id="radio3_4"type="radio" name="c_jurnal" value="II.A.1.b.2" onclick="updateHiddenInputValue('radio3_4')"> 
                                                                <label class="form-check-label" for="radio3_4">Jurnal Nasional Terakreditasi</label>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Jurnal Nasional Berbahasa Indonesia Terindek Pada DOAJ" id="hidden_radio3_5a"> --}}
                                                                <input class="form-check-input" id="radio3_5a"type="radio" name="c_jurnal" value="II.A.1.b.2.1" onclick="updateHiddenInputValue('radio3_5a')"> 
                                                                <label class="form-check-label" for="radio3_5a">Jurnal Nasional Berbahasa Indonesia Terindek Pada DOAJ</label>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Jurnal Nasional Berbahasa Inggris Atau Bahasa Resmi (PBB) Terindek Pada DOAJ" id="hidden_radio3_5b"> --}}
                                                                <input class="form-check-input" id="radio3_5b"type="radio" name="c_jurnal" value="II.A.1.b.2.2" onclick="updateHiddenInputValue('radio3_5b')"> 
                                                                <label class="form-check-label" for="radio3_5b">Jurnal Nasional Berbahasa Inggris Atau Bahasa Resmi (PBB) Terindek Pada DOAJ</label>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Jurnal Nasional" id="hidden_radio3_6"> --}}
                                                                <input class="form-check-input" id="radio3_6"type="radio" name="c_jurnal" value="II.A.1.b.3" onclick="updateHiddenInputValue('radio3_6')"> 
                                                                <label class="form-check-label" for="radio3_6">Jurnal Nasional</label>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Jurnal Ilmiah Yang Ditulis Dalam Bahasa Resmi PBB Namun Tidak Memenuhi Syarat-Syarat Sebagai Jurnal Ilmiah Internasional" id="hidden_radio3_7"> --}}
                                                                <input class="form-check-input" id="radio3_7"type="radio" name="c_jurnal" value="II.A.1.b.3.1" onclick="updateHiddenInputValue('radio3_7')"> 
                                                                <label class="form-check-label" for="radio3_7">Jurnal Ilmiah Yang Ditulis Dalam Bahasa Resmi PBB Namun Tidak Memenuhi Syarat-Syarat Sebagai Jurnal Ilmiah Internasional</label>
                                                            </div>
                                                        </div>
                                                    </div> <!-- END list-group -->
                                                </div> <!-- End Radio 3 -->
                                        </div> <!-- End Col -->
                                </div> <!-- inputField1 -->

                                <!-- 2 -->
                                <!-- Hasil penelitian atau hasil pemikiran yang didesiminasikan -->
                                <div id="inputField2" style="display: none;">
                                        <!-- Pertama Keluar yang Radio -->
                                        <div class="col my-3">
                                            <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
                                            <br>

                                            <div class="list-group">
                                                <div class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="a_hasil_pemikiran_yang_didesiminasikan" type="radio" name="komponen_kegiatan"  value="Dipresentasikan Secara Oral Dan Dimuat Dalam Prosiding Yang Dipublikasikan (Ber ISSN/ISBN)" onclick="showInputHasilPemikiranYangDidesiminasikan('a')"> 
                                                        <label class="form-check-label" for="a_hasil_pemikiran_yang_didesiminasikan">Dipresentasikan Secara Oral Dan Dimuat Dalam Prosiding Yang Dipublikasikan (Ber ISSN/ISBN)</label>
                                                    </div>
                                                </div>
                                                <div class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="b_hasil_pemikiran_yang_didesiminasikan" type="radio" name="komponen_kegiatan" value="Disajikan Dalam Bentuk Poster Dan Dimuat Dalam Prosiding Yang Dipublikasikan" onclick="showInputHasilPemikiranYangDidesiminasikan('b')"> 
                                                        <label class="form-check-label" for="b_hasil_pemikiran_yang_didesiminasikan">Disajikan Dalam Bentuk Poster Dan Dimuat Dalam Prosiding Yang Dipublikasikan</label>
                                                    </div>
                                                </div>
                                                <div class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="c_hasil_pemikiran_yang_didesiminasikan" type="radio" name="komponen_kegiatan" value="Disajikan Dalam Seminar/ Simposium/ Lokakarya, Tetapi Tidak Dimuat Dalam Posiding Yang Dipublikasikan" onclick="showInputHasilPemikiranYangDidesiminasikan('c')"> 
                                                        <label class="form-check-label" for="c_hasil_pemikiran_yang_didesiminasikan">Disajikan Dalam Seminar/ Simposium/ Lokakarya, Tetapi Tidak Dimuat Dalam Posiding Yang Dipublikasikan</label>
                                                    </div>
                                                </div>
                                                <div class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="d_hasil_pemikiran_yang_didesiminasikan" type="radio" name="komponen_kegiatan" value="Hasil Penelitian/ Pemikiran Yang Tidak Disajikan Dalam Seminar/ Dimposium/ Lokakarya, Tetapi Dimuat Dalam Prosiding" onclick="showInputHasilPemikiranYangDidesiminasikan('d')"> 
                                                        <label class="form-check-label" for="d_hasil_pemikiran_yang_didesiminasikan">Hasil Penelitian/ Pemikiran Yang Tidak Disajikan Dalam Seminar/ Dimposium/ Lokakarya, Tetapi Dimuat Dalam Prosiding</label>
                                                    </div>
                                                </div>
                                                <div class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="e_hasil_pemikiran_yang_didesiminasikan" type="radio" name="komponen_kegiatan" value="Hasil Penelitian/ Pemikiran/ Yang Disajikan Dalam Koran/ Majalah Populer/ Umum" onclick="showInputHasilPemikiranYangDidesiminasikan('e')"> 
                                                        <label class="form-check-label" for="e_hasil_pemikiran_yang_didesiminasikan">Hasil Penelitian/ Pemikiran/ Yang Disajikan Dalam Koran/ Majalah Populer/ Umum</label>
                                                    </div>
                                                </div>
                                            </div>
                                                
                                                <br>
                                                
                                                <!-- Radio 1 -->
                                                <div id="a_hasil_pemikiran_yang_didesiminasikan_radio" style="display: none;">
                                                    <p class="mt-2 mb-3 text-center" style="color:#012970;"><strong>Nilai A</strong></p>
                                                    <div class="list-group">
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="oral_internasional" type="radio" name="dipresentasikan_secara_oral" value="II.A.1.c.1.a.1" > 
                                                                <label class="form-check-label" for="oral_internasional">Internasional</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Internasional"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="oral_nasional"type="radio" name="dipresentasikan_secara_oral" value="II.A.1.c.2.b"> 
                                                                <label class="form-check-label" for="oral_nasional">Nasional</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Nasional"> --}}
                                                            </div>
                                                        </div>
                                                    </div> <!-- END list-group -->
                                                </div>
                                                
                                                <!-- Radio 2 -->
                                                <div id="b_hasil_pemikiran_yang_didesiminasikan_radio" style="display: none;">
                                                    <p class="mt-2 mb-3 text-center" style="color:#012970;"><strong>Nilai B</strong></p>
                                                    <div class="list-group">
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Internasional"> --}}
                                                                <input class="form-check-input" id="dalam_bentuk_poster_internasional" type="radio" name="disajikan_dalam_bentuk_poster" value="II.A.1.c.2.a"> 
                                                                <label class="form-check-label" for="dalam_bentuk_poster_internasional">Internasional</label>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Nasional"> --}}
                                                                <input class="form-check-input" id="dalam_bentuk_poster_nasional"type="radio" name="disajikan_dalam_bentuk_poster" value="II.A.1.c.1.b.1"> 
                                                                <label class="form-check-label" for="dalam_bentuk_poster_nasional">Nasional</label>
                                                            </div>
                                                        </div>
                                                    </div> <!-- END list-group -->
                                                    
                                                </div> <!-- End Radio 2 -->

                                                <!-- Radio 3 -->
                                                <div id="c_hasil_pemikiran_yang_didesiminasikan_radio" style="display: none;">
                                                    <p class="mt-2 mb-3 text-center" style="color:#012970;"><strong>Nilai C</strong></p>
                                                    <div class="list-group">
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Internasional"> --}}
                                                                <input class="form-check-input" id="c_hpydr_1_radio3" type="radio" name="disajikan_dalam_seminar" value="II.A.1.c.1.a"> 
                                                                <label class="form-check-label" for="c_hpydr_1_radio3">Internasional</label>
                                                                <!-- Ambil nama id radio 3 -->
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Nasional"> --}}
                                                                <input class="form-check-input" id="c_hpydr_2_radio3"type="radio" name="disajikan_dalam_seminar" value="II.A.1.c.1.b"> 
                                                                <label class="form-check-label" for="c_hpydr_2_radio3">Nasional</label>
                                                            </div>
                                                        </div>
                                                    </div> <!-- END list-group -->
                                                </div> <!-- End Radio 3 -->

                                                <!-- Radio 4 -->
                                                <div id="d_hasil_pemikiran_yang_didesiminasikan_radio" style="display: none;">
                                                    <p class="mt-2 mb-3 text-center" style="color:#012970;"><strong>Nilai D</strong></p>
                                                    <div class="list-group">
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Internasional"> --}}
                                                                <input class="form-check-input" id="d_hpydr_1_radio3" type="radio" name="tidak_disajikan_dalam_seminar" value="II.A.1.c.3.a"> 
                                                                <label class="form-check-label" for="d_hpydr_1_radio3">Internasional</label>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Nasional"> --}}
                                                                <input class="form-check-input" id="d_hpydr_2_radio3"type="radio" name="tidak_disajikan_dalam_seminar" value="II.A.1.c.3.b"> 
                                                                <label class="form-check-label" for="d_hpydr_2_radio3">Nasional</label>
                                                            </div>
                                                        </div>
                                                    </div> <!-- END list-group -->
                                                    
                                                </div> <!-- End Radio 4 -->



                                        </div> <!-- End Col -->
                                </div> <!-- inputField2 -->

                                <!-- 3 -->
                                <!-- Hasil penelitian atau pemikiran atau kerjasama industri yang tidak dipublikasikan (tersimpan dalam perpustakaan) -->
                                <div id="inputField3" style="display: none;">
                                    <p class="text-center" style="color:#ff0000;" ><strong>Dipilih</strong></p>
                                    <p class="text-center" style="color:#012970;">
                                        <strong>
                                            Hasil penelitian atau pemikiran atau kerjasama industri yang tidak dipublikasikan (tersimpan dalam perpustakaan) 
                                        </strong>
                                    </p>
                                </div>

                                <!-- 4 -->
                                <!-- Menerjemahkan/ Menyadur Buku Ilmiah Yang Diterbitkan (Ber ISBN) -->
                                <div id="inputField4" style="display: none;">
                                    <p class="text-center" style="color:#ff0000;" ><strong>Dipilih</strong></p>
                                    <p class="text-center" style="color:#012970;">
                                        <strong>
                                            Menerjemahkan/ Menyadur Buku Ilmiah Yang Diterbitkan (Ber ISBN)
                                        </strong>
                                    </p>
                                </div> <!-- Input Field4-->

                                <!-- 5 -->
                                <!-- Mengedit/ Menyunting Karya Ilmiah Dalam Bentuk Buku Yang Diterbitkan (Ber ISBN) -->
                                <div id="inputField5" style="display: none;">
                                    <p class="text-center" style="color:#ff0000;" ><strong>Dipilih</strong></p>
                                    <p class="text-center" style="color:#012970;">
                                        <strong>
                                            Mengedit/ Menyunting Karya Ilmiah Dalam Bentuk Buku Yang Diterbitkan (Ber ISBN)
                                        </strong>
                                    </p>
                                </div> <!-- inputField5 -->

                                <!-- 6 -->
                                <!-- Membuat Rancangan Dan Karya Teknologi/ Seni Yang Dipatenkan Secara Nasional Atau Internasional -->
                                <div id="inputField6" style="display: none;">
                                    <div class="col my-3">
                                        <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
                                        <br>

                                        <div class="list-group">
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="a_6" type="radio" name="komponen_kegiatan"  value="Internasional (Paling Sedikit Diakui Oleh 4 Negara)"> 
                                                    <label class="form-check-label" for="a_6">Internasional (Paling Sedikit Diakui Oleh 4 Negara)</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="b_6" type="radio" name="komponen_kegiatan" value="Nasional"> 
                                                    <label class="form-check-label" for="b_6">Nasional</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- inputField6 -->

                                <!-- 7 -->
                                <!-- Membuat Rancangan Dan Karya Teknologi Yang Tidak Dipatenkan; Rancangan Dan Karya Seni Monumental/ Seni Pertunjukan; Karya Sastra -->
                                <div id="inputField7" style="display: none;">
                                    <div class="col my-3">
                                        <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
                                        <br>

                                        <div class="list-group">
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="a_7" type="radio" name="komponen_kegiatan"  value="Tingkat Internasional"> 
                                                    <label class="form-check-label" for="a_7">Tingkat Internasional</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="b_7" type="radio" name="komponen_kegiatan" value="Tingkat Nasional"> 
                                                    <label class="form-check-label" for="b_7">Tingkat Nasional</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="c_7" type="radio" name="komponen_kegiatan" value="Tingkat Lokal"> 
                                                    <label class="form-check-label" for="c_7">Tingkat Lokal</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- InputField7 -->

                                <!-- 8 -->
                                <!-- Membuat Rancangan Dan Karya Seni/ Seni Pertunjukan Yang Tidak Mendapatkan HKI -->
                                <div id="inputField8" style="display: none;">
                                    <p class="text-center" style="color:#ff0000;" ><strong>Dipilih</strong></p>
                                    <p class="text-center" style="color:#012970;">
                                        <strong>
                                            Membuat Rancangan Dan Karya Seni/ Seni Pertunjukan Yang Tidak Mendapatkan HKI
                                        </strong>
                                    </p>
                                </div>

                                
                            

                            {{-- </div> <!-- End Card Body -->
                        </div> <!-- End Card -->  --}}
                    </div>

                <div class="col-lg text-center">
                    <a href="{{ route('penelitian') }}" class="my-2 btn btn-lg btn-warning rounded-pill" style="color:#012970;padding-left: 50px; padding-right: 50px;">Kembali</a>
                    <button type="submit" class="my-2 btn btn-primary btn-lg rounded-pill" style="background-color:#012970; color:#ffffff;padding-left: 50px; padding-right: 50px;">Tambah</button>
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

    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   
        <script>

            // sanitized input
            const inputField = document.getElementById('angka_kredit');

            inputField.addEventListener('input', function(e) {
                const inputValue = e.target.value;
                const sanitizedValue = inputValue.replace(/[^0-9.,]/g, ''); // Remove any characters other than numbers, dots, and commas
                e.target.value = sanitizedValue;
            });


            // JavaScript code to handle the select change event -->
            $(document).ready(function() {
                // $('#optionSelect').change(function() {
                $('select[name="tipe_kegiatan"]').change(function() {
                    // Get the selected option value
                    var selectedOption = $(this).val();

                    // Hide all input fields
                    $('#default').hide();
                    $('#inputField1').hide();
                    $('#inputField2').hide();
                    $('#inputField3').hide();
                    $('#inputField4').hide();
                    $('#inputField5').hide();
                    $('#inputField6').hide();
                    $('#inputField7').hide();
                    $('#inputField8').hide();

                    // Show the input field based on the selected option
                    if (selectedOption == 'default') {
                        $('#default').show();
                    } else if (selectedOption == 'Menghasilkan Karya Ilmiah Sesuai Dengan Bidang Ilmunya') {
                        $('#inputField1').show();
                    } else if (selectedOption == 'Hasil Penelitian Atau Hasil Pemikiran Yang Didesiminasikan') {
                        $('#inputField2').show();
                    } else if (selectedOption == 'Hasil Penelitian Atau Pemikiran Atau Kerjasama Industri Yang Tidak Dipublikasikan (Tersimpan Dalam Perpustakaan)'){
                        $('#inputField3').show();
                    } else if (selectedOption == 'Menerjemahkan/ Menyadur Buku Ilmiah Yang Diterbitkan (Ber ISBN)') {
                        $('#inputField4').show();
                    } else if (selectedOption == 'Mengedit/ Menyunting Karya Ilmiah Dalam Bentuk Buku Yang Diterbitkan (Ber ISBN)') {
                        $('#inputField5').show();
                    } else if (selectedOption == 'Membuat Rancangan Dan Karya Teknologi/ Seni Yang Dipatenkan Secara Nasional Atau Internasional') {
                        $('#inputField6').show();
                    } else if (selectedOption == 'Membuat Rancangan Dan Karya Teknologi Yang Tidak Dipatenkan; Rancangan Dan Karya Seni Monumental/ Seni Pertunjukan; Karya Sastra') {
                        $('#inputField7').show();
                    } else if (selectedOption == 'Membuat Rancangan Dan Karya Seni/ Seni Pertunjukan Yang Tidak Mendapatkan HKI') {
                        $('#inputField8').show();
                    }

                });
            });
        </script>

        <!-- ======= Radio show Hasil Penelitian  ======= -->
            <script>
                function showInputHasilPenelitian(option) {
                    
                    // var perkuliahanInput = document.getElementById("inputPerkuliahan");

                    // if (option === "a") {
                    //     perkuliahanInput.setAttribute("required", "required");
                    // } else {
                    //     perkuliahanInput.removeAttribute("required");
                    // }

                    if (option === 'a') {
                        document.getElementById('a_hasil_penelitian_radio').style.display = 'block';
                        document.getElementById('b_hasil_penelitian_radio').style.display = 'none';
                        document.getElementById('c_hasil_penelitian_radio').style.display = 'none';
                    } else if (option === 'b') {
                        document.getElementById('a_hasil_penelitian_radio').style.display = 'none';
                        document.getElementById('b_hasil_penelitian_radio').style.display = 'block';
                        document.getElementById('c_hasil_penelitian_radio').style.display = 'none';
                    } else if (option === 'c') {
                        document.getElementById('a_hasil_penelitian_radio').style.display = 'none';
                        document.getElementById('b_hasil_penelitian_radio').style.display = 'none';
                        document.getElementById('c_hasil_penelitian_radio').style.display = 'block';
                    }
                }

                // Js to handle the selected input hidden value
                // function updateHiddenInputValue(hiddenInputId) {
                //     // Get the selected radio button value
                //     const selectedValue = document.querySelector('input[name="kode"]:checked').value;

                //     // Update the hidden input value
                //     document.getElementById('hidden_' + hiddenInputId).value = selectedValue;
                // }

                // function updateHiddenInputValue(hiddenInputId) {
                // // Get the selected radio button within the corresponding radio button group
                //     const radioButtons = document.getElementsByName('kode');
                //     const selectedRadioButton = Array.from(radioButtons).find(radio => radio.checked);

                // // Get the selected value from the radio button or set it to an empty string if no radio button is selected
                //     const selectedValue = selectedRadioButton ? selectedRadioButton.value : '';

                // // Update the hidden input value
                //     document.getElementById('hidden_' + hiddenInputId).value = selectedValue;
                // }


                function showInputHasilPemikiranYangDidesiminasikan(option) {
                    

                    if (option === 'a') {
                        document.getElementById('a_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'block';
                        document.getElementById('b_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'none';
                        document.getElementById('c_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'none';
                        document.getElementById('d_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'none';
                    } else if (option === 'b') {
                        document.getElementById('a_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'none';
                        document.getElementById('b_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'block';
                        document.getElementById('c_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'none';
                        document.getElementById('d_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'none';
                    } else if (option === 'c') {
                        document.getElementById('a_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'none';
                        document.getElementById('b_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'none';
                        document.getElementById('c_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'block';
                        document.getElementById('d_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'none';
                    } else if (option === 'd') {
                        document.getElementById('a_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'none';
                        document.getElementById('b_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'none';
                        document.getElementById('c_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'none';
                        document.getElementById('d_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'block';
                    } else if (option === 'e') {
                        document.getElementById('a_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'none';
                        document.getElementById('b_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'none';
                        document.getElementById('c_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'none';
                        document.getElementById('d_hasil_pemikiran_yang_didesiminasikan_radio').style.display = 'none';
                    }
                }

                // Input Hidden for ID
                    // JavaScript code to update the hidden input value
                // const selectElement = document.querySelector('select[name="tipe_kegiatan"]');
                // const hiddenInput = document.getElementById('tipe_kegiatan_id');

                // selectElement.addEventListener('change', function() {
                //     const selectedOption = selectElement.options[selectElement.selectedIndex];
                //     const selectedId = selectedOption.value;
                //     hiddenInput.value = selectedId;
                // });


                // JavaScript code to update the hidden input value
                // const selectElement = document.getElementById('tipe_kegiatan');
                // const hiddenInput = document.getElementById('tipe_kegiatan_id');

                // selectElement.addEventListener('change', function() {
                //     const selectedOption = selectElement.options[selectElement.selectedIndex];
                //     const selectedId = selectedOption.value;
                //     hiddenInput.value = selectedId;
                // });


                const selectElement = document.querySelector('select[name="tipe_kegiatan"]');
                const hiddenInput = document.getElementById('tipe_kegiatan_id');

                selectElement.addEventListener('change', function() {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const selectedName = selectedOption.value;
                
                // Get the index of the selected option
                const selectedIndex = selectedOption.index;
                
                // Set the index as the value for the hidden input
                hiddenInput.value = selectedIndex.toString();

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


    <!-- Refresh Page -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 
    <script>
        function clickURL() {
          var currentURL = window.location.href;
          window.location.href = currentURL;
        }
      </script>
@endpush