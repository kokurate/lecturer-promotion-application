@extends('layouts.master')

@section('content')

@include('layouts.header')

<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h2>
                            <a href="{{ route('pegawai.semua_dosen') }}" class="mt-5 btn btn-light" style="color: #012970; font-weight: bold; text-decoration: none;" onmouseover="this.style.color='red'" onmouseout="this.style.color='#012970'">
                                <i class="bi bi-arrow-return-left"></i>  Kembali 
                            </a>
                        </h2>
                        <h2 class="mt-5" style="color:#012970;"><strong>Detail</strong></h2>
                        <h1 style="color:#012970;">_______</h1>
                        <h3 style="color:#cc0808;">{{ $user->pangkat->jabatan_fungsional }} , {{ $user->pangkat->golongan }} , {{ $user->pangkat->pangkat }}</h2>
                        <h3 style="color:#012970;">{{ $user->name }}, </h3>
                        <h4 class="my-2"  style="color:#012970;">Dosen <strong>{{ $user->jurusan_prodi }}</strong></h4>
                        <h4 class="my-2 mb-2"  style="color:#012970;"><strong>{{ $user->fakultas }}</strong></h4>
                        <p style="color:#012970;"><strong>NIP  :   {{ $user->nip ?? 'Dosen Belum Memasukkan NIP'  }} </strong></p>
                        <p style="color:#012970;"><strong>NIDN  : {{ $user->nidn ?? 'Dosen Belum Memasukkan NIDN'  }} </strong></p>
                        
                     
                            @if(optional($status_kenaikan_pangkat)->status == null)
                            @elseif($status_kenaikan_pangkat->status == 'Tersedia')
                            <p style="color:#012970;"><strong>Kenaikan Pangkat  : 
                                <span style="color:#00ff66">{{ $status_kenaikan_pangkat->status }}</span>
                            </strong></p>
                            <p style="color:#012970;"><strong>Naik ke golongan {{ $status_kenaikan_pangkat->golongan }}</strong></p>

                            @elseif($status_kenaikan_pangkat->status == 'Belum Tersedia')
                            <p style="color:#012970;"><strong>Kenaikan Pangkat  :
                                <span style="color:#cc0808">{{ $status_kenaikan_pangkat->status }}</span>
                            </strong></p>
                            @endif
                        
                        <!-- ======================= Trigger =========== -->
                        @if(optional($status_kenaikan_pangkat)->status == null)
                            <div class="d-flex justify-content-center">
                                <a href="#" id="showDiv" class="text-center my-3" style="color: #012970; font-weight: bold; text-decoration: none;" onmouseover="this.style.color='red'" onmouseout="this.style.color='#012970'" data-bs-toggle="modal" data-bs-target="#disablebackdrop">+ Ubah Status Kenaikan Pangkat</a>
                            </div>
                        @elseif($status_kenaikan_pangkat->status == 'Belum Tersedia')
                            <div class="d-flex justify-content-center">
                                <a href="#" id="showDiv" class="text-center my-3" style="color: #012970; font-weight: bold; text-decoration: none;" onmouseover="this.style.color='red'" onmouseout="this.style.color='#012970'" data-bs-toggle="modal" data-bs-target="#disablebackdrop">+ Ubah Status Kenaikan Pangkat</a>
                            </div>
                        @endif

                        <!-- Disabled Backdrop Modal -->

                                <div class="modal fade" id="disablebackdrop" tabindex="-1" data-bs-backdrop="false">
                                    <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ubah Status Kenaikan Pangkat</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('pegawai.ubah_status_kenaikan_pangkat_store', $user->email) }}" method="post">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="col">
                                                    <p><strong>
                                                        Ubah Status = <span style="color:#00ff66">Tersedia</span>
                                                    </strong></p>
                                                    </div>
                                                <div class="col">
                                                    <label for="golongan" class="mt-3 form-label"><strong>Naik Ke Golongan</strong></label>
                                                    
                                                    <label for="golongan" class="mt-3 form-label"><strong>{{ $naik_ke_pangkat->golongan }}</strong></label>

                                                    <select name="golongan" class="form-select">
                                                        <option value="{{ $naik_ke_pangkat->golongan }}" selected>{{ $naik_ke_pangkat->golongan }}</option>
                                                    </select>
                                                      @error('golongan')
                                                            <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                                                    @enderror
                                                </div>    
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                    </div>
                                </div><!-- End Disabled Backdrop Modal-->

                    </div> <!-- Card Body-->
                </div> <!-- End Card -->
                
                <!-- ================== SHOW DIV =================== -->
                {{-- <div id="myDiv" style="display: none;">
                    <div class="row">
                        <div class="col-lg-6 mx-auto">
                            <p>This is my content.</p>
                        </div>
                    </div>
                </div> --}}
                <!--  ================== End SHOW DIV =================== -->

            </div> <!-- End COl-->
        </div> <!-- End Row -->
    </section>
</main>
@endsection

@push('script')
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
@endpush