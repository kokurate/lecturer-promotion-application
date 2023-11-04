<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
   <div class="container">
        <h2 class="text-center">Simulasi PAK</h2>

        <div class="row" >
                    <div class="col-lg-12 mt-3">
                        <h2></h2>
                        <table id="simulasi" class="table table-hover table-bordered" style="width:100%">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="text-center">Kategori PAK</th>
                                <th scope="col" class="text-center">Banyaknya</th>
                                <th scope="col" class="text-center">Jumlah Kredit</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($kategori_pak as $data)
                                <tr>
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
    
        <table class="table">
            <tr>
                <td>
                    <strong>Total Kegiatan:</strong> {{ $total_kegiatan }}   
                </td>
                <td>
                </td>
                <td>
                    <strong>Jumlah Kredit:</strong> {{ $jumlah_kredit }}
                </td>
            </tr>
        </table>

   </div> <!-- CONTAINER -->
   <br><br><br><br><br><br><br><br>
   <br><br><br><br><br><br><br><br>
   <br><br><br><br><br><br><br><br>
<!-- PAGE 2-->
            <br>
           <!-- ==============   PENDIDIKAN DAN PENGAJARAN  ================= -->
           <h3 class="text-center">Pendidikan dan Pengajaran</h3>
           <div class="row">
            <div class="col">
                <div class="card custom-card-20">
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>
                                    <p class="mt-3"><strong>Mengikuti Pendidikan Formal :</strong> {{ $pendidikan_formal }}/1</p>
                                    <p class="mt-3 mb-1" style="font-size: 15px;"><strong>Perkuliahan:</strong> {{ $total_sks }} SKS</p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;"><strong>Pendidikan Dokter Klinis:</strong> {{ $pendidikan_dokter_klinis }} AK</p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;" ><strong>Membimbing Seminar Mahasiswa:</strong> {{ $membimbing_seminar_mahasiswa }} </p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;" ><strong>Membimbing KKN, PKN, PKL:</strong> {{ $membimbing_kkn_dst }} </p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;" ><strong>P1 Disertasi:</strong> {{ $p1_disertasi }} Lulusan</p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;" ><strong>P1 Tesis:</strong> {{ $p1_tesis }} Lulusan</p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;" ><strong>P1 Skripsi:</strong> {{ $p1_skripsi }} Lulusan</p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;" ><strong>P1 Laporan Akhir Studi:</strong> {{ $p1_laporan_akhir_studi }} Lulusan</p>
                                </td>
                                <td>
                                    <p class="mt-3"><strong>Mengikuti Diklat Pra-Jabatan :</strong> {{ $diklat_pra_jabatan }}/1</p>
                                    <p class="mt-3 mb-1" style="font-size: 15px;"><strong>P2 Disertasi:</strong> {{ $p2_disertasi }} Lulusan</p>
                                    <p class="mt-0 mb-1" style="font-size: 15px;"><strong>P2 Tesis:</strong> {{ $p2_tesis }} Lulusan</p>
                                    <p class="mt-0 mb-1" style="font-size: 15px;"><strong>P2 Skripsi:</strong> {{ $p2_skripsi }} Lulusan</p>
                                    <p class="mt-0 mb-1" style="font-size: 15px;"><strong>P2 Laporan Akhir Studi:</strong> {{ $p2_laporan_akhir_studi }} Lulusan</p>
                                    <p class="mt-0 mb-1" style="font-size: 15px;"><strong>Ketua Penguji:</strong> {{ $ketua_penguji }} Lulusan</p>
                                    <p class="mt-0 mb-1" style="font-size: 15px;"><strong>Anggota Penguji:</strong> {{ $anggota_penguji }} Lulusan</p>
                                    <p class="mt-0 mb-1" style="font-size: 15px;"><strong>Membina Kegiatan:</strong> {{ $membina_kegiatan_mahasiswa }} Kegiatan</p>
                                    <p class="mt-0 mb-1" style="font-size: 15px;"><strong>Mengembangkan Program Kuliah:</strong> {{ $mengembangkan_program_kuliah }} MK</p>
                                    <p class="mt-0 mb-1" style="font-size: 15px;"><strong>Orasi Ilmiah :</strong> {{ $orasi_ilmiah }} Orasi</p>
                                </td>
                                <td>
                                    <p class="mt-3 mb-1" style="font-size: 15px;"><strong>Buku Ajar:</strong> {{ $buku_ajar }} Buku</p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;"><strong>Diklat, Modul, dsb:</strong> {{ $diklat_modul }} Produk</p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;"><strong>Pembimbing Pencangkokan :</strong> {{ $pembimbing_pencangkokan }} Orang</p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;"><strong>Pembimbing Reguler :</strong> {{ $pembimbing_reguler }} Orang</p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;"><strong>Detasering Luar Instansi :</strong> {{ $detasering_luar_instansi }} Orang</p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;"><strong>Pencangkokan Luar Instansi :</strong> {{ $pencangkokan_luar_instansi }} Orang</p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;"><strong>Pengembangan Diri :</strong>{{ $pengembangan_diri }} </p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;"><strong>Menduduki Jabatan :</strong> {{ $menduduki_jabatan }} </p>
                                </td>
                            </tr>
                            <!-- Add more rows as needed -->
                        </table>
                        <hr>
                    </div> <!-- End Card Body -->
                </div> <!-- End Card -->
            </div> <!-- End Col-lg -->
        </div> <!-- End ROW -->
        
        <!-- ==============  END  PENDIDIKAN DAN PENGAJARAN  ================= -->

