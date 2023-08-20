@extends('layouts.master')

@section('content')

@include('layouts.header')

<main id="main" class="main">


    <section class="section">
      


        <!--=== Start The Card =================================================== -->
      <div class="row">
        <div class="col-lg">
          <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                      <h4 class="mb-1 mt-4" style="color:#012970;"><strong>Simulasi PAK Kegiatan Pendidikan & Pengajaran</strong></h4>
                        <div class="row justify-content-between">
                            @foreach($tahun_ajaran as $data)
                              <div class="col-4">
                                  <h5 class="mt-1" style="color:#012970;"><strong>Tahun Ajaran</strong> : {{ $data->tahun }}</h4>
                              </div>
                              <div class="col-3">
                                  <h5 class="mt-1" style="color:#012970;"><strong>Semester</strong> : {{ $data->semester }}</h4>
                              </div>
                            @endforeach
                            <div class="col-5 text-end">
                              
                                    <a href="{{ route('pendidikan_dan_pengajaran_tambah') }}" class="mb-0 mt-0 btn btn-primary" style="background-color:#012970 ">+ Tambah</a>
                                
                            </div>
                        </div>
                      
                    </div>
                </div>

                

                  <!-- ========== Data Table ========-->
                  <div class="row" >
                    <div class="col-lg my-3">
                        <h2></h2>
                        <table id="simulasi" class="table table-hover">
                            <thead>
                              <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">Kegiatan</th>
                                <th scope="col" class="text-center">Tipe Kegiatan</th>
                                <th scope="col" class="text-center">SKS</th>
                                <th scope="col" class="text-center">Angka Kredit</th>
                                <th scope="col" class="text-center">Semester</th>
                                <th scope="col" class="text-center">T.A</th>
                              </tr>
                            </thead>
                            <tbody>
                            @foreach($all as $data)
                                <tr>
                                    <th scope="row">
                                        <div class="d-flex justify-content-center ">
                                          <form action="{{ route('pendidikan_dan_pengajaran_destroy', $data->slug) }}" method="post">
                                              @method('delete')
                                              @csrf
                                              <button class="text-decoration-none border-0 p-0 m-0" type="submit" onclick="confirmDelete(event)">
                                                  <div class="d-flex justify-content-center badge bg-danger">
                                                      <i class="text-light bi bi-trash"></i>
                                                  </div>
                                              </button>
                                          </form>
                                        </div>
                                    </th>
                                    <td class="text-center"><a href="{{ route('pendidikan_dan_pengajaran_detail', $data->slug)  }}" class="text-decoration:none" style="color:rgb(0, 0, 0)">{{ $data->kegiatan }}</a></td>
                                    <td class="text-center">{{ $data->tipe_kegiatan }}</a></td>
                                    <td class="text-center"><p style="color:#012970;">{{ $data->sks }}</p></td>
                                    <td class="text-center"><p style="color:#012970;">{{ $data->angka_kredit }}</p></td>
                                    <td class="text-center"><p style="color:#012970;">{{ optional($data->tahun_ajaran)->semester }}</p></td>
                                    <td class="text-center"><p style="color:#012970;">{{ optional($data->tahun_ajaran)->tahun }}</p></td>
                                    
                                </tr>
                            @endforeach 
                            </tbody>
                          </table>
                    </div>
                  </div>

            </div>
          </div>
        </div>
      </div> <!-- End ROW Card 1 -->

    
    
    <!-- ========================== Start New Card  ========================-->
    <div class="row">
      <div class="col">
        <div class="card custom-card-30">
          <div class="card-body">
            <div class="row justify-content-between">

                <div class="col-md-3">
                  <p class="mt-3" ><strong>Total Kredit: {{ $total_kredit }}</strong></p>

              </div>
              <div class="col-md-4">
                  <p class="mt-3"><strong>Mengikuti Pendidikan Formal : {{ $pendidikan_formal }}/1</strong></p>
              </div>

              <div class="col-md-5">
                <p class="mt-3"><strong>Mengikuti Diklat Pra-Jabatan : {{ $diklat_pra_jabatan }}/1</strong></p>
              </div>


            </div>
          </div> <!-- End Card Body -->
        </div> <!-- End Card -->
      </div> <!-- End Col-lg -->
    </div> <!-- End ROW -->


    <!-- ================  NEW CARD =================== -->
     
      <div class="row">
        <div class="col">
          <div class="card custom-card-20">
            <div class="card-body">
              <div class="row justify-content-between">

                  <div class="col-md-4">
                    <p class="mt-3 mb-1" style="font-size: 15px;" >Perkuliahan: {{ $total_sks }} </p>
                    <p class="mt-2 mb-2" style="font-size: 15px;" >Pendidikan Dokter Klinis: {{ $pendidikan_dokter_klinis }} </p>
                    <p class="mt-2 mb-2" style="font-size: 15px;" >Membimbing Seminar Mahasiswa: {{ $membimbing_seminar_mahasiswa }} </p>
                    <p class="mt-2 mb-2" style="font-size: 15px;" >Membimbing KKN, PKN, PKL: {{ $membimbing_kkn_dst }} </p>
                    <p class="mt-2 mb-2" style="font-size: 15px;" >P1 Disertasi: {{ $p1_disertasi }} </p>
                    <p class="mt-2 mb-2" style="font-size: 15px;" >P1 Tesis: {{ $p1_tesis }} </p>
                    <p class="mt-2 mb-2" style="font-size: 15px;" >P1 Skripsi: {{ $p1_skripsi }} </p>
                    <p class="mt-2 mb-2" style="font-size: 15px;" >P1 Laporan Akhir Studi: {{ $p1_laporan_akhir_studi }} </p>

                </div>
                <div class="col-md-4">
                  <p class="mt-3 mb-1" style="font-size: 15px;" >P2 Disertasi: {{ $p2_disertasi }} </p>
                  <p class="mt-0 mb-1" style="font-size: 15px;" >P2 Tesis: {{ $p2_tesis }} </p>
                  <p class="mt-0 mb-1" style="font-size: 15px;" >P2 Skripsi: {{ $p2_skripsi }} </p>
                  <p class="mt-0 mb-1" style="font-size: 15px;" >P2 Laporan Akhir Studi: {{ $p2_laporan_akhir_studi }} </p>
                  <p class="mt-0 mb-1" style="font-size: 15px;" >Ketua Penguji: {{ $ketua_penguji }} </p>
                  <p class="mt-0 mb-1" style="font-size: 15px;" >Anggota Penguji: {{ $anggota_penguji }} </p>
                  <p class="mt-0 mb-1" style="font-size: 15px;" >Membina Kegiatan: {{ $membina_kegiatan_mahasiswa }} </p>
                  <p class="mt-0 mb-1" style="font-size: 15px;" >Mengembangkan Program Kuliah: {{ $mengembangkan_program_kuliah }} </p>
                  <p class="mt-0 mb-1" style="font-size: 15px;">Orasi Ilmiah : {{ $orasi_ilmiah }} </p>

                </div>

                <div class="col-md-4">
                  <p class="mt-3 mb-1" style="font-size: 15px;">Buku Ajar : {{ $buku_ajar }} </p>
                  <p class="mt-2 mb-2" style="font-size: 15px;">Diklat, Modul, dsb : {{ $diklat_modul }} </p>
                  <p class="mt-2 mb-2" style="font-size: 15px;">Pembimbing Pencangkokan : {{ $pembimbing_pencangkokan }} </p>
                  <p class="mt-2 mb-2" style="font-size: 15px;">Pembimbing Reguler : {{ $pembimbing_reguler }} </p>
                  <p class="mt-2 mb-2" style="font-size: 15px;">Detasering Luar Instansi : {{ $detasering_luar_instansi }} </p>
                  <p class="mt-2 mb-2" style="font-size: 15px;">Pencangkokan Luar Instansi : {{ $pencangkokan_luar_instansi }} </p>
                  <p class="mt-2 mb-2" style="font-size: 15px;">Pengembangan Diri {{ $pengembangan_diri }} </p>
                  <p class="mt-2 mb-2" style="font-size: 15px;">Menduduki Jabatan : {{ $menduduki_jabatan }} </p>
                </div>


              </div> <!-- End ROW -->
              <hr>
            </div> <!-- End Card Body -->
          </div> <!-- End Card -->
        </div> <!-- End Col-lg -->
      </div> <!-- End ROW -->


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
          $('#simulasi').DataTable({
              "paging":   true,
              "ordering": true,
              "info":     true
          });
          });
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
      </script>

    
  @endpush