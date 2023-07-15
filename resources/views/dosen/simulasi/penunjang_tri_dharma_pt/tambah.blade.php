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
              <h2 class="text-center" style="color:#012970;"><strong>Penunjang Tri Dharma PT</strong></h2>
              
              <div class="text-center mt-3">
                <a href="#" class="btn btn-primary" onclick="clickURL()">Click disini jika terjadi error</a>
              </div>
            
              
                
            <!-- Vertical Form -->
            <form class="row g-3 my-3" action="{{ route('penunjang_tri_dharma_pt_tambah_store') }}" method="post" enctype="multipart/form-data">
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
                                    <label for="kegiatan" class="mt-3 form-label" style="color:#012970;"><strong>Kegiatan</strong></label>
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


                                <div class="col-my-3">
                                    <label for="tempat" class="mt-3 form-label" style="color:#012970;"><strong>Tempat/ Instansi</strong></label>
                                    <input id="tempat" class="form-control{{ $errors->has('tempat') ? ' is-invalid' : '' }}" type="text" name="tempat" value="{{ old('tempat') }}"  required>
                                    
                                    @if ($errors->has('tempat'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('tempat') }}
                                        </div>
                                    @endif
                                </div>

                                
                                <div class="col-my-3">
                                    <label for="tanggal_pelaksanaan" class="mt-3 form-label" style="color:#012970;"><strong>Tanggal Pelaksanaan</strong></label>
                                    <input type="date" id="tanggal_pelaksanaan" class="form-control{{ $errors->has('tanggal_pelaksanaan') ? ' is-invalid' : '' }}"  name="tanggal_pelaksanaan" value="{{ old('tanggal_pelaksanaan') }}"  required>
                                    
                                    @if ($errors->has('tanggal_pelaksanaan'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('tanggal_pelaksanaan') }}
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
                                <!-- Menjadi Anggota Dalam Suatu Panitia/ Badan Pada Perguruan Tinggi -->
                                <div id="inputField1" style="display: none;">
                                    <!-- Pertama Keluar yang Radio -->
                                        <div class="col my-3">
                                            <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
                                            <br>

                                            <div class="list-group">
                                                <div class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="1_a" type="radio" name="komponen_kegiatan"  value="Sebagai Ketua/ Wakil Ketua Merangkap Anggota, Tiap Tahun"> 
                                                        <label class="form-check-label" for="1_a">Sebagai Ketua/ Wakil Ketua Merangkap Anggota, Tiap Tahun</label>
                                                    </div>
                                                </div>
                                                <div class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="1_b" type="radio" name="komponen_kegiatan" value="Sebagai Anggota, Tiap Tahun"> 
                                                        <label class="form-check-label" for="1_b">Sebagai Anggota, Tiap Tahun</label>
                                                    </div>
                                                </div>
                                            </div>
                                                
                                                
                                        </div> <!-- End Col -->
                                </div> <!-- inputField1 -->

                                <!-- 2 -->
                                <!-- Menjadi Anggota Panitia/ Badan Pada Lembaga Pemerintah -->
                                <div id="inputField2" style="display: none;">
                                        <!-- Pertama Keluar yang Radio -->
                                        <div class="col my-3">
                                            <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
                                            <br>

                                            <div class="list-group">
                                                <div class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="2a" type="radio" name="komponen_kegiatan"  value="Panitia Pusat, Sebagai" onclick="showInput2('a')"> 
                                                        <label class="form-check-label" for="2a">Panitia Pusat, Sebagai</label>
                                                    </div>
                                                </div>
                                                <div class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="2b" type="radio" name="komponen_kegiatan" value="Panitia Daerah, Sebagai" onclick="showInput2('b')"> 
                                                        <label class="form-check-label" for="2b">Panitia Daerah, Sebagai</label>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                                
                                                <br>
                                                
                                                <!-- Radio 1 -->
                                                <div id="2a_radio" style="display: none;">
                                                    <p class="mt-2 mb-3 text-center" style="color:#012970;"><strong>Nilai A</strong></p>
                                                    <div class="list-group">
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="2a_radio_1" type="radio" name="panitia_pusat" value="8.2.a.1" > 
                                                                <label class="form-check-label" for="2a_radio_1">Ketua/ Wakil Ketua, Tiap Kepanitiaan</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Ketua/ Wakil Ketua, Tiap Kepanitiaan"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="2a_radio_2"type="radio" name="panitia_pusat" value="8.2.a.2"> 
                                                                <label class="form-check-label" for="2a_radio_2">Anggota, Tiap Kepanitiaan</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Anggota, Tiap Kepanitiaan"> --}}
                                                            </div>
                                                        </div>
                                                    </div> <!-- END list-group -->
                                                </div>
                                                
                                                <!-- Radio 2 -->
                                                <div id="2b_radio" style="display: none;">
                                                    <p class="mt-2 mb-3 text-center" style="color:#012970;"><strong>Nilai B</strong></p>
                                                    <div class="list-group">
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Ketua/ Wakil Ketua, Tiap Kepanitiaan"> --}}
                                                                <input class="form-check-input" id="2b_radio_1" type="radio" name="panitia_daerah" value="8.2.b.1"> 
                                                                <label class="form-check-label" for="2b_radio_1">Ketua/ Wakil Ketua, Tiap Kepanitiaan</label>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Anggota, Tiap Kepanitiaan"> --}}
                                                                <input class="form-check-input" id="2b_radio_2"type="radio" name="panitia_daerah" value="8.2.b.2"> 
                                                                <label class="form-check-label" for="2b_radio_2">Anggota, Tiap Kepanitiaan</label>
                                                            </div>
                                                        </div>
                                                    </div> <!-- END list-group -->
                                                    
                                                </div> <!-- End Radio 2 -->

                                        </div> <!-- End Col -->
                                </div> <!-- inputField2 -->

                                <!-- 3 -->
                                <!-- Menjadi Anggota Organisasi Profesi -->
                                <div id="inputField3" style="display: none;">
                                    <!-- Pertama Keluar yang Radio -->
                                        <div class="col my-3">
                                            <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
                                            <br>

                                            <div class="list-group">
                                                <div class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="3a" type="radio" name="komponen_kegiatan"  value="Tingkat Internasional, Sebagai" onclick="showInput3('a')"> 
                                                        <label class="form-check-label" for="3a">Tingkat Internasional, Sebagai</label>
                                                    </div>
                                                </div>
                                                <div class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="3b" type="radio" name="komponen_kegiatan" value="Tingkat Nasional, Sebagai" onclick="showInput3('b')"> 
                                                        <label class="form-check-label" for="3b">Tingkat Nasional, Sebagai</label>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                                
                                                <br>
                                                
                                                <!-- Radio 1 -->
                                                <div id="3a_radio" style="display: none;">
                                                    <p class="mt-2 mb-3 text-center" style="color:#012970;"><strong>Nilai A</strong></p>
                                                    <div class="list-group">
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="3a_radio_1" type="radio" name="tingkat_internasional" value="8.3.a.1" > 
                                                                <label class="form-check-label" for="3a_radio_1">Pengurus, Tiap Periode Jabatan</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Pengurus, Tiap Periode Jabatan"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="3a_radio_2"type="radio" name="tingkat_internasional" value="8.3.a.2"> 
                                                                <label class="form-check-label" for="3a_radio_2">Anggota Atas Permintaan, Tiap Periode Jabatan</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Anggota Atas Permintaan, Tiap Periode Jabatan"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="3a_radio_3"type="radio" name="tingkat_internasional" value="8.3.a.3"> 
                                                                <label class="form-check-label" for="3a_radio_3">Anggota, Tiap Periode Jabatan</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Anggota, Tiap Periode Jabatan"> --}}
                                                            </div>
                                                        </div>
                                                    </div> <!-- END list-group -->
                                                </div>
                                                
                                                <!-- Radio 2 -->
                                                <div id="3b_radio" style="display: none;">
                                                    <p class="mt-2 mb-3 text-center" style="color:#012970;"><strong>Nilai B</strong></p>
                                                    <div class="list-group">
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="3b_radio_1" type="radio" name="tingkat_nasional" value="8.3.b.1" > 
                                                                <label class="form-check-label" for="3b_radio_1">Pengurus, Tiap Periode Jabatan</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Pengurus, Tiap Periode Jabatan"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="3b_radio_2"type="radio" name="tingkat_nasional" value="8.3.b.2"> 
                                                                <label class="form-check-label" for="3b_radio_2">Anggota Atas Permintaan, Tiap Periode Jabatan</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Anggota Atas Permintaan, Tiap Periode Jabatan"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="3b_radio_3"type="radio" name="tingkat_nasional" value="8.3.b.3"> 
                                                                <label class="form-check-label" for="3b_radio_3">Anggota, Tiap Periode Jabatan</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Anggota, Tiap Periode Jabatan"> --}}
                                                            </div>
                                                        </div>

                                                    </div> <!-- END list-group -->
                                                    
                                                </div> <!-- End Radio 2 -->

                                        </div> <!-- End Col -->
                                </div> <!-- End InputField3 -->

                                <!-- 4 -->
                                <!-- Menerjemahkan/ Menyadur Buku Ilmiah Yang Diterbitkan (Ber ISBN) -->
                                <div id="inputField4" style="display: none;">
                                    <p class="text-center" style="color:#ff0000;" ><strong>Dipilih</strong></p>
                                    <p class="text-center" style="color:#012970;">
                                        <strong>
                                            Mewakili Perguruan Tinggi/ Lembaga Pemerintah Duduk Dalam Panitia Antar Lembaga, Tiap Kepanitiaan
                                        </strong>
                                    </p>
                                </div> <!-- Input Field4-->

                                <!-- 5 -->
                                <!-- Menjadi Anggota Delegasi Nasional Ke Pertemuan Internasional -->
                                <div id="inputField5" style="display: none;">
                                    <!-- Pertama Keluar yang Radio -->
                                    <div class="col my-3">
                                        <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
                                        <br>
                                        <div class="list-group">
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="5a" type="radio" name="komponen_kegiatan"  value="Sebagai Ketua Delegasi, Tiap Kegiatan"> 
                                                    <label class="form-check-label" for="5a">Sebagai Ketua Delegasi, Tiap Kegiatan</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="5b" type="radio" name="komponen_kegiatan" value="Sebagai Anggota, Tiap Kegiatan"> 
                                                    <label class="form-check-label" for="5b">Sebagai Anggota, Tiap Kegiatan</label>
                                                </div>
                                            </div>
                                            
                                        </div>
                                            
                                    </div> <!-- End Col -->
                                </div> <!-- inputField5 -->

                                <!-- 6 -->
                                <!-- Berperan Serta Aktif Dalam Pengelolaan Jurnal Ilmiah (Per Tahun) -->
                                <div id="inputField6" style="display: none;">
                                    <!-- Pertama Keluar yang Radio -->
                                    <div class="col my-3">
                                        <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
                                        <br>
                                        <div class="list-group">
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="6a" type="radio" name="komponen_kegiatan"  value="Editor/ Dewan Penyunting/ Dewan Redaksi Jurnal Ilmiah Internasional"> 
                                                    <label class="form-check-label" for="6a">Editor/ Dewan Penyunting/ Dewan Redaksi Jurnal Ilmiah Internasional</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="6b" type="radio" name="komponen_kegiatan" value="Editor/ Dewan Penyunting/ Dewan Redaksi Jurnal Ilmiah Nasional"> 
                                                    <label class="form-check-label" for="6b">Editor/ Dewan Penyunting/ Dewan Redaksi Jurnal Ilmiah Nasional</label>
                                                </div>
                                            </div>
                                            
                                        </div>
                                            
                                    </div> <!-- End Col -->
                                </div> <!-- inputField6 -->

                                <!-- 7 -->
                                <!-- Berperan Serta Aktif Dalam Pertemuan Ilmiah -->
                                <div id="inputField7" style="display: none;">
                                    <!-- Pertama Keluar yang Radio -->
                                    <div class="col my-3">
                                        <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
                                        <br>

                                        <div class="list-group">
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="7a" type="radio" name="komponen_kegiatan"  value="Tingkat Internasional/ Nasional/ Regional Sebagai" onclick="showInput7('a')"> 
                                                    <label class="form-check-label" for="7a">Tingkat Internasional/ Nasional/ Regional Sebagai</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="7b" type="radio" name="komponen_kegiatan" value="Di Lingkungan Perguruan Tinggi Sebagai" onclick="showInput7('b')"> 
                                                    <label class="form-check-label" for="7b">Di Lingkungan Perguruan Tinggi Sebagai</label>
                                                </div>
                                            </div>
                                            
                                        </div>
                                            
                                            <br>
                                            
                                            <!-- Radio 1 -->
                                            <div id="7a_radio" style="display: none;">
                                                <p class="mt-2 mb-3 text-center" style="color:#012970;"><strong>Nilai A</strong></p>
                                                <div class="list-group">
                                                    <div class="list-group-item">
                                                        <div class="form-check">
                                                            <input class="form-check-input" id="7a_radio_1" type="radio" name="tingkat_int_nas_reg_sebagai" value="8.7.a.1" > 
                                                            <label class="form-check-label" for="7a_radio_1">Ketua, Tiap Kegiatan</label>
                                                            {{-- <input type="hidden" name="nilai_kegiatan" value="Ketua, Tiap Kegiatan"> --}}
                                                        </div>
                                                    </div>
                                                    <div class="list-group-item">
                                                        <div class="form-check">
                                                            <input class="form-check-input" id="7a_radio_2"type="radio" name="tingkat_int_nas_reg_sebagai" value="8.7.a.2"> 
                                                            <label class="form-check-label" for="7a_radio_2">Anggota/ Peserta, Tiap Kegiatan</label>
                                                            {{-- <input type="hidden" name="nilai_kegiatan" value="Anggota/ Peserta, Tiap Kegiatan"> --}}
                                                        </div>
                                                    </div>
                                                </div> <!-- END list-group -->
                                            </div>
                                            
                                            <!-- Radio 2 -->
                                            <div id="7b_radio" style="display: none;">
                                                <p class="mt-2 mb-3 text-center" style="color:#012970;"><strong>Nilai B</strong></p>
                                                <div class="list-group">
                                                    <div class="list-group-item">
                                                        <div class="form-check">
                                                            {{-- <input type="hidden" name="nilai_kegiatan" value="Ketua, Tiap Kegiatan"> --}}
                                                            <input class="form-check-input" id="7b_radio_1" type="radio" name="ling_perguruan_tinggi_sebagai" value="8.7.b.1"> 
                                                            <label class="form-check-label" for="7b_radio_1">Ketua, Tiap Kegiatan</label>
                                                        </div>
                                                    </div>
                                                    <div class="list-group-item">
                                                        <div class="form-check">
                                                            {{-- <input type="hidden" name="nilai_kegiatan" value="Anggota/ Peserta, Tiap Kegiatan"> --}}
                                                            <input class="form-check-input" id="7b_radio_2"type="radio" name="ling_perguruan_tinggi_sebagai" value="8.7.b.2"> 
                                                            <label class="form-check-label" for="7b_radio_2">Anggota/ Peserta, Tiap Kegiatan</label>
                                                        </div>
                                                    </div>
                                                </div> <!-- END list-group -->
                                            </div> <!-- End Radio 2 -->

                                    </div> <!-- End Col -->
                                </div> <!-- InputField7 -->

                                <!-- 8 -->
                                <!-- Mendapat Tanda Jasa/ Penghargaan -->
                                <div id="inputField8" style="display: none;">
                                    <!-- Pertama Keluar yang Radio -->
                                    <div class="col my-3">
                                        <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
                                        <br>
                                        <div class="list-group">
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="8a" type="radio" name="komponen_kegiatan"  value="Penghargaan/ Tanda Jasa Satya Lencana 30 Tahun"> 
                                                    <label class="form-check-label" for="8a">Penghargaan/ Tanda Jasa Satya Lencana 30 Tahun</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="8b" type="radio" name="komponen_kegiatan" value="Penghargaan/ Tanda Jasa Satya Lencana 20 Tahun"> 
                                                    <label class="form-check-label" for="8b">Penghargaan/ Tanda Jasa Satya Lencana 20 Tahun</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="8c" type="radio" name="komponen_kegiatan" value="Penghargaan/ Tanda Jasa Satya Lencana 10 Tahun"> 
                                                    <label class="form-check-label" for="8c">Penghargaan/ Tanda Jasa Satya Lencana 10 Tahun</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="8d" type="radio" name="komponen_kegiatan" value="Tingkat Internasional, Tiap Tanda Jasa/ Penghargaan"> 
                                                    <label class="form-check-label" for="8d">Tingkat Internasional, Tiap Tanda Jasa/ Penghargaan</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="8e" type="radio" name="komponen_kegiatan" value="Tingkat Nasional, Tiap Tanda Jasa/ Penghargaan"> 
                                                    <label class="form-check-label" for="8e">Tingkat Nasional, Tiap Tanda Jasa/ Penghargaan</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="8f" type="radio" name="komponen_kegiatan" value="Tingkat Daerah/ Lokal, Tiap Tanda Jasa/ Penghargaan"> 
                                                    <label class="form-check-label" for="8f">Tingkat Daerah/ Lokal, Tiap Tanda Jasa/ Penghargaan</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- End Col -->
                                </div> <!-- End inputField8 -->

                                <!-- 9 -->
                                <!-- Menulis Buku Pelajaran SLTA Ke Bawah Yang Diterbitkan Dan Diedarkan Secara Nasional -->
                                <div id="inputField9" style="display: none;">
                                    <!-- Pertama Keluar yang Radio -->
                                    <div class="col my-3">
                                        <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
                                        <br>
                                        <div class="list-group">
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="9a" type="radio" name="komponen_kegiatan"  value="Buku SMTA Atau Setingkat, Tiap Buku"> 
                                                    <label class="form-check-label" for="9a">Buku SMTA Atau Setingkat, Tiap Buku</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="9b" type="radio" name="komponen_kegiatan" value="Buku SMTP Atau Setingkat, Tiap Buku"> 
                                                    <label class="form-check-label" for="9b">Buku SMTP Atau Setingkat, Tiap Buku</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="9c" type="radio" name="komponen_kegiatan" value="Buku SD Atau Setingkat, Tiap Buku"> 
                                                    <label class="form-check-label" for="9c">Buku SD Atau Setingkat, Tiap Buku</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- End Col -->
                                </div> <!-- End inputField9 -->


                                <!-- 10 -->
                                <!-- Mempunyai Prestasi Di Bidang Olahraga/ Humaniora -->
                                <div id="inputField10" style="display: none;">
                                    <!-- Pertama Keluar yang Radio -->
                                    <div class="col my-3">
                                        <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
                                        <br>
                                        <div class="list-group">
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="10a" type="radio" name="komponen_kegiatan"  value="Tingkat Internasional, Tiap Piagam/ Medali"> 
                                                    <label class="form-check-label" for="10a">Tingkat Internasional, Tiap Piagam/ Medali</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="10b" type="radio" name="komponen_kegiatan" value="Tingkat Nasional, Tiap Piagam/ Medali"> 
                                                    <label class="form-check-label" for="10b">Tingkat Nasional, Tiap Piagam/ Medali</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="10c" type="radio" name="komponen_kegiatan" value="Tingkat Daerah/Lokal, Tiap Piagam/ Medali"> 
                                                    <label class="form-check-label" for="10c">Tingkat Daerah/Lokal, Tiap Piagam/ Medali</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- End Col -->
                                </div> <!-- End inputField10 -->

                                
                                <!-- 11 -->
                                <!-- Keanggotaan Dalam Tim Penilai Jabatan Akademik Dosen (Tiap Semester) -->
                                <div id="inputField11" style="display: none;">
                                    <p class="text-center" style="color:#ff0000;" ><strong>Dipilih</strong></p>
                                    <p class="text-center" style="color:#012970;">
                                        <strong>
                                            Keanggotaan Dalam Tim Penilai Jabatan Akademik Dosen (Tiap Semester)
                                        </strong>
                                    </p>
                                </div> <!-- End inputField11 -->
                            

                            {{-- </div> <!-- End Card Body -->
                        </div> <!-- End Card -->  --}}
                    </div>

                <div class="col-lg text-center">
                    <a href="{{ route('penunjang-tri-dharma-pt') }}" class="my-2 btn btn-lg btn-warning rounded-pill" style="color:#012970;padding-left: 50px; padding-right: 50px;">Kembali</a>
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
                    $('#inputField9').hide();
                    $('#inputField10').hide();
                    $('#inputField11').hide();

                    // Show the input field based on the selected option
                    if (selectedOption == 'default') {
                        $('#default').show();
                    } else if (selectedOption == 'Menjadi Anggota Dalam Suatu Panitia/ Badan Pada Perguruan Tinggi') {
                        $('#inputField1').show();
                    } else if (selectedOption == 'Menjadi Anggota Panitia/ Badan Pada Lembaga Pemerintah') {
                        $('#inputField2').show();
                    } else if (selectedOption == 'Menjadi Anggota Organisasi Profesi'){
                        $('#inputField3').show();
                    } else if (selectedOption == 'Mewakili Perguruan Tinggi/ Lembaga Pemerintah Duduk Dalam Panitia Antar Lembaga, Tiap Kepanitiaan') {
                        $('#inputField4').show();
                    } else if (selectedOption == 'Menjadi Anggota Delegasi Nasional Ke Pertemuan Internasional') {
                        $('#inputField5').show();
                    } else if (selectedOption == 'Berperan Serta Aktif Dalam Pengelolaan Jurnal Ilmiah (Per Tahun)') {
                        $('#inputField6').show();
                    } else if (selectedOption == 'Berperan Serta Aktif Dalam Pertemuan Ilmiah') {
                        $('#inputField7').show();
                    } else if (selectedOption == 'Mendapat Tanda Jasa/ Penghargaan') {
                        $('#inputField8').show();
                    } else if (selectedOption == 'Menulis Buku Pelajaran SLTA Ke Bawah Yang Diterbitkan Dan Diedarkan Secara Nasional') {
                        $('#inputField9').show();
                    }else if (selectedOption == 'Mempunyai Prestasi Di Bidang Olahraga/ Humaniora') {
                        $('#inputField10').show();
                    } else if (selectedOption == 'Keanggotaan Dalam Tim Penilai Jabatan Akademik Dosen (Tiap Semester)') {
                        $('#inputField11').show();
                    }

                });
            });
        </script>

        <!-- ======= Radio show Hasil Penelitian  ======= -->
            <script>
                function showInput2(option) {
                    
                    if (option === 'a') {
                        document.getElementById('2a_radio').style.display = 'block';
                        document.getElementById('2b_radio').style.display = 'none';
                    } else if (option === 'b') {
                        document.getElementById('2a_radio').style.display = 'none';
                        document.getElementById('2b_radio').style.display = 'block';
                    } 
                }

                function showInput3(option) {

                    if (option === 'a') {
                        document.getElementById('3a_radio').style.display = 'block';
                        document.getElementById('3b_radio').style.display = 'none';
                    } else if (option === 'b') {
                        document.getElementById('3a_radio').style.display = 'none';
                        document.getElementById('3b_radio').style.display = 'block';
                    }
                }

                function showInput7(option) {

                    if (option === 'a') {
                        document.getElementById('7a_radio').style.display = 'block';
                        document.getElementById('7b_radio').style.display = 'none';
                    } else if (option === 'b') {
                        document.getElementById('7a_radio').style.display = 'none';
                        document.getElementById('7b_radio').style.display = 'block';
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

        // Input Hidden for ID
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