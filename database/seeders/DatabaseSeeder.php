<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(PangkatSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(JurusanProdiSeeder::class);
        $this->call(FakultasSeeder::class);
        $this->call(TipeKegiatanPendidikanDanPengajaranSeeder::class);
        $this->call(TahunAjaranSeeder::class);
        $this->call(KategoriPakSeeder::class);
        $this->call(TipeKegiatanPenelitianSeeder::class);
        $this->call(TipeKegiatanPenunjangTriDharmaPtSeeder::class);
        $this->call(TipeKegiatanPengabdianPadaMasyarakatSeeder::class);

    }
    
}
