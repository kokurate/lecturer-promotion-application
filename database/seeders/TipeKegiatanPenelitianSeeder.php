<?php

namespace Database\Seeders;

use App\Models\tipe_kegiatan_penelitian;
use Illuminate\Database\Seeder;

class TipeKegiatanPenelitianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tipe_kegiatan_penelitian::create(['nama_kegiatan' => 'Menghasilkan Karya Ilmiah Sesuai Dengan Bidang Ilmunya']); // 1 
        tipe_kegiatan_penelitian::create(['nama_kegiatan' => 'Hasil Penelitian Atau Hasil Pemikiran Yang Didesiminasikan']); // 2 
        tipe_kegiatan_penelitian::create(['nama_kegiatan' => 'Hasil Penelitian Atau Pemikiran Atau Kerjasama Industri Yang Tidak Dipublikasikan (Tersimpan Dalam Perpustakaan)']); // 3 
        tipe_kegiatan_penelitian::create(['nama_kegiatan' => 'Menerjemahkan/ Menyadur Buku Ilmiah Yang Diterbitkan (Ber ISBN)']); // 4
        tipe_kegiatan_penelitian::create(['nama_kegiatan' => 'Mengedit/ Menyunting Karya Ilmiah Dalam Bentuk Buku Yang Diterbitkan (Ber ISBN)']); // 5
        tipe_kegiatan_penelitian::create(['nama_kegiatan' => 'Membuat Rancangan Dan Karya Teknologi/ Seni Yang Dipatenkan Secara Nasional Atau Internasional']); // 6
        tipe_kegiatan_penelitian::create(['nama_kegiatan' => 'Membuat Rancangan Dan Karya Teknologi Yang Tidak Dipatenkan; Rancangan Dan Karya Seni Monumental/ Seni Pertunjukan; Karya Sastra']); // 7
        tipe_kegiatan_penelitian::create(['nama_kegiatan' => 'Membuat Rancangan Dan Karya Seni/ Seni Pertunjukan Yang Tidak Mendapatkan HKI']); // 8
    }
}
