@extends('layouts.master')

@section('content')

@include('layouts.header')

<main id="main" class="main">


    <section class="section">
      <div class="row">
          <!-- ================ Display None ================= -->
          <div id="myDiv" style="display:none;">
            <form action="{{ route('pegawai.semua_dosen_store') }}" method="post" class="">
                @csrf
                <div class="row">
                    <h2 class="text-center">Tambah Data Dosen</h2>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div class="col-lg-6">
                        <div class="col">
                            <label for="name" class="mt-3 form-label"><strong>Nama Dosen</strong></label>
                            <input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="col">
                            <label for="email" class="mt-3 form-label"><strong>email</strong></label>
                            <input id="email"  class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                        @enderror
                        <div class="form-group my-2">
                            <label for="nip" class="mt-3 form-label"><strong>NIP</strong></label>
                            <input id="nip" class="form-control{{ $errors->has('nip') ? ' is-invalid' : '' }}" type="text" name="nip" value="{{ old('nip') }}" required autocomplete="off">
                            @error('nip')
                                <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                            @enderror
                        </div>
                    
                        <div class="form-group my-2">
                            <label for="nidn" class="mt-3 form-label"><strong>NIDN</strong></label>
                            <input id="nidn" class="form-control{{ $errors->has('nidn') ? ' is-invalid' : '' }}" type="text" name="nidn" value="{{ old('nidn') }}" required autocomplete="off">
                            @error('nidn')
                                <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                            @enderror
                        </div>
                    </div>
                                            
                    
                    <!-- =========== Fakultas and jurusan prodi Section ============================-->

                    <div class="col-lg-6">
                        <div class="col">
                            <label for="fakultas" class="mt-3 form-label"><strong>Fakultas</strong></label>
                            <input id="fakultas" class="form-control" type="text" name="fakultas" 
                            value=
                            "@if(auth()->user()->fakultas == 'Fakultas Teknik')Fakultas Teknik
                            @elseif(auth()->user()->fakultas == 'Fakultas Bahasa Dan Seni')Fakultas Bahasa Dan Seni
                            @elseif(auth()->user()->fakultas == 'Fakultas Ilmu Pendidikan')Fakultas Ilmu Pendidikan
                            @elseif(auth()->user()->fakultas == 'Fakultas Matematika Ilmu Pengetahuan Alam Dan Kebumian')Fakultas Matematika Ilmu Pengetahuan Alam Dan Kebumian
                            @elseif(auth()->user()->fakultas == 'Fakultas Ilmu Keolahragaan Dan Kesejahteraan Masyarakat')Fakultas Ilmu Keolahragaan Dan Kesejahteraan Masyarakat
                            @elseif(auth()->user()->fakultas == 'Fakultas Ekonomi Dan Bisnis')Fakultas Ekonomi Dan Bisnis
                            @elseif(auth()->user()->fakultas == 'Fakultas Ilmu Sosial Dan Hukum')Fakultas Ilmu Sosial Dan Hukum
                            @endif
                            " disabled>
                        </div>

                        @if(auth()->user()->fakultas == 'Fakultas Ilmu Pendidikan')
                        <!-- ========================== Fakultas Ilmu Pendidikan ==============  -->
                            <div class="col">
                                <label for="jurusan_prodi" class="mt-3 form-label"><strong>Program Studi</strong></label>
                                <select name="jurusan_prodi" class="form-select">
                                    <option selected>Pilih Program Studi</option>
                                    @foreach($fip as $data)
                                    @if(old('jurusan_prodi') == $data->nama)
                                        <option value="{{ $data->nama }}" selected>{{ $data->nama }}</option>
                                    @else
                                        <option value="{{ $data->nama }}">{{ $data->nama }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('jurusan_prodi')
                                        <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                                @enderror
                            </div>
                        
                        
                        @elseif(auth()->user()->fakultas == 'Fakultas Matematika Ilmu Pengetahuan Alam Dan Kebumian')
                        <!-- ========================== Fakultas Matematika Ilmu Pengetahuan Alam Dan Kebumian==============  -->
                            <div class="col">
                                <label for="jurusan_prodi" class="mt-3 form-label"><strong>Program Studi</strong></label>
                                <select name="jurusan_prodi" class="form-select">
                                    <option selected>Pilih Program Studi</option>
                                    @foreach($fmipa as $data)
                                    @if(old('jurusan_prodi') == $data->nama)
                                        <option value="{{ $data->nama }}" selected>{{ $data->nama }}</option>
                                    @else
                                        <option value="{{ $data->nama }}">{{ $data->nama }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('jurusan_prodi')
                                        <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                                @enderror
                            </div>

                        @elseif(auth()->user()->fakultas == 'Fakultas Ilmu Keolahragaan Dan Kesejahteraan Masyarakat')
                        <!-- ========================== Fakultas Ilmu Keolahragaan Dan Kesejahteraan Masyarakat ==============  -->
                            <div class="col">
                                <label for="jurusan_prodi" class="mt-3 form-label"><strong>Program Studi</strong></label>
                                <select name="jurusan_prodi" class="form-select">
                                    <option selected>Pilih Program Studi</option>
                                    @foreach($fik as $data)
                                    @if(old('jurusan_prodi') == $data->nama)
                                        <option value="{{ $data->nama }}" selected>{{ $data->nama }}</option>
                                    @else
                                        <option value="{{ $data->nama }}">{{ $data->nama }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('jurusan_prodi')
                                        <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                                @enderror
                            </div>

                        @elseif(auth()->user()->fakultas == 'Fakultas Teknik')
                        <!-- ========================== Fakultas Teknik ==============  -->
                            <div class="col">
                                <label for="jurusan_prodi" class="mt-3 form-label"><strong>Program Studi</strong></label>
                                <select name="jurusan_prodi" class="form-select">
                                    <option selected>Pilih Program Studi</option>
                                    @foreach($fatek as $data)
                                    @if(old('jurusan_prodi') == $data->nama)
                                        <option value="{{ $data->nama }}" selected>{{ $data->nama }}</option>
                                    @else
                                        <option value="{{ $data->nama }}">{{ $data->nama }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('jurusan_prodi')
                                        <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                                @enderror
                            </div>

                        @elseif(auth()->user()->fakultas == 'Fakultas Ekonomi Dan Bisnis')
                        <!-- ========================== Fakultas Ekonomi Dan Bisnis ==============  -->
                            <div class="col">
                                <label for="jurusan_prodi" class="mt-3 form-label"><strong>Program Studi</strong></label>
                                <select name="jurusan_prodi" class="form-select">
                                    <option selected>Pilih Program Studi</option>
                                    @foreach($fekon as $data)
                                    @if(old('jurusan_prodi') == $data->nama)
                                        <option value="{{ $data->nama }}" selected>{{ $data->nama }}</option>
                                    @else
                                        <option value="{{ $data->nama }}">{{ $data->nama }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('jurusan_prodi')
                                        <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                                @enderror
                            </div>

                        @elseif(auth()->user()->fakultas == 'Fakultas Ilmu Sosial Dan Hukum')
                        <!-- ========================== Fakultas Ilmu Sosial Dan Hukum ==============  -->
                            <div class="col">
                                <label for="jurusan_prodi" class="mt-3 form-label"><strong>Program Studi</strong></label>
                                <select name="jurusan_prodi" class="form-select">
                                    <option selected>Pilih Program Studi</option>
                                    @foreach($fis as $data)
                                    @if(old('jurusan_prodi') == $data->nama)
                                        <option value="{{ $data->nama }}" selected>{{ $data->nama }}</option>
                                    @else
                                        <option value="{{ $data->nama }}">{{ $data->nama }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('jurusan_prodi')
                                        <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                                @enderror
                            </div>

                        @elseif(auth()->user()->fakultas == 'Fakultas Bahasa Dan Seni')
                        <!-- ========================== Fakultas Bahasa Dan Seni ==============  -->
                            <div class="col">
                                <label for="jurusan_prodi" class="mt-3 form-label"><strong>Program Studi</strong></label>
                                <select name="jurusan_prodi" class="form-select">
                                    <option selected>Pilih Program Studi</option>
                                    @foreach($fbs as $data)
                                    @if(old('jurusan_prodi') == $data->nama)
                                        <option value="{{ $data->nama }}" selected>{{ $data->nama }}</option>
                                    @else
                                        <option value="{{ $data->nama }}">{{ $data->nama }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('jurusan_prodi')
                                        <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                                @enderror
                            </div>
                        
                        @endif



                        <!--  ============================== End  ================ -->
                        
                        
                        <div class="col">
                            <label for="pangkat_id" class="mt-3 form-label"><strong>Golongan</strong></label>
                            <select name="pangkat_id" class="form-select">
                                <option selected>Pilih Pangkat Saat Ini</option>
                                @foreach($pangkat as $data)
                                  @if(old('pangkat_id') == $data->id)
                                    <option value="{{ $data->id }}" selected>{{ $data->golongan }}</option>
                                  @else
                                    <option value="{{ $data->id }}">{{ $data->golongan }}</option>
                                  @endif
                                @endforeach
                              </select>
                              @error('pangkat_id')
                                    <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                            @enderror
                        </div>
                        <div class="col">
                            <div class="form-group my-2">
                                <label for="tmt" class="mt-3 form-label"><strong>TMT</strong></label>
                                <input id="tmt" class="form-control{{ $errors->has('tmt') ? ' is-invalid' : '' }}" type="date" name="tmt" value="{{ old('tmt') }}" required autocomplete="off">
                                @error('tmt')
                                    <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                                @enderror
                            </div>
                        </div>
                        
                    </div>
                </div> <!-- End Row-->
            </form>
                <div class="row">
                    <hr class="my-3">
                </div> <!-- End ROW -->
            
          </div> <!-- End My div-->
          <!-- End Display None-->


        <!--=== Start The Card =================================================== -->
      <div class="row">
        <div class="col-lg">
          <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-6">
                      <p class="mb-0 mt-4"><strong>Semua Dosen</strong></p>
                    </div>
                    <!-- ======== Trigger Show ======-->
                    <div class="col-6 text-end">
                      <a href="#" class="mb-0 mt-4 btn btn-primary" id="showDiv" style="background-color:#012970 ">+ Tambah</a>
                    </div>
                </div>

                

                  <!-- ========== Data Table ========-->
                  <div class="row" >
                    <div class="col-lg my-3">
                        <h2></h2>
                        <table id="semua_dosen" class="table table-hover" style="width:100%">
                            <thead>
                              <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">Nama</th>
                                <th scope="col" class="text-center">Program Studi</th>
                                <th scope="col" class="text-center">Detail</th>
                              </tr>
                            </thead>
                            <tbody>
                            @foreach($all_dosen as $data)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td class="text-center"><p>{{ $data->name }}</p></td>
                                    <td class="text-center"><p>{{ $data->jurusan_prodi }}</p></td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            {{-- <a href="{{ route('pegawai.ubah_status_kenaikan_pangkat', $data->email) }}" class="text-center badge bg-outline-primary" >
                                                <span class="badge bg-primary">
                                                    Ubah Status Kenaikan Pangkat
                                                </span>
                                            </a> --}}
                                            <form action="{{ route('pegawai.semua_dosen_delete', $data->id) }}" id="delete-form-{{ $data->id }}" method="post">@csrf @method('delete')</form>  
                                            <a href="#" 
                                                class="text-center my-1 mx-1" style="color:red;font-size:15px;" 
                                                onclick="confirmDelete(event, '{{ $data->id }}')">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                            <a href="{{ route('pegawai.ubah_status_kenaikan_pangkat', $data->email) }}" 
                                                class="text-center my-1 mx-1" style="color:#012970;font-size:15px;a:hover{color:blue;}" >
                                                <i class="bi bi-info-square-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                          </table>
                    </div>
                  </div>

            </div>
          </div>
        </div>
      </div>
    </section>
</main>

@endsection

@push('css')
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">


@endpush

@push('script')

    <!-- jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <script>
        // $(document).ready(function() {
        // $('#semua_dosen').DataTable();
        // });

        $(document).ready(function() {
        $('#semua_dosen').DataTable({
            "paging":   true,
            "ordering": true,
            "info":     true,
            "scrollX": true
        });
        });
    </script>


    <script>
        var showDivButton = document.getElementById('showDiv');
        var myDiv = document.getElementById('myDiv');
        var divVisible = false;

        showDivButton.addEventListener('click', function() {
        if (divVisible) {
            myDiv.style.display = 'none';
            divVisible = false;
        } else {
            myDiv.style.display = 'block';
            divVisible = true;
        }
        });
    </script>

    
    <script>
        function confirmDelete(event, id) {
            event.preventDefault();
            if (window.confirm('Kamu yakin akan menghapus akun ini?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>

    
@endpush