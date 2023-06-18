@extends('layouts.master')

@section('content')

@include('layouts.header')

    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col-lg">
                    <form class="inline-form" action="{{ route('truncate_tahun_ajaran') }}" method="post">
                        @method('delete')
                        @csrf
                            <div class="mt-4 mb-3">
                                <button type="submit" class="btn btn-danger" onclick="truncateData(event)">Truncate Data Tahun Ajaran</button>
                            </div>
                    </form>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tahun Ajaran</h5>
                            
                            <table id="tahun_ajaran" class="table" style="width:100%">
                                
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Tahun</th>
                                            <th>Semester</th>
                                            <th>Aktif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tahun_ajaran as $data)
                                        <tr>
                                            <td>
                                                @if($data->now == false)
                                                    <!-- delete -->
                                                    <form class="inline-form" action="{{ route('admin.simulasi.tambah_tahun_ajaran_destroy', $data->id) }}" method="post">
                                                        @method('delete')
                                                        @csrf
                                                        <div class="d-flex">
                                                            <button class="text-decoration-none border-0 p-0 m-0 " type="submit" onclick="confirmDelete(event)">
                                                                <div class="d-flex justify-content-center badge bg-danger">
                                                                    <i class="text-light bi bi-trash"></i>
                                                                </div>
                                                            </button>
                                                        </div>
                                                    
                                                    </form>
                                               

                                                    <!-- Aktifkan -->
                                                    <form class="inline-form" action="{{ route('admin.simulasi_tambah.tahun_ajaran_update', $data->id) }}" method="post">
                                                        @csrf
                                                        <div class="d-flex">
                                                            <button class="text-decoration-none border-0 p-0 m-0 " type="submit" onclick="confirmUpdate(event)">
                                                                <div class="d-flex justify-content-center badge bg-success">
                                                                    <i class="text-light bi-file-check"></i>
                                                                </div>
                                                            </button>
                                                        </div>
                                                            
                                                    </form>
                                                @endif
                                            </td>
                                            <td>{{ $data->tahun }}</td>
                                            <td>{{ $data->semester }}</td>
                                            <td>@if($data->now == 1)<strong>Ya</strong>  @else Tidak @endif</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                            </table>
                                <div class="d-flex justify-content-center">

                                    <!-- Vertically centered Modal -->
                                    <button type="button" class="mb-0 mt-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#verticalycentered" style="background-color:#012970 ">
                                        +Tambah
                                    </button>
                                </div>
                                    <div class="modal fade" id="verticalycentered" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tambah Tahun Ajaran </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form class="row g-3 my-3" action="{{ route('admin.simulasi_tambah.tahun_ajaran_store') }}" method="post">
                                                    @csrf
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                
                                                                <div class="col my-3">
                                                                    <label for="tahun" class="form-label" style="color:#012970;"><strong>Tahun</strong></label>
                                                                    <input class="form-control{{ $errors->has('tahun') ? ' is-invalid' : '' }}" type="text" name="tahun" value="{{ old('tahun') }}" required>
                                                                </div>
                                                                <div class="col my-3">
                                                                    <label for="semester" class="form-label" style="color:#012970;"><strong>Semester</strong></label>
                                                                    {{-- <input class="form-control{{ $errors->has('semester') ? ' is-invalid' : '' }}" type="text" name="semester" value="{{ old('tahun') }}" required> --}}
                                                                    <select name="semester" class="form-select">
                                                                        <option value="Ganjil" selected>Ganjil</option>
                                                                        <option value="Genap" selected>Genap</option>
                                                                    </select>

                                                                </div>
                                                            </div> <!-- End Row -->
                                                        </div> 
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color:#5276b4">Cancel</button>
                                                            <button type="submit" class="btn btn-primary" style="background-color:#012970">Submit</button>
                                                        </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div><!-- End Vertically centered Modal-->
                               

                        </div> <!-- Card Body -->
                    </div>

                </div>

                <!--  ====================== Kategori simulasi =============================== -->

                <div class="col-lg">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Kategori Simulasi</h5>
                                <table id="kategori_simulasi" class="table" style="width:100%">
                                    
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Kategori</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kategori_simulasi as $data)
                                        <tr>
                                            <td>
                                                <!-- delete -->
                                                        <form class="inline-form" action="{{ route('admin.simulasi.kategori_simulasi_destroy', $data->id) }}" method="post">
                                                            @method('delete')
                                                            @csrf
                                                            <div class="d-flex">
                                                                <button class="text-decoration-none border-0 p-0 m-0 " type="submit" onclick="confirmDelete(event)">
                                                                    <div class="d-flex justify-content-center badge bg-danger">
                                                                        <i class="text-light bi bi-trash"></i>
                                                                    </div>
                                                                </button>
                                                            </div>
                                                        
                                                        </form>
                                            </td>
                                            <td>
                                                <a id="{{ $data->id }}" href="#" onclick="showAdditionalComponents({{ $data->id }})">{{ $data->nama }}</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                            </table>

                            <!-- Modal button -->
                            <div class="d-flex justify-content-center">
                                <button type="button" class="mb-0 mt-3 btn btn-primary"  style="background-color:#012970"  data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    + Tambah
                                </button>
                            </div>
                                <!-- Modal -->
                                    
                                <div class="modal fade" id="exampleModal" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tambah Kategori Simulasi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                                <form class="row g-3 my-3" action="{{ route('admin.simulasi.kategori_simulasi_store') }}" method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <label for="nama" class="form-label" style="color:#012970;"><strong>Nama Kategori</strong></label>
                                                        <input id="nama" class="form-control{{ $errors->has('nama') ? ' is-invalid' : '' }}" type="text" name="nama" value="{{ old('nama') }}" required>
                                                        
                                                        
                                                    </div>
                                                

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color:#5276b4">Close</button>
                                                        <button type="submit" class="btn btn-primary" style="background-color:#012970">Submit</button>
                                                    </div>
                                                </form>
                                        </div>
                                          
                                    </div>
                                </div> <!-- End Modal -->
                                  

                                    

                        </div><!-- End Card Body -->
                    </div> <!-- End Card -->

                </div> <!-- End Col Lg -->
            </div> <!-- End Row -->

            <div class="row">
                
                <!-- Id 1 Pendidikan Dan Pengajaran -->
                    <div class="col" id="additionalComponents1" style="display: none;"> <!-- id 1 -->
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Tipe Kegiatan Pendidikan dan Pengajaran</h4>
                                    <table id="table-1" class="table" style="width:100%">
                                        
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Nama Kegiatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tipe_kegiatan_pendidikan_dan_pengajaran as $data)
                                            <tr>
                                                <td>
                                                    <!-- delete -->
                                                            <form class="inline-form" action="{{ route('admin.simulasi.tipe_kegiatan_pendidikan_dan_pengajaran_destroy', $data->id) }}" method="post">
                                                                @method('delete')
                                                                @csrf
                                                                <div class="d-flex">
                                                                    <button class="text-decoration-none border-0 p-0 m-0 " type="submit" onclick="confirmDelete(event)">
                                                                        <div class="d-flex justify-content-center badge bg-danger">
                                                                            <i class="text-light bi bi-trash"></i>
                                                                        </div>
                                                                    </button>
                                                                </div>
                                                            
                                                            </form>
                                                </td>
                                                <td>
                                                    {{ $data->nama_kegiatan }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                </table>

                                        <!-- Modal button -->
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="mb-0 mt-3 btn btn-primary"  style="background-color:#012970"  data-bs-toggle="modal" data-bs-target="#modal_pendidikan_dan_pengajaran">
                                            + Tambah
                                        </button>
                                    </div>
                                        <!-- Modal -->
                                            
                                        <div class="modal fade" id="modal_pendidikan_dan_pengajaran" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tambah Tipe Kegiatan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                        <form class="row g-3 my-3" action="{{ route('admin.simulasi.tipe_kegiatan_pendidikan_dan_pengajaran_store') }}" method="post">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <label for="tipe_kegiatan_pendidikan_dan_pengajaran" class="form-label" style="color:#012970;"><strong>Nama Tipe Kegiatan</strong></label>
                                                                <input id="tipe_kegiatan_pendidikan_dan_pengajaran" class="form-control{{ $errors->has('tipe_kegiatan_pendidikan_dan_pengajaran') ? ' is-invalid' : '' }}" type="text" name="tipe_kegiatan_pendidikan_dan_pengajaran" value="{{ old('tipe_kegiatan_pendidikan_dan_pengajaran') }}" required>
                                                                
                                                                
                                                            </div>
                                                        

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color:#5276b4">Close</button>
                                                                <button type="submit" class="btn btn-primary" style="background-color:#012970">Submit</button>
                                                            </div>
                                                        </form>
                                                </div>
                                                
                                            </div>
                                        </div> <!-- End Modal -->
                            </div>
                        </div>
                    </div> <!-- End Col -->

                <!-- id 2 -->
                    <div class="col" id="additionalComponents2" style="display: none;"> <!-- ID 2 -->
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">HELLO WORLD 2</h4>
                                
                            </div>
                        </div>
                    </div> <!-- End Col -->

                <!-- id 3 -->
                    <div class="col" id="additionalComponents3" style="display: none;"> <!-- ID 3 -->
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">HELLO WORLD 3</h4>
                                
                            </div>
                        </div>
                    </div> <!-- End Col -->

                <!-- id 4 -->
                    <div class="col" id="additionalComponents4" style="display: none;"> <!-- ID 4 -->
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">HELLO WORLD 4</h4>
                                
                            </div>
                        </div>
                    </div> <!-- End Col -->




            </div> <!-- End Row -->
            <br><br><br><br><br><br><br><br>

        </section>

    </main><!-- End #main -->
@endsection

@push('css')

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">


@endpush

@push('script')

    <!-- Take the data tables from this link -->
    <!-- https://datatables.net/examples/styling/bootstrap5.html -->

    <!-- jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <script>

        // Data table
            $(document).ready(function () {
                $('#tahun_ajaran').DataTable();
            });


            $(document).ready(function () {
                $('#kategori_simulasi').DataTable();
            });


            $(document).ready(function () {
                $('#table-1').DataTable();
            });

        // Modal
          var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));




    </script>


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

        function confirmUpdate(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda Ingin Mengaktifkan Tahun Ajaran Ini ?',
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

        function truncateData(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Hapus Data',
            text: 'Apakah Anda Yakin Ingin Menghapus Semua Data Tahun Ajaran Yang Tidak Aktif?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            confirmButtonColor: '#dc3545', // Warna tombol konfirmasi danger
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.closest('form').submit();
            }
        });
    }



    </script>

        <script>
            function showAdditionalComponents(dataId) {
            // Hide all additional components
            $('[id^="additionalComponents"]').hide();
        
            // Show the additional component for the clicked data ID
            $('#additionalComponents' + dataId).show();
        
            // Scroll to the additional component
            $('html, body').animate({
                scrollTop: $('#additionalComponents' + dataId).offset().top
            }, 500);
            }
        </script>

@endpush