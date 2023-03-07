@extends('layouts.master')

@section('content')

@include('layouts.header')
<main id="main" class="main">
    <section class="section">

        <div class="row">
            <div class="col-lg">
              <div class="card">
                <div class="card-body">
                    <p class="mb-0 mt-4"><strong>Dalam Proses</strong></p>
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
                                        <th scope="col" class="text-center">File</th>
                                        <th scope="col" class="text-center">Selesaikan Pengajuan</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($dalam_proses as $data)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td class="text-center">{{ $data->name }}</td>
                                            <td class="text-center">{{ $data->jurusan_prodi }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ asset('storage/' . $data->berkas_kenaikan_pangkat_reguler->merge) }}" class="text-center" target="__blank">
                                                        <i class="bi bi-info-square-fill"></i>
                                                    </a>
                                                </div>    
                                            </td>
                                            <td class="text-center ">
                                                <div class="d-flex justify-content-center">
                                                    <a href="#" class="d-block btn btn-primary" style="background-color:#00ff62;  border:none;" data-bs-toggle="modal" data-bs-target="#verticalycentered{{ $data->id }}"><strong>Selesaikan</strong></a>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- Start The Modal -->
                                            <div class="modal fade" id="verticalycentered{{ $data->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <form action="{{ route('pegawai.selesai_store', $data->email) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-floating mb-3">
                                                                    <div class="d-flex justify-content-center">
                                                                        <h3 class="modal-title text-center mt-2" style="color:#012970;"><strong>Selesaikan</strong></h3>
                                                                    </div>
                                                                    <input type="hidden" name="status" value="Selesai">
                                                                    <p style="color:#012970;"><strong>Nama : {{ $data->name }}</strong></p>
                                                                    <p>{{ $data->fakultas }}, {{ $data->jurusan_prodi }}</p>
                                                                    
                                                                    <p style="color:#012970;">Golongan Sekarang : {{ $data->pangkat->jabatan_fungsional }}, {{ $data->pangkat->pangkat }}, {{ $data->pangkat->golongan }}</p>
                                                                    <p style="color:#012970;">Naik Ke Golongan : {{ $data->status_kenaikan_pangkat->golongan }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Selesaikan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Vertically centered Modal-->
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center">Tidak Ada Pengajuan Dalam Proses</td>
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

    <!-- Modal-->
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.querySelector('#verticalycentered');
            modal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var user = JSON.parse(button.getAttribute('data-user'));
                var modalBody = modal.querySelector('.modal-body p');
                modalBody.textContent = 'Name: ' + user.name + ', Jurusan Prodi: ' + user.jurusan_prodi;
            });
        });
    </script> --}}



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