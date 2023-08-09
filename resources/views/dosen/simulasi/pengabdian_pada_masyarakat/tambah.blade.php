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
              <h2 class="text-center" style="color:#012970;"><strong>Pengabdian Pada Masyarakat</strong></h2>
              
              <div class="text-center mt-3">
                <a href="#" class="btn btn-primary" onclick="clickURL()">Click disini jika terjadi error</a>
              </div>
            
              
                
            <!-- Vertical Form -->
            <form class="row g-3 my-3" action="{{ route('pengabdian_pada_masyarakat_tambah_store') }}" method="post" enctype="multipart/form-data">
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
                                <!-- Menduduki Jabatan Pimpinan Pada Lembaga Pemerintahan/ Pejabat Negara
                                    Yang Harus Dibebaskan Dari Jabatan Organiknya Tiap Semester -->
                                <div id="inputField1" style="display: none;">
                                    <p class="text-center" style="color:#ff0000;" ><strong>Dipilih</strong></p>
                                    <p class="text-center" style="color:#012970;">
                                        <strong>
                                            Menduduki Jabatan Pimpinan Pada Lembaga Pemerintahan/ Pejabat 
                                            Negara Yang Harus Dibebaskan Dari Jabatan Organiknya Tiap Semester
                                        </strong>
                                    </p>
                                </div> <!-- inputField1 -->

                                <!-- 2 -->
                                <!-- Melaksanakan Pengembangan Hasil Pendidikan, Dan Penelitian Yang 
                                    Dapat Dimanfaatkan Oleh Masyarakat/ Industry Setiap Program -->
                                <div id="inputField2" style="display: none;">
                                    <p class="text-center" style="color:#ff0000;" ><strong>Dipilih</strong></p>
                                    <p class="text-center" style="color:#012970;">
                                        <strong>
                                            Melaksanakan Pengembangan Hasil Pendidikan, Dan Penelitian 
                                            Yang Dapat Dimanfaatkan Oleh Masyarakat/ Industry Setiap Program
                                        </strong>
                                    </p>
                                </div> <!-- inputField2 -->

                                <!-- 3 -->
                                <!-- Memberikan Latihan/ Penyuluhan/ Penataran/ Ceramah Pada Masyarakat, Terjadwal/ Terprogram -->
                                <div id="inputField3" style="display: none;">
                                    <!-- Pertama Keluar yang Radio -->
                                        <div class="col my-3">
                                            <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
                                            <br>

                                            <div class="list-group">
                                                <div class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="3a" type="radio" name="komponen_kegiatan"  value="Dalam Satu Semester Atau Lebih" onclick="showInput3('a')"> 
                                                        <label class="form-check-label" for="3a">Dalam Satu Semester Atau Lebih</label>
                                                    </div>
                                                </div>
                                                <div class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="3b" type="radio" name="komponen_kegiatan" value="Kurang Dari Satu Semester Dan Minimal Satu Bulan" onclick="showInput3('b')"> 
                                                        <label class="form-check-label" for="3b">Kurang Dari Satu Semester Dan Minimal Satu Bulan</label>
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
                                                                <input class="form-check-input" id="3a_radio_1" type="radio" name="satu_semester_atau_lebih" value="7.3.1.a" > 
                                                                <label class="form-check-label" for="3a_radio_1">Tingkat Internasional Tiap Program</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Tingkat Internasional Tiap Program"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="3a_radio_2"type="radio" name="satu_semester_atau_lebih" value="7.3.1.b"> 
                                                                <label class="form-check-label" for="3a_radio_2">Tingkat Nasional, Tiap Program</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Tingkat Nasional, Tiap Program"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="3a_radio_3"type="radio" name="satu_semester_atau_lebih" value="7.3.1.c"> 
                                                                <label class="form-check-label" for="3a_radio_3">Tingkat Lokal, Tiap Program</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Tingkat Lokal, Tiap Program"> --}}
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
                                                                <input class="form-check-input" id="3b_radio_1" type="radio" name="kurang_satu_semester" value="7.3.2.a" > 
                                                                <label class="form-check-label" for="3b_radio_1">Tingkat Internasional, Tiap Program</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Tingkat Internasional, Tiap Program"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="3b_radio_2"type="radio" name="kurang_satu_semester" value="7.3.2.b"> 
                                                                <label class="form-check-label" for="3b_radio_2">Tingkat Nasional, Tiap Program</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Tingkat Nasional, Tiap Program"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="3b_radio_3"type="radio" name="kurang_satu_semester" value="7.3.2.c"> 
                                                                <label class="form-check-label" for="3b_radio_3">Tingkat Lokal, Tiap Program</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Tingkat Lokal, Tiap Program"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item">
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="3b_radio_4"type="radio" name="kurang_satu_semester" value="7.3.2.d"> 
                                                                <label class="form-check-label" for="3b_radio_4">Insidental, Tiap Kegiatan/ Program</label>
                                                                {{-- <input type="hidden" name="nilai_kegiatan" value="Insidental, Tiap Kegiatan/ Program"> --}}
                                                            </div>
                                                        </div>

                                                    </div> <!-- END list-group -->
                                                    
                                                </div> <!-- End Radio 2 -->

                                        </div> <!-- End Col -->
                                </div> <!-- End InputField3 -->

                                <!-- 4 -->
                                <!-- Memberi Pelayanan Kepada Masyarakat Atau Kegiatan Lain Yang 
                                    Menunjang Pelaksanaan Tugas Pemerintahan Dan Pembangunan -->
                                <div id="inputField4" style="display: none;">
                                    <!-- Pertama Keluar yang Radio -->
                                    <div class="col my-3">
                                        <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
                                        <br>
                                        <div class="list-group">
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="5a" type="radio" name="komponen_kegiatan"  value="Berdasarkan Bidang Keahlian, Tiap Program"> 
                                                    <label class="form-check-label" for="5a">Berdasarkan Bidang Keahlian, Tiap Program</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="5b" type="radio" name="komponen_kegiatan" value="Berdasarkan Penugasan Lembaga Terguruan Tinggi, Tiap Program"> 
                                                    <label class="form-check-label" for="5b">Berdasarkan Penugasan Lembaga Terguruan Tinggi, Tiap Program</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="5c" type="radio" name="komponen_kegiatan" value="Berdasarkan Fungsi/ Jabatan Tiap Program"> 
                                                    <label class="form-check-label" for="5c">Berdasarkan Fungsi/ Jabatan Tiap Program</label>
                                                </div>
                                            </div>
                                            
                                        </div>
                                            
                                    </div> <!-- End Col -->
                                </div> <!-- Input Field4-->

                                <!-- 5 -->
                                <!-- Membuat/ Menulis Karya Pengabdian Pada Masyarakat Yang Tidak Dipublikasikan, Tiap Karya -->
                                <div id="inputField5" style="display: none;">
                                    <p class="text-center" style="color:#ff0000;" ><strong>Dipilih</strong></p>
                                    <p class="text-center" style="color:#012970;">
                                        <strong>
                                            Membuat/ Menulis Karya Pengabdian Pada Masyarakat Yang Tidak Dipublikasikan, Tiap Karya 
                                        </strong>
                                    </p>
                                </div> <!-- inputField5 -->

                            

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

                    // Show the input field based on the selected option
                    if (selectedOption == 'default') {
                        $('#default').show();
                    } else if (selectedOption == 'Menduduki Jabatan Pimpinan Pada Lembaga Pemerintahan/ Pejabat Negara Yang Harus Dibebaskan Dari Jabatan Organiknya Tiap Semester') {
                        $('#inputField1').show();
                    } else if (selectedOption == 'Melaksanakan Pengembangan Hasil Pendidikan, Dan Penelitian Yang Dapat Dimanfaatkan Oleh Masyarakat/ Industry Setiap Program') {
                        $('#inputField2').show();
                    } else if (selectedOption == 'Memberikan Latihan/ Penyuluhan/ Penataran/ Ceramah Pada Masyarakat, Terjadwal/ Terprogram'){
                        $('#inputField3').show();
                    } else if (selectedOption == 'Memberi Pelayanan Kepada Masyarakat Atau Kegiatan Lain Yang Menunjang Pelaksanaan Tugas Pemerintahan Dan Pembangunan') {
                        $('#inputField4').show();
                    } else if (selectedOption == 'Membuat/ Menulis Karya Pengabdian Pada Masyarakat Yang Tidak Dipublikasikan, Tiap Karya') {
                        $('#inputField5').show();
                    } 

                });
            });
        </script>

        <!-- ======= Radio show Hasil Penelitian  ======= -->
            <script>

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