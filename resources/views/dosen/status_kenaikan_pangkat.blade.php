@extends('layouts.master')

@section('content')

@include('layouts.header')

<main id="main" class="main">
    <section class="section">
        <div class="row">
                <div class="col-lg">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mt-5 text-center" style="color:#012970;"><strong>Status Kenaikan Pangkat</strong></h2>

                            <div class="row">
                                <div class="col">
                                    <h5 class="text-center my-2"><strong>Status : 
                                        @if(auth()->user()->status == 'Sedang Diperiksa')
                                            <span style="color:#e1f014">Sedang Diperiksa</span><br><br>
                                            Berkas yang diunggah
                                        @elseif(auth()->user()->status == 'Disetujui')
                                            <span style="color:#11b40c">Disetujui</span>
                                        @elseif(auth()->user()->status == 'Ditolak')
                                            <span style="color:#bd0808">Ditolak</span><br><br>
                                            Berkas yang diunggah
                                        @endif
                                    </strong></h5>
                                </div>
                            </div>
                            
                            @if(auth()->user()->status == 'Sedang Diperiksa' || auth()->user()->status == 'Ditolak')
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <div class="col">
                                            <p style="color:#012970;" style="margin-top: -50px;"><strong>1. Kartu Pegawai & NIP Baru</strong></strong></p>
                                            @if(auth()->user()->berkas_kenaikan_pangkat_reguler->kartu_pegawai_nip_baru_bkn == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . auth()->user()->berkas_kenaikan_pangkat_reguler->kartu_pegawai_nip_baru_bkn) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <p style="color:#012970;"><strong>2. SK Pengangkatan Pertama (CPNS)</strong></p>
                                            @if(auth()->user()->berkas_kenaikan_pangkat_reguler->sk_cpns == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . auth()->user()->berkas_kenaikan_pangkat_reguler->sk_cpns) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <p style="color:#012970;"><strong>3. SK Pangkat Terakhir</strong></p>
                                            @if(auth()->user()->berkas_kenaikan_pangkat_reguler->sk_pangkat_terakhir == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . auth()->user()->berkas_kenaikan_pangkat_reguler->sk_pangkat_terakhir) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <p style="color:#012970;"><strong>4. SK Jabatan Fungsional Terakhir dan PAK</strong></p>
                                            @if(auth()->user()->berkas_kenaikan_pangkat_reguler->sk_jabfung_terakhir_dan_pak == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . auth()->user()->berkas_kenaikan_pangkat_reguler->sk_jabfung_terakhir_dan_pak) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <p style="color:#012970;"><strong>5. PPK & SKP dua tahun terakhir</strong></p>
                                            @if(auth()->user()->berkas_kenaikan_pangkat_reguler->ppk_dan_skp == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . auth()->user()->berkas_kenaikan_pangkat_reguler->ppk_dan_skp) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>
                                        
                                        
                                    </div>

                                    <div class="col-6">
                                        <div class="col">
                                            <p style="color:#012970;"><strong>6. Ijazah Terakhir (Jika gelar belum tercantum dalam SK Pangkat Terakhir)</strong></p>
                                            @if(auth()->user()->berkas_kenaikan_pangkat_reguler->ijazah_terakhir == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . auth()->user()->berkas_kenaikan_pangkat_reguler->ijazah_terakhir) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <p style="color:#012970;"><strong>7. SK Tugas Belajar atau Surat Izin Studi (Sesuai no. 5)</strong></p>
                                            @if(auth()->user()->berkas_kenaikan_pangkat_reguler->sk_tugas_belajar_atau_surat_izin_studi == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . auth()->user()->berkas_kenaikan_pangkat_reguler->sk_tugas_belajar_atau_surat_izin_studi) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>

                                        <div class="col">
                                            <p style="color:#012970;"><strong>Keterangan Membina Mata Kuliah dari Jurusan</strong></p>
                                            @if(auth()->user()->berkas_kenaikan_pangkat_reguler->keterangan_membina_mata_kuliah_dari_jurusan == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . auth()->user()->berkas_kenaikan_pangkat_reguler->keterangan_membina_mata_kuliah_dari_jurusan) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <p style="color:#012970;"><strong>Surat Pernyataan Setiap Bidang Tridharma (beserta bukti pendukung)</strong></p>
                                            @if(auth()->user()->berkas_kenaikan_pangkat_reguler->surat_pernyataan_setiap_bidang_tridharma == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . auth()->user()->berkas_kenaikan_pangkat_reguler->surat_pernyataan_setiap_bidang_tridharma) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @endif
                          
                            
                            
                        
                    </div>
                </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="text-center mb-5">
                                

                    <div class="d-flex justify-content-center">
                        <a href="{{ route('dosen.index') }}" class="d-block btn btn-primary btn-lg rounded-pill mx-2" style="color:#012970; background-color:#eeff00;  border:none;"><strong>Kembali</strong></a>
                    @if(auth()->user()->status == 'Sedang Diperiksa' || auth()->user()->status == 'Ditolak')
                            <a href="#" class="d-block btn btn-primary btn-lg rounded-pill mx-2" style="background-color:#ff0000;  border:none;" data-bs-toggle="modal" data-bs-target="#verticalycentered"><strong>Keterangan</strong></a>
                            <a href="{{ route('dosen.sanggah') }}" class="d-block btn btn-primary btn-lg rounded-pill mx-2" style="color:#012970; background-color:#51ff00;  border:none;"><strong>Sanggah</strong></a>
                    @endif
                        </div>
                </div>
                <!-- Start The Modal -->
                <div class="modal fade" id="verticalycentered" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="d-flex justify-content-center">
                            <h3 class="modal-title text-center mt-2" style="color:#012970;"><strong>Alasan Penolakan</strong></h3>
                        </div>
                        

                        
                            <div class="modal-body">
                                <div class="form-floating mb-3">
                                    <p>
                                        {!! auth()->user()->tanggapan !!}
                                    </p>
                                </div>
                                                                        
                            </div>
                            
                       
                    </div>
                    </div>
                </div>
                <!-- End Vertically centered Modal-->
            </div>
        </div>
    </section>
</main>
@endsection

@push('script')
    

@endpush