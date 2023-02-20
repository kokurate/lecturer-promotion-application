<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // there's 3 level admin, pegawai, dosen
        // Admin
        User::create([
            'name' => 'Sondy Kumajas',
            'level' => 'admin',
            'email' => 'admin@gmail',
            'password' => bcrypt('password'), //password
        ]);

        User::create([
            'name' => 'Rivay Samangka',
            'level' => 'pegawai',
            'email' => 'pegawai@gmail',
            'fakultas' => 'Fakultas Teknik',
            'password' => bcrypt('password'), //password
        ]);

        User::create([
            'name' => 'Kristofel Santa',
            'level' => 'pegawai',
            'email' => 'dosen@gmail',
            'fakultas' => 'Fakultas Teknik',
            'jurusan_prodi' => 'Program Studi Teknik Informatika',
            'password' => bcrypt('password'), //password
        ]);
    }
}
