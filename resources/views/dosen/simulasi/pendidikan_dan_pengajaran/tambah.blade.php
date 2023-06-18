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
              
              <div class="text-center mt-3">
                <a href="#" class="btn btn-primary" onclick="clickURL()">Click disini jika terjadi error</a>
              </div>
            
              
                
            <!-- Vertical Form -->
            <form class="row g-3 my-3" action="{{ route('pendidikan_dan_pengajaran_tambah_store') }}" method="post" enctype="multipart/form-data">
              @csrf
                    <div class="col-lg-6">
                        {{-- <div class="card">
                            <div class="card-body"> --}}

                                <div class="col my-3">
                                    <label for="inputNanme4" class="form-label" style="color:#012970;"><strong>Tipe Kegiatan</strong></label>
                                    <select name="tipe_kegiatan" class="form-select">
                                        <option value="default" selected>Pilih Tipe Kegiatan</option>
                                        @foreach($tipe_kegiatan as $data)
                                            @if(old('tipe_kegiatan') == $data->nama_kegiatan)
                                                <option value="{{ $data->nama_kegiatan }}" selected>{{ $data->nama_kegiatan }}</option>
                                            @else
                                                <option value="{{ $data->nama_kegiatan }}">{{ $data->nama_kegiatan }}</option>
                                            @endif
                                        @endforeach
                                    </select>
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
                        {{-- @if ($errors->has('perkuliahan'))
                            <div class="invalid-feedback">
                                {{ $errors->first('perkuliahan') }}
                            </div>
                        @endif --}}
                        @error('perkuliahan')
                            <p class="text-danger"><strong>{{ $message }}</strong></p>
                            <hr>
                        @enderror
                        @error('dokter_klinis')
                            <p class="text-danger"><strong>{{ $message }}</strong></p>
                            <hr>
                        @enderror
                        @error('buat_error')
                            <p class="text-danger"><strong>{{ $message }}</strong></p>
                            <hr>
                        @enderror
                        @error('jenis_pendidikan')
                            <p class="text-danger"><strong>{{ $message }}</strong></p>
                            <hr>
                        @enderror
                        @error('penguji_uiian_akhir')
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

                                <!-- 1 Mengikuti Pendidikan Formal-->
                                <div id="inputField1" style="display: none;">
                                    @include('dosen.simulasi.pendidikan_dan_pengajaran.tambah.mengikuti_pendidikan_formal')
                                </div>

                                <!-- 2 Melaksanakan Perkuliahan -->
                                <div id="inputField2" style="display: none;">
                                    @include('dosen.simulasi.pendidikan_dan_pengajaran.tambah.melaksanakan_perkuliahan')
                                </div>

                                <!-- Mengikuti Diklat Pra-Jabatan -->
                                <div id="inputField3" style="display: none;">
                                    <h4 class="text-center" style="color:#012970;" >Tipe Kegiatan Ini</h4>
                                    <h4 class="text-center" style="color:#012970;" >Tidak Memiliki Komponen Tambahan</h4>
                                </div>

                                <!-- Membimbing disertasi, tesis, skripsi, dan laporan hasil studi -->
                                <!-- pembimbing -->
                                <div id="inputField4" style="display: none;">
                                    @include('dosen.simulasi.pendidikan_dan_pengajaran.tambah.pembimbing')
                                </div> <!-- Input Field4-->

                                <!-- Bertugas Sebagai Penguji Pada Ujian Akhir/Profesi -->
                                <div id="inputField5" style="display: none;">
                                    @include('dosen.simulasi.pendidikan_dan_pengajaran.tambah.bertugas_sebagai_penguji')
                                </div>

                                <!-- Membina kegiatan mahasiswa di bidang akademik dan kemahasiswaan  -->
                                <div id="inputField6" style="display: none;">
                                    <p class="text-center" style="color:#ff0000;" ><strong>Dipilih</strong></p>
                                    <p class="text-center" style="color:#012970;">
                                        <strong>
                                            Membina kegiatan mahasiswa di bidang akademik dan kemahasiswaan, termasuk dalam kegiatan ini adalah 
                                            membimbing mahasiswa menghasilkan produk saintifik (setiap semester)
                                        </strong>
                                    </p>
                               
                                </div>

                                <!-- Mengembangkan Program Kuliah -->
                                <div id="inputField7" style="display: none;">
                                    <p class="text-center" style="color:#ff0000;" ><strong>Dipilih</strong></p>
                                    <p class="text-center" style="color:#012970;">
                                        <strong>
                                            Mengembangkan program kuliah yang mempunyai nilai kebaharuan metode atau subtansi 
                                            (setiap produk)
                                        </strong>
                                    </p>
                                </div>

                                <!-- Mengembangkan Bahan Pengajaran -->
                                <div id="inputField8" style="display: none;">
                                    @include('dosen.simulasi.pendidikan_dan_pengajaran.tambah.mengembangkan_bahan_pengajaran')
                                </div>

                                <!-- Menyampaikan Orasi Ilmiah -->
                                <div id="inputField9" style="display: none;">
                                    <p class="text-center" style="color:#ff0000;" ><strong>Dipilih</strong></p>
                                    <p class="text-center" style="color:#012970;">
                                        <strong>
                                            Menyampaikan orasi ilmiah di tingkat perguruan tinggi 
                                        </strong>
                                    </p>
                                </div>


                                <!-- Menduduki Jabatan Pimpinan Perguruan Tinggi -->
                                <div id="inputField10" style="display: none;">
                                    @include('dosen.simulasi.pendidikan_dan_pengajaran.tambah.menduduki_jabatan_pimpinan')
                                </div> <!-- End id input field 10 -->


                                <!-- Membimbing Dosen Yang Mempunyai Jabatan Akademik Lebih Rendah -->
                                <div id="inputField11" style="display: none;">
                                    @include('dosen.simulasi.pendidikan_dan_pengajaran.tambah.bimbing_dosen_rendah')
                                </div> <!-- End id input field 11 -->


                                <!-- Melaksanakan Kegiatan Detasering Dan Pencangkokan Di Luar Institusi -->
                                <div id="inputField12" style="display: none;">
                                    @include('dosen.simulasi.pendidikan_dan_pengajaran.tambah.kegiatan_detasering_dan_pencangkokan')
                                </div> <!-- End InpuField 12 -->

                                <!-- Melaksanakan Pengembangan Diri Untuk Meningkatkan Kompetensi -->
                                <div id="inputField13" style="display: none;">
                                    @include('dosen.simulasi.pendidikan_dan_pengajaran.tambah.pengembangan_diri')
                                </div> <!-- End Input Field 13 -->
                            

                            

                            {{-- </div> <!-- End Card Body -->
                        </div> <!-- End Card -->  --}}
                    </div>

                <div class="col-lg text-center">
                    <a href="{{ route('pendidikan-dan-pengajaran') }}" class="btn btn-lg btn-warning rounded-pill" style="color:#012970;padding-left: 50px; padding-right: 50px;">Kembali</a>
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill" style="background-color:#012970; color:#ffffff;padding-left: 50px; padding-right: 50px;">Tambah</button>
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

    <!-- JavaScript code to handle the select change event -->
        <script>
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
                    $('#inputField12').hide();
                    $('#inputField13').hide();

                    // Show the input field based on the selected option
                    if (selectedOption == 'default') {
                        $('#default').show();
                    } else if (selectedOption == 'Mengikuti Pendidikan Formal') {
                        $('#inputField1').show();
                    } else if (selectedOption == 'Melaksanakan Perkuliahan') {
                        $('#inputField2').show();
                    } else if (selectedOption == 'Mengikuti Diklat Pra-Jabatan' ||
                                selectedOption == 'Membimbing Seminar Mahasiswa (Setiap Mahasiswa)' ||
                                selectedOption == 'Membimbing KKN, Praktik Kerja Nyata, Praktik Kerja Lapangan'){
                        $('#inputField3').show();
                    } else if (selectedOption == 'Membimbing Disertasi, Tesis, Skripsi, dan Laporan Hasil Studi') {
                        $('#inputField4').show();
                    } else if (selectedOption == 'Bertugas Sebagai Penguji Pada Ujian Akhir/Profesi') {
                        $('#inputField5').show();
                    } else if (selectedOption == 'Membina Kegiatan Mahasiswa Di Bidang Akademik Dan Kemahasiswaan') {
                        $('#inputField6').show();
                    } else if (selectedOption == 'Mengembangkan Program Kuliah') {
                        $('#inputField7').show();
                    } else if (selectedOption == 'Mengembangkan Bahan Pengajaran') {
                        $('#inputField8').show();
                    } else if (selectedOption == 'Menyampaikan Orasi Ilmiah') {
                        $('#inputField9').show();
                    } else if (selectedOption == 'Menduduki Jabatan Pimpinan Perguruan Tinggi') {
                        $('#inputField10').show();
                    } else if (selectedOption == 'Membimbing Dosen Yang Mempunyai Jabatan Akademik Lebih Rendah') {
                        $('#inputField11').show();
                    } else if (selectedOption == 'Melaksanakan Kegiatan Detasering Dan Pencangkokan Di Luar Institusi') {
                        $('#inputField12').show();
                    } else if (selectedOption == 'Melaksanakan Pengembangan Diri Untuk Meningkatkan Kompetensi') {
                        $('#inputField13').show();
                    }

                });
            });
        </script>

        <!-- ======= Radio show Melaksanakan Pendidikan  ======= -->
            <script>
                function showInput(option) {
                    
                    // var perkuliahanInput = document.getElementById("inputPerkuliahan");

                    // if (option === "a") {
                    //     perkuliahanInput.setAttribute("required", "required");
                    // } else {
                    //     perkuliahanInput.removeAttribute("required");
                    // }

                    if (option === 'a') {
                        document.getElementById('perkuliahan').style.display = 'block';
                        document.getElementById('dokter_klinis').style.display = 'none';
                    } else if (option === 'b') {
                        document.getElementById('perkuliahan').style.display = 'none';
                        document.getElementById('dokter_klinis').style.display = 'block';
                    }
                }
            </script>
                

        <!-- ======= Radio show Membimbing Disertasi, Tesis, Skripsi, dan Laporan Hasil Studi  ======= -->
            <script>
                function showInputMembimbing(option){
                    if (option === 'a') {
                        document.getElementById('pembimbing1').style.display = 'block';
                        document.getElementById('pembimbing2').style.display = 'none';
                    } else if (option === 'b') {
                        document.getElementById('pembimbing1').style.display = 'none';
                        document.getElementById('pembimbing2').style.display = 'block';
                    }
                }
            </script>

            <!-- Trigger Required-->

                {{-- <script>
                    function showInput(option) {
                    var perkuliahanInput = document.getElementById("inputPerkuliahan");

                    if (option === "a") {
                        perkuliahanInput.setAttribute("required", "required");
                    } else {
                        perkuliahanInput.removeAttribute("required");
                    }
                }
                </script> --}}
            

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


    <!-- Refresh Page -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 
    <script>
        function clickURL() {
          var currentURL = window.location.href;
          window.location.href = currentURL;
        }
      </script>
@endpush