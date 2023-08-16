<?php

namespace Database\Seeders;

use App\Models\fakultas;
use Illuminate\Database\Seeder;

class FakultasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        fakultas::create(['nama' => 'Fakultas Ilmu Pendidikan']); //1 
        fakultas::create(['nama' => 'Fakultas Matematika Ilmu Pengetahuan Alam Dan Kebumian']); // 2
        fakultas::create(['nama' => 'Fakultas Ilmu Keolahragaan Dan Kesejahteraan Masyarakat']); // 3
        fakultas::create(['nama' => 'Fakultas Teknik']); // 4
        fakultas::create(['nama' => 'Fakultas Ekonomi Dan Bisnis']); // 5
        fakultas::create(['nama' => 'Fakultas Ilmu Sosial Dan Hukum']); // 6
        fakultas::create(['nama' => 'Fakultas Bahasa Dan Seni']); // 7

    }
}
