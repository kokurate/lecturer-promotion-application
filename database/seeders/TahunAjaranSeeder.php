<?php

namespace Database\Seeders;

use App\Models\tahun_ajaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tahun_ajaran::create([
            'tahun' => '2023/2024',
            'semester' => 'Ganjil',
            'now' => '1'
        ]); // 1 
     

        tahun_ajaran::create([
            'tahun' => '2022/2023',
            'semester' => 'Genap',
            'now' => '0',
        ]); // 2


    
    }
}
