@extends('layouts.master')

@section('content')

@include('layouts.header')

<main id="main" class="main">
    <section class="section">
        <div class="row">
                <div class="col-lg">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="mt-5 text-center" style="color:#012970;"><strong>Detail Pengajuan Kenaikan Pangkat</strong></h2>

                            <div class="row">
                                <div class="col-4">
                                    <p class="text-center my-2"><strong>Nama : {{ $berkas->user->name }}</strong></p>
                                </div>
                                <div class="col-4">
                                    <p class="text-center my-2"><strong>NIP : {{ $berkas->user->nip }}</strong></p>
                                </div>
                                <div class="col-4">
                                    <p class="text-center my-2"><strong>NIDN : {{ $berkas->user->nidn }}</strong></p>
                                </div>
                                <hr>
                            </div>
                            <form action="{{ route('pdf.merge', $berkas->user->email) }}" method="post">
                                @csrf
                                <input type="hidden" name="kartu_pegawai_nip_baru_bkn" value="{{ asset('storage/'. $berkas->kartu_pegawai_nip_baru_bkn) }}">
                                <input type="hidden" name="sk_cpns" value="{{ asset('storage/'. $berkas->sk_cpns) }}">
                                <input type="hidden" name="sk_pangkat_terakhir" value="{{ asset('storage/'. $berkas->sk_pangkat_terakhir) }}">
                                <input type="hidden" name="sk_jabfung_terakhir_dan_pak" value="{{ asset('storage/'. $berkas->sk_jabfung_terakhir_dan_pak) }}">
                                <input type="hidden" name="ppk_dan_skp" value="{{ asset('storage/'. $berkas->ppk_dan_skp) }}">
                                <input type="hidden" name="ijazah_terakhir" value="{{ asset('storage/'. $berkas->ijazah_terakhir) }}">
                                <input type="hidden" name="sk_tugas_belajar_atau_surat_izin_studi" value="{{ asset('storage/'. $berkas->sk_tugas_belajar_atau_surat_izin_studi) }}">
                                <input type="hidden" name="keterangan_membina_mata_kuliah_dari_jurusan" value="{{ asset('storage/'. $berkas->keterangan_membina_mata_kuliah_dari_jurusan) }}">
                                <input type="hidden" name="surat_pernyataan_setiap_bidang_tridharma" value="{{ asset('storage/'. $berkas->surat_pernyataan_setiap_bidang_tridharma) }}">

                                <div class="row">
                                    <div class="col-6">
                                        <div class="col">
                                            <p style="color:#012970;" style="margin-top: -50px;"><strong>1. Kartu Pegawai & NIP Baru</strong></strong></p>
                                            @if($berkas->kartu_pegawai_nip_baru_bkn == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . $berkas->kartu_pegawai_nip_baru_bkn) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <p style="color:#012970;"><strong>2. SK Pengangkatan Pertama (CPNS)</strong></p>
                                            @if($berkas->sk_cpns == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . $berkas->sk_cpns) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <p style="color:#012970;"><strong>3. SK Pangkat Terakhir</strong></p>
                                            @if($berkas->sk_pangkat_terakhir == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . $berkas->sk_pangkat_terakhir) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <p style="color:#012970;"><strong>4. SK Jabatan Fungsional Terakhir dan PAK</strong></p>
                                            @if($berkas->sk_jabfung_terakhir_dan_pak == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . $berkas->sk_jabfung_terakhir_dan_pak) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <p style="color:#012970;"><strong>5. PPK & SKP dua tahun terakhir</strong></p>
                                            @if($berkas->ppk_dan_skp == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . $berkas->ppk_dan_skp) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>
                                        
                                        
                                    </div>

                                    <div class="col-6">
                                        <div class="col">
                                            <p style="color:#012970;"><strong>6. Ijazah Terakhir (Jika gelar belum tercantum dalam SK Pangkat Terakhir)</strong></p>
                                            @if($berkas->ijazah_terakhir == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . $berkas->ijazah_terakhir) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <p style="color:#012970;"><strong>7. SK Tugas Belajar atau Surat Izin Studi (Sesuai no. 5)</strong></p>
                                            @if($berkas->sk_tugas_belajar_atau_surat_izin_studi == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . $berkas->sk_tugas_belajar_atau_surat_izin_studi) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>

                                        <div class="col">
                                            <p style="color:#012970;"><strong>Keterangan Membina Mata Kuliah dari Jurusan</strong></p>
                                            @if($berkas->keterangan_membina_mata_kuliah_dari_jurusan == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . $berkas->keterangan_membina_mata_kuliah_dari_jurusan) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <p style="color:#012970;"><strong>Surat Pernyataan Setiap Bidang Tridharma (beserta bukti pendukung)</strong></p>
                                            @if($berkas->surat_pernyataan_setiap_bidang_tridharma == 'tidak-upload.pdf') <p style="color:red">Tidak Mengupload file</p>
                                            @else
                                                <a href="{{ asset('storage/' . $berkas->surat_pernyataan_setiap_bidang_tridharma) }}" class="badge bg-secondary mb-3" target="_blank">
                                                Open PDF 
                                                </a>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                          

                            <div class="text-center my-5">
                                <h4 class="text-center mb-3" style="color:red;">Jika pengajuan diterima jangan lupa untuk mendownload file pdf !!!</h4>
                                
                                @if(session('success'))
                                    <div class="alert alert-success" role="alert">
                                        <a href="{{ asset('storage/' . $berkas->merge) }}" style="color:rgb(255, 0, 0);" target="__blank">Lihat & Download File PDF </a>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-center">
                                   
                                        <a href="{{ route('pegawai.pengajuan_masuk_user', $berkas->user->email) }}" class="d-block btn btn-primary btn-lg rounded-pill mx-2" style="color:#012970; background-color:#eeff00; padding-left: 50px; padding-right: 50px;border:none;"><strong>Kembali</strong></a>
                                            
                                        <button type="submit" class="d-block  btn btn-primary btn-lg rounded-pill mx-2" style="background-color:#15ff00; padding-left: 50px; padding-right: 50px;border:none;"><strong>Terima</strong></button>
                            </form>    
                                        {{-- <a href="#" class="btn btn-primary btn-lg rounded-pill" style="background-color:#15ff00; padding-left: 50px; padding-right: 50px;border:none;"><strong>Terima</strong></a> --}}
                                        <a href="#" id class="mx-2 btn btn-primary btn-lg rounded-pill" style="background-color:#ff0000; padding-left: 50px; padding-right: 50px;border:none;" data-bs-toggle="modal" data-bs-target="#verticalycentered"><strong>Tolak</strong></a>
                                    
                                        <!-- Start The Modal -->
                                        <div class="modal fade" id="verticalycentered" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                              <div class="modal-content">
                                                <form action="{{ route('pegawai.pengajuan_masuk_detail_tolak_store', $berkas->user->email) }}" method="post">
                                                    @csrf
                                                    {{-- <div class="modal-header">
                                                        <div class="d-flex justify-content-start">
                                                            <p><strong>Berkas Yang Ditolak :</strong></p>
                                                        </div>
                                                    </div> --}}

                                                    <div class="modal-body">
                                                        <div class="justify-content-start">
                                                            <div class="row">
                                                                <div class="col my-2">
                                                                    <p style="color:#ff0000;"><strong>Berkas Yang Ditolak :</strong></p>
                                                                        <div class="form-check text-start text-start">
                                                                            @if(old('check_kartu_pegawai_nip_baru_bkn'))
                                                                                <input class="form-check-input" type="checkbox" name="check_kartu_pegawai_nip_baru_bkn" id="customCheck1" value="1" checked>
                                                                            @else
                                                                                <input class="form-check-input" type="checkbox" name="check_kartu_pegawai_nip_baru_bkn" id="customCheck1" value="1">
                                                                            @endif
                                                                                <label class="form-check-label" for="customCheck1">1. Kartu Pegawai & NIP Baru</label>
                                                                        </div>
                                                                        
                                                                        <div class="form-check text-start">
                                                                            @if(old('check_sk_cpns'))
                                                                                <input class="form-check-input" type="checkbox" name="check_sk_cpns" value="1" id="customCheck2" checked>
                                                                            @else
                                                                                <input class="form-check-input" type="checkbox" name="check_sk_cpns" value="1" id="customCheck2">
                                                                            @endif    
                                                                                <label class="form-check-label" for="customCheck2">2. SK Pengangkatan Pertama (CPNS)</label>
                                                                        </div>
                                                                        
                                                                        <div class="form-check text-start">
                                                                            @if(old('check_sk_pangkat_terakhir'))
                                                                                <input class="form-check-input" type="checkbox" name="check_sk_pangkat_terakhir" value="1" id="customCheck3" checked>
                                                                            @else    
                                                                                <input class="form-check-input" type="checkbox" name="check_sk_pangkat_terakhir" value="1" id="customCheck3">
                                                                            @endif    
                                                                                <label class="form-check-label" for="customCheck3">3. SK Pangkat Terakhir</label>
                                                                        
                                                                        </div>
                                                                        
                                                                        <div class="form-check text-start">
                                                                            @if(old('check_sk_jabfung_terakhir_dan_pak'))
                                                                                <input class="form-check-input" type="checkbox" name="check_sk_jabfung_terakhir_dan_pak" value="1" id="customCheck4" checked>
                                                                            @else    
                                                                                <input class="form-check-input" type="checkbox" name="check_sk_jabfung_terakhir_dan_pak" value="1" id="customCheck4">
                                                                            @endif    
                                                                                <label class="form-check-label" for="customCheck4">4. SK Jabatan Fungsional Terakhir dan PAK</label>
                                                                        
                                                                        </div>
                                                                        
                                                                        <div class="form-check text-start">
                                                                            @if(old('check_ppk_dan_skp'))
                                                                                <input class="form-check-input" type="checkbox" name="check_ppk_dan_skp" value="1" id="customCheck5" checked>
                                                                            @else    
                                                                                <input class="form-check-input" type="checkbox" name="check_ppk_dan_skp" value="1" id="customCheck5">
                                                                            @endif    
                                                                                <label class="form-check-label" for="customCheck5">5. PPK & SKP dua tahun terakhir</label>
                                                                        
                                                                        </div>
                                                                        
                                                                        <div class="form-check text-start">
                                                                            @if(old('check_ijazah_terakhir'))
                                                                                <input class="form-check-input" type="checkbox" name="check_ijazah_terakhir" value="1" id="customCheck6" checked>
                                                                            @else    
                                                                                <input class="form-check-input" type="checkbox" name="check_ijazah_terakhir" value="1" id="customCheck6">
                                                                            @endif    
                                                                                <label class="form-check-label" for="customCheck6">6. Ijazah Terakhir (Jika gelar belum tercantum dalam SK Pangkat Terakhir)</label>
                                                                        
                                                                        </div>
                                                                        
                                                                        <div class="form-check text-start">
                                                                            @if(old('check_sk_tugas_belajar_atau_surat_izin_studi'))
                                                                                <input class="form-check-input" type="checkbox" name="check_sk_tugas_belajar_atau_surat_izin_studi" value="1" id="customCheck7" checked>
                                                                            @else    
                                                                                <input class="form-check-input" type="checkbox" name="check_sk_tugas_belajar_atau_surat_izin_studi" value="1" id="customCheck7">
                                                                            @endif    
                                                                                <label class="form-check-label" for="customCheck7">7. SK Tugas Belajar atau Surat Izin Studi (Sesuai no. 5)</label>
                                                                        
                                                                        </div>
                                                                        
                                                                        <div class="form-check text-start">
                                                                            @if(old('check_keterangan_membina_mata_kuliah_dari_jurusan'))
                                                                                <input class="form-check-input" type="checkbox" name="check_keterangan_membina_mata_kuliah_dari_jurusan" value="1" id="customCheck8" checked>
                                                                            @else    
                                                                                <input class="form-check-input" type="checkbox" name="check_keterangan_membina_mata_kuliah_dari_jurusan" value="1" id="customCheck8">
                                                                            @endif    
                                                                                <label class="form-check-label" for="customCheck8">Keterangan Membina Mata Kuliah dari Jurusan</label>
                                                                        
                                                                        </div>
                                                                        
                                                                        <div class="form-check text-start">
                                                                            @if(old('check_surat_pernyataan_setiap_bidang_tridharma'))
                                                                                <input class="form-check-input" type="checkbox" name="check_surat_pernyataan_setiap_bidang_tridharma" value="1" id="customCheck9" checked>
                                                                            @else    
                                                                                <input class="form-check-input" type="checkbox" name="check_surat_pernyataan_setiap_bidang_tridharma" value="1" id="customCheck9">
                                                                            @endif    
                                                                                <label class="form-check-label" for="customCheck9">Surat Pernyataan Setiap Bidang Tridharma (beserta bukti pendukung)</label>
                                                                        
                                                                        </div>
                                                                    
                                                                       
                                            
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="d-flex justify-content-center">
                                                            <h3 class="modal-title text-center mt-2" style="color:#012970;"><strong>Alasan Penolakan</strong></h3>
                                                        </div>

                                                        <div class="form-floating mb-3">
                                                            <textarea class="form-control" name="tanggapan" style="height: 150px;" placeholder="Alasan Penolakan"></textarea>
                                                        </div>
                                                        @error('tanggapan')
                                                                <p class="text-danger my-2"><strong>{{ $message }}</strong></p>    
                                                        @enderror
                                                    
                                                    </div>
                                                    <div class="d-flex justify-content-center">
                                                        {{-- <h3 class="text-center mt-2" style="color:#ff0000;"><strong>Tolak</strong></h3> --}}
                                                        <button type="submit" class="text-center mb-3 btn btn-danger btn-xl">Tolak</button>
                                                    </div>
                                                </form>
                                              </div>
                                            </div>
                                          </div>
                                          <!-- End Vertically centered Modal-->
                                </div>

                        </div>
                    </div>
                </div>
        </div>
    </section>
</main>
@endsection

@push('script')
    

@endpush