<!-- END PAGE 2-->

<br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br>
<br><br>
<!-- PAGE 3-->

    <h2 class="text-center">Penelitian</h2>

    <!-- ================  NEW CARD =================== -->

    <div class="row">
        <div class="col">
            <div class="card custom-card-20">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td>
                                <p class="mt-3 mb-1" style="font-size: 14px;" > K.I - Buku Referensi : <strong>{{ $k_i__buku_refrensi }} </strong></p>
                                <p class="mt-2 mb-2" style="font-size: 14px;" >K.I - Monograf : <strong>{{ $k_i__monograf }}</strong></p>
                                <p class="mt-2 mb-2" style="font-size: 14px;" >Buku Internasional :  <strong>{{ $buku_internasional }}</strong></p>
                                <p class="mt-2 mb-2" style="font-size: 14px;" >Buku Nasional : <strong>{{ $buku_nasional }}</strong> </p>
                                <p class="mt-2 mb-2" style="font-size: 14px;" >Jurnal Int. Bereputasi: <strong>{{ $jurnal_int_bereputasi }}</strong></p>
            
                            </td>
                            <td>
                                <p class="mt-3 mb-2" style="font-size: 14px;" >Jurnal Int. Indek DB Bereputasi : <strong>{{ $jurnal_int_terindek_db_bereputpasi }}</strong></p>
                                <p class="mt-2 mb-2" style="font-size: 14px;" >Jurnal Int. Indek DB Int. Luar Kategori: <strong>{{ $jurnal_int_terindek_db_int_luar_kategori_2 }}</strong></p>
                                <p class="mt-2 mb-2" style="font-size: 14px;" >Jurnal Nas. Terakreditasi <strong>{{ $jurnal_nas_terakreditasi }}</strong></p>
                                <p class="mt-2 mb-2" style="font-size: 14px;" >Jurnal Nas. Bhs. Indonesia DOAJ <strong>{{ $jurnal_nas_bhs_indonesia_doaj }}</strong></p>
                                <p class="mt-2 mb-2" style="font-size: 14px;" >Jurnal Nas. Bhs. Inggris/Lainnya DOAJ: <strong>{{ $jurnal_nas_bhs_inggris_doaj }}</strong></p>
                            
                            </td>
                            <td>
                                <p class="mt-3 mb-1" style="font-size: 14px;" >Jurnal Nasional : <strong>{{ $jurnal_nasional }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;" >Jurnal Bhs. Resmi PBB : <strong>{{ $jurnal_bhs_resmi_pbb }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;" >Dimuat dalam Prosiding Int. <strong>{{ $dimuat_dalam_prosiding_int }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;" >Dimuat dalam Prosiding Nas. <strong>{{ $dimuat_dalam_prosiding_nas }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;" >Poster dalam prosiding Int. <strong>{{ $poster_dalam_prosiding_int }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;" >Poster dalam prosiding Nas. <strong>{{ $poster_dalam_prosiding_nas }}</strong></p>

                            </td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </table>
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
                    <table class="table">
                        <tr>
                            <td>
                                <p class="mt-3 mb-1" style="font-size: 14px;" >Int. tanpa prosiding - disajikan dalam Seminar,dsb: <strong>{{ $int_tanpa_prosiding_disajikan_dalam_seminar_dsb }}</strong> </p>
                                <p class="mt-0 mb-1" style="font-size: 14px;" >Nas. tanpa prosiding - disajikan dalam Seminar,dsb: <strong>{{ $nas_tanpa_prosiding_disajikan_dalam_seminar_dsb }}</strong> </p>
                                <p class="mt-0 mb-1" style="font-size: 14px;" >Int. prosiding - disajikan dalam Seminar,dsb: <strong>{{ $int_prosiding_disajikan_dalam_seminar_dsb }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;" >Nas. prosiding - disajikan dalam Seminar,dsb: <strong>{{ $nas_prosiding_disajikan_dalam_seminar_dsb }}</strong></p>  
                                <p class="mt-0 mb-1" style="font-size: 14px;">Disajikan dalam Koran/Majalah,dsb :<strong>{{ $disajikan_dalam_koran_majalah_dsb }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;">Hasil Penelitian tidak dipublikasikan - tersimpan perpustakaan : <strong>{{ $hasil_penelitian_tidak_dipublikasikan_tersimpan_perpustakaan }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;">Menerjemahkan buku ilmiah (ISBN): <strong>{{ $menerjemahkan_buku_ilmiah_isbn }}</strong></p>
            
                            </td>
                            <td>
                                <p class="mt-3 mb-1" style="font-size: 14px;">Menyunting Karya Ilmiah bentuk buku (ISBN): <strong>{{ $menyunting_karya_ilmiah_bentuk_buku_isbn }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;">Paten Rancangan/Karya Teknologi/Seni Int.: <strong>{{ $paten_rancangan_teknologi_int }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;">Paten Rancangan/Karya Teknologi/Seni Nasional: <strong>{{ $paten_rancangan_teknologi_nas }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;">Tanpa Paten Rancangan/Karya Teknologi/Seni Int.: <strong>{{ $tanpa_paten_rancangan_teknologi_int }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;">Tanpa Paten Rancangan/Karya Teknologi/Seni Nasional: <strong>{{ $tanpa_paten_rancangan_teknologi_nas }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;">Tanpa Paten Rancangan/Karya Teknologi/Seni Lokal : <strong>{{ $tanpa_paten_rancangan_teknologi_lokal }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;">Rancangan/Karya Teknologi/Seni Tanpa HKI: <strong>{{ $tanpa_hki_rancangan_teknologi }}</strong></p>
                              
                            </td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </table>
                </div><!-- End card-body -->
            </div> <!-- End card custom-card-20 -->
        </div><!-- End col -->
    </div><!-- End row -->
    <!-- ========== End New Card =========== -->

<!-- END PAGE 3-->
<br><br><br><br><br><br><br><br><br><br>

    <div class="text-right">
        {{ auth()->user()->name }}
    </div>




    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

  </body>
</html>
