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
                    <div class="col-6">
                      <h3 class="mb-4 mt-4"><strong>Simulasi PAK</strong></h3>
                    </div>
                </div>

                <a href="{{ route('dosen.simulasi.export') }}" target="__blank"
                  class="btn btn-primary ">Export</a>

                  <!-- ========== Data Table ========-->
                  <div class="row" >
                    <div class="col-lg my-3">
                        <h2></h2>
                        <table id="simulasi" class="table table-hover" style="width:100%">
                            <thead>
                              <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">Kategori PAK</th>
                                <th scope="col" class="text-center">Banyaknya</th>
                                <th scope="col" class="text-center">Jumlah Kredit</th>
                              </tr>
                            </thead>
                            <tbody>
                            @foreach($kategori_pak as $data)
                                <tr>
                                  <th scope="row"></th>
                                  @if($data->slug == 'pendidikan-dan-pengajaran')
                                    <td class="text-center"><a href="{{ route($data->slug) }}" class="text-decoration:none" style="color:rgb(0, 0, 0)">{{ $data->nama }}</a></td>
                                    <td class="text-center"><p style="color:#012970;">{{ $data->pak_kegiatan_pendidikan_dan_pengajaran_count }}</p></td>
                                    <td class="text-center"><p style="color:#012970;">{{ $data->pak_kegiatan_pendidikan_dan_pengajaran->sum('angka_kredit') }}</p></td>
                                  @endif
                                  @if($data->slug == 'penelitian')
                                    <td class="text-center"><a href="{{ route($data->slug) }}" class="text-decoration:none" style="color:rgb(0, 0, 0)">{{ $data->nama }}</a></td>
                                    <td class="text-center"><p style="color:#012970;">{{ $data->pak_kegiatan_penelitian_count }}</p></td>
                                    <td class="text-center"><p style="color:#012970;">{{ $data->pak_kegiatan_penelitian->sum('angka_kredit') }}</p></td>

                                  @endif
                                  @if($data->slug == 'pengabdian-pada-masyarakat')
                                    <td class="text-center"><a href="{{ route($data->slug) }}" class="text-decoration:none" style="color:rgb(0, 0, 0)">{{ $data->nama }}</a></td>
                                    <td class="text-center"><p style="color:#012970;">{{ $data->pak_kegiatan_pengabdian_pada_masyarakat_count }}</p></td>
                                    <td class="text-center"><p style="color:#012970;">{{ $data->pak_kegiatan_pengabdian_pada_masyarakat->sum('angka_kredit') }}</p></td>

                                  @endif
                                  @if($data->slug == 'penunjang-tri-dharma-pt')
                                    <td class="text-center"><a href="{{ route($data->slug) }}" class="text-decoration:none" style="color:rgb(0, 0, 0)">{{ $data->nama }}</a></td>
                                    <td class="text-center"><p style="color:#012970;">{{ $data->pak_kegiatan_penunjang_tri_dharma_pt_count }}</p></td>
                                    <td class="text-center"><p style="color:#012970;">{{ $data->pak_kegiatan_penunjang_tri_dharma_pt->sum('angka_kredit') }}</p></td>
                                  @endif
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
        <div class="card">
          <div class="card-body">
            <div class="row justify-content-between">

                <div class="col-md-3">
                  <p class="mt-3"><strong>Total Kegiatan: {{ $total_kegiatan }}</strong></p>

              </div>
              <div class="col-md-3">
                  <p class="mt-3"><strong>Jumlah Kredit: {{ $jumlah_kredit }}</strong></p>
              </div>

              <div class="col-md-6"></div>


            </div>
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
              "info":     true,
              "scrollX": true
          });
          });
      </script>


    
  @endpush