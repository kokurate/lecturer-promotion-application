<?php

namespace Database\Seeders;

use App\Models\tipe_kegiatan_pendidikan_dan_pengajaran;
use Illuminate\Database\Seeder;

class TipeKegiatanPendidikanDanPengajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tipe_kegiatan_pendidikan_dan_pengajaran::create(['nama_kegiatan' => 'Mengikuti Pendidikan Formal']); // 1 
        tipe_kegiatan_pendidikan_dan_pengajaran::create(['nama_kegiatan' => 'Mengikuti Diklat Pra-Jabatan']); // 2
        tipe_kegiatan_pendidikan_dan_pengajaran::create(['nama_kegiatan' => 'Melaksanakan Perkuliahan']); // 3 
        tipe_kegiatan_pendidikan_dan_pengajaran::create(['nama_kegiatan' => 'Membimbing Seminar Mahasiswa (Setiap Mahasiswa)']); // 4 
        tipe_kegiatan_pendidikan_dan_pengajaran::create(['nama_kegiatan' => 'Membimbing KKN, Praktik Kerja Nyata, Praktik Kerja Lapangan']); // 5 
        tipe_kegiatan_pendidikan_dan_pengajaran::create(['nama_kegiatan' => 'Membimbing Disertasi, Tesis, Skripsi, dan Laporan Hasil Studi']); // 6
        tipe_kegiatan_pendidikan_dan_pengajaran::create(['nama_kegiatan' => 'Bertugas Sebagai Penguji Pada Ujian Akhir/Profesi']); // 7
        tipe_kegiatan_pendidikan_dan_pengajaran::create(['nama_kegiatan' => 'Membina Kegiatan Mahasiswa Di Bidang Akademik Dan Kemahasiswaan']); // 8
        tipe_kegiatan_pendidikan_dan_pengajaran::create(['nama_kegiatan' => 'Mengembangkan Program Kuliah']); // 9
        tipe_kegiatan_pendidikan_dan_pengajaran::create(['nama_kegiatan' => 'Mengembangkan Bahan Pengajaran']); // 10
        tipe_kegiatan_pendidikan_dan_pengajaran::create(['nama_kegiatan' => 'Menyampaikan Orasi Ilmiah']); // 11
        tipe_kegiatan_pendidikan_dan_pengajaran::create(['nama_kegiatan' => 'Menduduki Jabatan Pimpinan Perguruan Tinggi']); // 12
        tipe_kegiatan_pendidikan_dan_pengajaran::create(['nama_kegiatan' => 'Membimbing Dosen Yang Mempunyai Jabatan Akademik Lebih Rendah']); // 13
        tipe_kegiatan_pendidikan_dan_pengajaran::create(['nama_kegiatan' => 'Melaksanakan Kegiatan Detasering Dan Pencangkokan Di Luar Institusi']); // 14
        tipe_kegiatan_pendidikan_dan_pengajaran::create(['nama_kegiatan' => 'Melaksanakan Pengembangan Diri Untuk Meningkatkan Kompetensi']); // 15
    }
}
