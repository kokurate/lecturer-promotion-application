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
                                    <p class="mt-3 mb-1" style="font-size: 15px;"><strong>Buku Ajar:</strong> {{ $buku_ajar }} Buku</p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;"><strong>Diklat, Modul, dsb:</strong> {{ $diklat_modul }} Produk</p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;"><strong>Pembimbing Pencangkokan :</strong> {{ $pembimbing_pencangkokan }} Orang</p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;"><strong>Pembimbing Reguler :</strong> {{ $pembimbing_reguler }} Orang</p>
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
                                    <p class="mt-2 mb-2" style="font-size: 15px;"><strong>Detasering Luar Instansi :</strong> {{ $detasering_luar_instansi }} Orang</p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;"><strong>Pencangkokan Luar Instansi :</strong> {{ $pencangkokan_luar_instansi }} Orang</p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;"><strong>Pengembangan Diri :</strong>{{ $pengembangan_diri }} </p>
                                    <p class="mt-2 mb-2" style="font-size: 15px;"><strong>Menduduki Jabatan :</strong> {{ $menduduki_jabatan }} </p>
                                </td>
                                <td>
                             
                              
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
    <p class="text-center text-muted">Nilai dalam Angka Kredit</p>

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
                                <p class="mt-0 mb-1" style="font-size: 14px;">Rancangan/Karya Teknologi/Seni Tanpa HKI: <strong>{{ $tanpa_hki_rancangan_teknologi }}</strong></p>
                            </td>
                            <td>
                                <p class="mt-3 mb-1" style="font-size: 14px;">Menyunting Karya Ilmiah bentuk buku (ISBN): <strong>{{ $menyunting_karya_ilmiah_bentuk_buku_isbn }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;">Paten Rancangan/Karya Teknologi/Seni Int.: <strong>{{ $paten_rancangan_teknologi_int }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;">Paten Rancangan/Karya Teknologi/Seni Nasional: <strong>{{ $paten_rancangan_teknologi_nas }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;">Tanpa Paten Rancangan/Karya Teknologi/Seni Int.: <strong>{{ $tanpa_paten_rancangan_teknologi_int }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;">Tanpa Paten Rancangan/Karya Teknologi/Seni Nasional: <strong>{{ $tanpa_paten_rancangan_teknologi_nas }}</strong></p>
                                <p class="mt-0 mb-1" style="font-size: 14px;">Tanpa Paten Rancangan/Karya Teknologi/Seni Lokal : <strong>{{ $tanpa_paten_rancangan_teknologi_lokal }}</strong></p>                              
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
<br><br><br><br><br><br><br><br><br><br>

<!-- PAGE 4 --> 

    <h2 class="text-center">Pengabdian Pada Masyarakat</h2>
    <p class="text-center text-muted">Nilai dalam Angka Kredit</p>

    <div class="row" >
        <div class="col-lg-12 mt-3">
            <h2></h2>
            <table id="simulasi" class="table table-borderless table-sm">
                <tr>
                    <td>
                        <p class="mt-1 mb-1" style="font-size: 14px;">Pimpinan lembaga pemerintahan, bebas setiap semester : <strong>{{ $menduduki_jabatan_pimpinan_bebas_setiap_semester }}</strong></p>
                        <p class="mt-1 mb-1" style="font-size: 14px;">Kembangkan pendidikan dan penelitian untuk masyarakat/industri : <strong>{{ $kembangkan_pendidikan_dan_penelitian_untuk_masyarakat }}</strong></p>
                        <p class="mt-1 mb-1" style="font-size: 14px;">Memberikan Latihan Dalam Satu Semester atau lebih tingkat Internasional : <strong>{{ $memberikan_latihan_dalam_satu_semester_tingkat_internasional }}</strong></p>
                        <p class="mt-1 mb-1" style="font-size: 14px;">Memberikan Latihan Dalam Satu Semester atau lebih tingkat Nasional : <strong>{{ $memberikan_latihan_dalam_satu_semester_tingkat_nasional }}</strong></p>
                        <p class="mt-1 mb-1" style="font-size: 14px;">Memberikan Latihan Dalam Satu Semester atau lebih tingkat Lokal : <strong>{{ $memberikan_latihan_dalam_satu_semester_tingkat_lokal }}</strong></p>
                        <p class="mt-1 mb-1" style="font-size: 14px;">Memberikan Latihan Kurang dari satu semester tingkat Internasional : <strong>{{ $memberikan_latihan_kurang_dari_satu_semester_tingkat_internasional }}</strong></p>
                        <p class="mt-1 mb-1" style="font-size: 14px;">Memberikan Latihan Kurang dari satu semester tingkat Nasional : <strong>{{ $memberikan_latihan_kurang_dari_satu_semester_tingkat_nasional }}</strong></p>
                        <p class="mt-1 mb-1" style="font-size: 14px;">Memberikan Latihan Kurang dari satu semester tingkat Lokal : <strong>{{ $memberikan_latihan_kurang_dari_satu_semester_tingkat_lokal }}</strong></p>
                        <p class="mt-1 mb-1" style="font-size: 14px;">Memberikan Pelayanan berdasarkan bidang keahlian : <strong>{{ $memberikan_pelayanan_bidang_keahlian }}</strong></p>
                        <p class="mt-1 mb-1" style="font-size: 14px;">Memberikan Pelayanan berdasarkan Penugasan Lembaga Perguruan Tinggi : <strong>{{ $memberikan_pelayanan_penugasan_lembaga }}</strong></p>
                        <p class="mt-1 mb-1" style="font-size: 14px;">Memberikan Pelayanan berdasarkan Fungsi/Jabatan : <strong>{{ $memberikan_pelayanan_fungsi }}</strong></p>
                        <p class="mt-1 mb-1" style="font-size: 14px;">Membuat karya yang tidak dipublikasikan : <strong>{{ $menulis_karya_pengabdian_tidak_dipublikasikan }}</strong></p>
                    </td>            
                </tr>

            </table>
        </div>
    </div>



<!-- END PAGE 4 --> 
    
<br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br>

<!-- PAGE 5 -->

    <h2 class="text-center">Penunjang Tri Dharma PT</h2>
    <p class="text-center text-muted">Nilai dalam Angka Kredit</p>

    <div class="row" >
        <div class="col-lg-12">
            <h2></h2>
            <table id="simulasi" class="table table-borderless table-sm">
                <tr>
                    <td>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Ketua dalam suatu kepanitiaan pada Perguruan Tinggi  : <strong>{{ $menjadi_ketua_panitia_perguruan_tinggi }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Anggota dalam suatu kepanitiaan pada Perguruan Tinggi  : <strong>{{ $menjadi_anggota_panitia_perguruan_tinggi }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Ketua Panitia Pusat pada Lembaga Permerintah  : <strong>{{ $menjadi_ketua_panitia_pusat_pemerintahan }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Anggota Panitia Pusat pada Lembaga Permerintah  : <strong>{{ $menjadi_anggota_panitia_pusat_pemerintahan }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Ketua Panitia Daerah pada Lembaga Permerintah  : <strong>{{ $menjadi_ketua_panitia_daerah_pemerintahan }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Anggota Panitia Daerah pada Lembaga Permerintah  : <strong>{{ $menjadi_anggota_panitia_daerah_pemerintahan }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Pengurus Organisasi Profesi Tingkat Internasional  : <strong>{{ $menjadi_anggota_profesi_internasional_sebagai_pengurus }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Anggota Atas Permintaan Organisasi Profesi Tingkat Internasional  : <strong>{{ $menjadi_anggota_profesi_internasional_sebagai_anggota_permintaan }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Anggota Organisasi Profesi Tingkat Internasional  : <strong>{{ $menjadi_anggota_profesi_internasional_sebagai_anggota }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Pengurus Organisasi Profesi Tingkat Nasional  :  <strong>{{ $menjadi_anggota_profesi_nasional_sebagai_pengurus }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Anggota Atas Permintaan Organisasi Profesi Tingkat Nasional  :  <strong>{{ $menjadi_anggota_profesi_nasional_sebagai_anggota_permintaan }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Anggota Organisasi Profesi Tingkat Nasional  :  <strong>{{ $menjadi_anggota_profesi_nasional_sebagai_anggota }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Mewakili PT / Lembaga Pemerintah dalam panitia Antar Lembaga  :  <strong>{{ $mewakili_pt_pemerintah_dalam_panitia_antar_lembaga }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Ketua Delegasi Nasional  :  <strong>{{ $ketua_delegasi_nasional_pertemuan_internasional }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Anggota Delegasi Nasional  :  <strong>{{ $anggota_delegasi_nasional_pertemuan_internasional }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Editor/Dewan Penyunting/Dewan Redaksi Jurnal Ilmiah Internasional  :  <strong>{{ $editor_jurnal_ilmiah_internasional }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Editor/Dewan Penyunting/Dewan Redaksi Jurnal Ilmiah Nasional  :  <strong>{{ $editor_jurnal_ilmiah_nasional }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Ketua Tingkat Internasional/Nasional/Regional dalam pertemuan ilmiah  :  <strong>{{ $ketua_internasional_pertemuan_ilmiah }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Anggota Tingkat Internasional/Nasional/Regional dalam pertemuan ilmiah  :  <strong>{{ $anggota_internasional_pertemuan_ilmiah }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Ketua di lingkungan Perguruan Tinggi dalam pertemuan ilmiah  :  <strong>{{ $ketua_perguruan_tinggi_pertemuan_ilmiah }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Anggota di lingkungan Perguruan Tinggi dalam pertemuan ilmiah  :  <strong>{{ $anggota_perguruan_tinggi_pertemuan_ilmiah }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Penghargaan Satya Lencana 30 tahun  :  <strong>{{ $satya_lencana_30 }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Penghargaan Satya Lencana 20 tahun  :  <strong>{{ $satya_lencana_20 }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Penghargaan Satya Lencana 10 tahun  :  <strong>{{ $satya_lencana_10 }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Penghargaan Tingkat Internasional  :  <strong>{{ $penghargaan_internasional }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Penghargaan Tingkat Nasional  :  <strong>{{ $penghargaan_nasional }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Penghargaan Tingkat Daerah  :  <strong>{{ $penghargaan_daerah }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Buku SMTA yang diedarkan secara nasional  :  <strong>{{ $buku_smta }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Buku SMTP yang diedarkan secara nasional  :  <strong>{{ $buku_smta }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Buku SD yang diedarkan secara nasional  :  <strong>{{ $buku_sd }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Prestasi di bidang Olahraga/Humaniora Tingkat Internasional  :  <strong>{{ $prestasi_olahraga_internasional }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Prestasi di bidang Olahraga/Humaniora Tingkat Nasional  :  <strong>{{ $prestasi_olahraga_nasional }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Prestasi di bidang Olahraga/Humaniora Tingkat Daerah  :  <strong>{{ $prestasi_olahraga_daerah }}</strong></p>
                        <p class="mt-0 mb-0" style="font-size: 14px;">Keanggotaan dalam tim penilai jabatan akademik dosen  :  <strong>{{ $keanggotaan_tim_penilai }}</strong></p>
                    </td>            
                </tr>

            </table>
        </div>
    </div>

<!-- END PAGE 5 -->

<br><br><br><br>
<br><br>
<div class="text-right">
        {{ auth()->user()->name }}
    </div>




    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

  </body>
</html>
