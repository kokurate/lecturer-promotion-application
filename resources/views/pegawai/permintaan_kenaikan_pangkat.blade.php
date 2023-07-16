@extends('layouts.master')

@section('content')

@include('layouts.header')
<main id="main" class="main">
    <section class="section">

        <div class="row">
            <div class="col-lg">
              <div class="card">
                <div class="card-body">
                    <p class="mb-0 mt-4"><strong>Permintaan Kenaikan Pangkat</strong></p>
                    <br>

                    <!-- ========== Data Table ========-->
                        <div class="row" >
                            <div class="col-lg my-3">
                                <h2></h2>
                                <table id="pengajuan_dalam_proses" class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="text-center">#</th>
                                        <th scope="col" class="text-center">Nama</th>
                                        <th scope="col" class="text-center">Program Studi</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($permintaan_kenaikan as $data)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td class="text-center">{{ $data->name }}</td>
                                            <td class="text-center">{{ $data->jurusan_prodi }}</td>
                                            <td class="text-center ">
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('pegawai.ubah_status_kenaikan_pangkat', $data->email) }}" class="d-block btn btn-primary" style="background-color:#012970; "><strong><i class="bi bi-info-square-fill"></i></strong></a>
                                                </div>
                                            </td>
                                        </tr>

                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center">Tidak Ada Permintaan Kenaikan Pangkat</td>
                                            </tr>
                                        @endforelse
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
        $('#pengajuan_dalam_proses').DataTable({
            "paging":   true,
            "ordering": true,
            "info":     true
        });
        });
    </script>

@endpush