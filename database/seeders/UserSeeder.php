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
            'name' => 'Rivay Samangka',
            'level' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'), //password
        ]);

        User::create([
            'name' => 'Pegawai Fakultas Teknik',
            'level' => 'pegawai',
            'email' => 'fatek@gmail.com',
            'fakultas' => 'Fakultas Teknik',
            'password' => bcrypt('password'), //password
        ]);

        User::create([
            'name' => 'Pegawai Fakultas Ekonomi Dan Bisnis',
            'level' => 'pegawai',
            'email' => 'fekon@gmail.com',
            'fakultas' => 'Fakultas Ekonomi Dan Bisnis',
            'password' => bcrypt('password'), //password
        ]);

        User::create([
            'name' => 'Glory',
            'pangkat_id' => 2,
            'level' => 'dosen',
            'email' => 'dosen@gmail.com',
            'fakultas' => 'Fakultas Teknik',
            'jurusan_prodi' => 'Program Studi Teknik Informatika',
            'password' => bcrypt('password'), //password
            'my_token' => 'asdasdasd'
        ]);

        User::create([
            'name' => 'Celine',
            'pangkat_id' => 4,
            'level' => 'dosen',
            'email' => 'dosenfekon@gmail.com',
            'fakultas' => 'Fakultas Ekonomi Dan Bisnis',
            'jurusan_prodi' => 'Program Studi Pendidikan Ekonomi',
            'password' => bcrypt('password'), //password
            'my_token' => 'asdasdasd'
        ]);
    }
}
