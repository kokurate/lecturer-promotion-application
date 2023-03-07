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
        fakultas::create(['nama' => 'Fakultas Matematika Dan Ilmu Pengetahuan Alam']); // 2
        fakultas::create(['nama' => 'Fakultas Ilmu Keolahragaan']); // 3
        fakultas::create(['nama' => 'Fakultas Teknik']); // 4
        fakultas::create(['nama' => 'Fakultas Ekonomi']); // 5
        fakultas::create(['nama' => 'Fakultas Ilmu Sosial']); // 6
        fakultas::create(['nama' => 'Fakultas Bahasa Dan Seni']); // 7

    }
}
