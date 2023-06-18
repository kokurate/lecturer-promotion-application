<?php

namespace Database\Seeders;

use App\Models\pak_kegiatan_pendidikan_dan_pengajaran;
use Illuminate\Database\Seeder;

class PakKegiatanPendidikanDanPengajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        pak_kegiatan_pendidikan_dan_pengajaran::create([
            'kategori_pak_id' => 2,
            'user_id' => 3,
            'kegiatan' => 'Menjadi Doktor',
            'tipe_kegiatan' => 'Mengikuti Pendidikan Formal',
            'slug' => 'testing-slug-0214',
        ]); // 1 

        pak_kegiatan_pendidikan_dan_pengajaran::create([
            'kategori_pak_id' => 2,
            'user_id' => 3,
            'kegiatan' => 'Menjadi Magister ',
            'tipe_kegiatan' => 'Mengikuti Pendidikan Formal',
            'slug' => 'testing-slug-0214',
        ]); // 2
    }
}
