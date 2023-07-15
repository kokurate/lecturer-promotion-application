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
                      <h4 class="mb-1 mt-4" style="color:#012970;"><strong>{{ $title }}</strong></h4>
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
                              
                                    <a href="{{ route('pengabdian_pada_masyarakat_tambah') }}" class="mb-0 mt-0 btn btn-primary" style="background-color:#012970 ">+ Tambah</a>
                                
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
                                          <form action="{{ route('pengabdian_pada_masyarakat_destroy', $data->slug) }}" method="post">
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
                                    <td class="text-center"><a href="{{ route('pengabdian_pada_masyarakat_detail', $data->slug)  }}" class="text-decoration:none" style="color:rgb(0, 0, 0)">{{ $data->kegiatan }}</a></td>
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