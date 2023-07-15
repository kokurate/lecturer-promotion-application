<?php

namespace Database\Seeders;

use App\Models\tipe_kegiatan_penunjang_tri_dharma_pt;
use Illuminate\Database\Seeder;

class TipeKegiatanPenunjangTriDharmaPtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tipe_kegiatan_penunjang_tri_dharma_pt::create(['nama_kegiatan' => 'Menjadi Anggota Dalam Suatu Panitia/ Badan Pada Perguruan Tinggi']); // 1 
        tipe_kegiatan_penunjang_tri_dharma_pt::create(['nama_kegiatan' => 'Menjadi Anggota Panitia/ Badan Pada Lembaga Pemerintah']); // 2
        tipe_kegiatan_penunjang_tri_dharma_pt::create(['nama_kegiatan' => 'Menjadi Anggota Organisasi Profesi']); // 3
        tipe_kegiatan_penunjang_tri_dharma_pt::create(['nama_kegiatan' => 'Mewakili Perguruan Tinggi/ Lembaga Pemerintah Duduk Dalam Panitia Antar Lembaga, Tiap Kepanitiaan']); // 4
        tipe_kegiatan_penunjang_tri_dharma_pt::create(['nama_kegiatan' => 'Menjadi Anggota Delegasi Nasional Ke Pertemuan Internasional']); // 5
        tipe_kegiatan_penunjang_tri_dharma_pt::create(['nama_kegiatan' => 'Berperan Serta Aktif Dalam Pengelolaan Jurnal Ilmiah (Per Tahun)']); // 6
        tipe_kegiatan_penunjang_tri_dharma_pt::create(['nama_kegiatan' => 'Berperan Serta Aktif Dalam Pertemuan Ilmiah']); // 7
        tipe_kegiatan_penunjang_tri_dharma_pt::create(['nama_kegiatan' => 'Mendapat Tanda Jasa/ Penghargaan']); // 8
        tipe_kegiatan_penunjang_tri_dharma_pt::create(['nama_kegiatan' => 'Menulis Buku Pelajaran SLTA Ke Bawah Yang Diterbitkan Dan Diedarkan Secara Nasional']); // 9
        tipe_kegiatan_penunjang_tri_dharma_pt::create(['nama_kegiatan' => 'Mempunyai Prestasi Di Bidang Olahraga/ Humaniora']); // 10
        tipe_kegiatan_penunjang_tri_dharma_pt::create(['nama_kegiatan' => 'Keanggotaan Dalam Tim Penilai Jabatan Akademik Dosen (Tiap Semester)']); // 11
    }
}
