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
                      <h4 class="mb-1 mt-4" style="color:#012970;"><strong>Simulasi PAK Kegiatan Penelitian</strong></h4>
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
                              
                                    <a href="{{ route('penelitian_tambah') }}" class="mb-0 mt-0 btn btn-primary" style="background-color:#012970 ">+ Tambah</a>
                                
                            </div>
                        </div>
                      
                    </div>
                </div>

                

                  <!-- ========== Data Table ========-->
                  <div class="row" >
                    <div class="col-lg my-3">
                        <h2></h2>
                        <table id="simulasi" class="table table-hover" style="width:100%">
                            <thead>
                              <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">Kegiatan</th>
                                <th scope="col" class="text-center">Tipe Kegiatan</th>
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
                                          <form action="{{ route('penelitian_destroy', $data->slug) }}" method="post">
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
                                    <td class="text-center"><a href="{{ route('penelitian_detail', $data->slug)  }}" class="text-decoration:none" style="color:rgb(0, 0, 0)">{{ $data->kegiatan }}</a></td>
                                    <td class="text-center">{{ $data->tipe_kegiatan }}</a></td>
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

                  <div class="col-md-3">
                    <p class="mt-3 mb-1" style="font-size: 14px;" > K.I - Buku Referensi : <strong>{{ $k_i__buku_refrensi }} </strong></p>
                    <p class="mt-2 mb-2" style="font-size: 14px;" >K.I - Monograf : <strong>{{ $k_i__monograf }}</strong></p>
                    <p class="mt-2 mb-2" style="font-size: 14px;" >Buku Internasional :  <strong>{{ $buku_internasional }}</strong></p>
                    <p class="mt-2 mb-2" style="font-size: 14px;" >Buku Nasional : <strong>{{ $buku_nasional }}</strong> </p>
                    <p class="mt-2 mb-2" style="font-size: 14px;" >Jurnal Int. Bereputasi: <strong>{{ $jurnal_int_bereputasi }}</strong></p>
                   
                </div>
                <div class="col-md-5">
                  <p class="mt-3 mb-2" style="font-size: 14px;" >Jurnal Int. Indek DB Bereputasi : <strong>{{ $jurnal_int_terindek_db_bereputpasi }}</strong></p>
                  <p class="mt-2 mb-2" style="font-size: 14px;" >Jurnal Int. Indek DB Int. Luar Kategori: <strong>{{ $jurnal_int_terindek_db_int_luar_kategori_2 }}</strong></p>
                  <p class="mt-2 mb-2" style="font-size: 14px;" >Jurnal Nas. Terakreditasi <strong>{{ $jurnal_nas_terakreditasi }}</strong></p>
                  <p class="mt-2 mb-2" style="font-size: 14px;" >Jurnal Nas. Bhs. Indonesia DOAJ <strong>{{ $jurnal_nas_bhs_indonesia_doaj }}</strong></p>
                  <p class="mt-2 mb-2" style="font-size: 14px;" >Jurnal Nas. Bhs. Inggris/Lainnya DOAJ: <strong>{{ $jurnal_nas_bhs_inggris_doaj }}</strong></p>
                </div>

                <div class="col-md-4">
                  <p class="mt-3 mb-1" style="font-size: 14px;" >Jurnal Nasional : <strong>{{ $jurnal_nasional }}</strong></p>
                  <p class="mt-0 mb-1" style="font-size: 14px;" >Jurnal Bhs. Resmi PBB : <strong>{{ $jurnal_bhs_resmi_pbb }}</strong></p>
                  <p class="mt-0 mb-1" style="font-size: 14px;" >Dimuat dalam Prosiding Int. <strong>{{ $dimuat_dalam_prosiding_int }}</strong></p>
                  <p class="mt-0 mb-1" style="font-size: 14px;" >Dimuat dalam Prosiding Nas. <strong>{{ $dimuat_dalam_prosiding_nas }}</strong></p>
                  <p class="mt-0 mb-1" style="font-size: 14px;" >Poster dalam prosiding Int. <strong>{{ $poster_dalam_prosiding_int }}</strong></p>
                  <p class="mt-0 mb-1" style="font-size: 14px;" >Poster dalam prosiding Nas. <strong>{{ $poster_dalam_prosiding_nas }}</strong></p>
                
                </div>


              </div> <!-- End ROW -->
              <hr>
            </div> <!-- End Card Body -->
          </div> <!-- End Card -->
        </div> <!-- End Col-lg -->
      </div> <!-- End ROW -->

      <!--  =======  End NEW Card ======== -->

      <!-- ========== Start New Card ======== -->
      <div class="row">
        <div class="col">
          <div class="card custom-card-20">
            <div class="card-body">
              <div class="row justify-content-between">

                  <div class="col-md-6">
                    <p class="mt-3 mb-1" style="font-size: 14px;" >Int. tanpa prosiding - disajikan dalam Seminar,dsb: <strong>{{ $int_tanpa_prosiding_disajikan_dalam_seminar_dsb }}</strong> </p>
                    <p class="mt-0 mb-1" style="font-size: 14px;" >Nas. tanpa prosiding - disajikan dalam Seminar,dsb: <strong>{{ $nas_tanpa_prosiding_disajikan_dalam_seminar_dsb }}</strong> </p>
                    <p class="mt-0 mb-1" style="font-size: 14px;" >Int. prosiding - disajikan dalam Seminar,dsb: <strong>{{ $int_prosiding_disajikan_dalam_seminar_dsb }}</strong></p>
                    <p class="mt-0 mb-1" style="font-size: 14px;" >Nas. prosiding - disajikan dalam Seminar,dsb: <strong>{{ $nas_prosiding_disajikan_dalam_seminar_dsb }}</strong></p>  
                    <p class="mt-0 mb-1" style="font-size: 14px;">Disajikan dalam Koran/Majalah,dsb :<strong>{{ $disajikan_dalam_koran_majalah_dsb }}</strong></p>
                    <p class="mt-0 mb-1" style="font-size: 14px;">Hasil Penelitian tidak dipublikasikan - tersimpan perpustakaan : <strong>{{ $hasil_penelitian_tidak_dipublikasikan_tersimpan_perpustakaan }}</strong></p>
                    <p class="mt-0 mb-1" style="font-size: 14px;">Menerjemahkan buku ilmiah (ISBN): <strong>{{ $menerjemahkan_buku_ilmiah_isbn }}</strong></p>

                  </div>

                  <div class="col-md-6">
                    <p class="mt-3 mb-1" style="font-size: 14px;">Menyunting Karya Ilmiah bentuk buku (ISBN): <strong>{{ $menyunting_karya_ilmiah_bentuk_buku_isbn }}</strong></p>
                    <p class="mt-0 mb-1" style="font-size: 14px;">Paten Rancangan/Karya Teknologi/Seni Int.: <strong>{{ $paten_rancangan_teknologi_int }}</strong></p>
                    <p class="mt-0 mb-1" style="font-size: 14px;">Paten Rancangan/Karya Teknologi/Seni Nasional: <strong>{{ $paten_rancangan_teknologi_nas }}</strong></p>
                    <p class="mt-0 mb-1" style="font-size: 14px;">Tanpa Paten Rancangan/Karya Teknologi/Seni Int.: <strong>{{ $tanpa_paten_rancangan_teknologi_int }}</strong></p>
                    <p class="mt-0 mb-1" style="font-size: 14px;">Tanpa Paten Rancangan/Karya Teknologi/Seni Nasional: <strong>{{ $tanpa_paten_rancangan_teknologi_nas }}</strong></p>
                    <p class="mt-0 mb-1" style="font-size: 14px;">Tanpa Paten Rancangan/Karya Teknologi/Seni Lokal : <strong>{{ $tanpa_paten_rancangan_teknologi_lokal }}</strong></p>
                    <p class="mt-0 mb-1" style="font-size: 14px;">Rancangan/Karya Teknologi/Seni Tanpa HKI: <strong>{{ $tanpa_hki_rancangan_teknologi }}</strong></p>
                  
                  </div>


              </div> <!-- End ROW Justify Content between-->
            </div><!-- End card-body -->
          </div> <!-- End card custom-card-20 -->
        </div><!-- End col -->
      </div><!-- End row -->
      <!-- ========== End New Card =========== -->


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
              "info":     true,
              "scrollX": true
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