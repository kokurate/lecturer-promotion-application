<?php

namespace Database\Seeders;

use App\Models\kategori_pak;
use Illuminate\Database\Seeder;

class KategoriPakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        kategori_pak::create([
            'nama' => 'Pendidikan dan Pengajaran',
            'slug' => 'pendidikan-dan-pengajaran',
        ]); // 1 
    
        
        // kategori_pak::create([
        //     'nama' => 'Penelitian',
        //     'slug' => 'penelitian'
        // ]); // 2

        // kategori_pak::create([
        //     'nama' => 'Pengabdian Pada Masyarakat',
        //     'slug' => 'pengabdian-pada-masyarakat',
        // ]); // 3 
   
        
        // kategori_pak::create([
        //     'nama' => 'Penunjang Tri Dharma PT',
        //     'slug' => 'penunjang-tri-dharma-pt'
        // ]); // 4

    }
}
