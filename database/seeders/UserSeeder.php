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
        // 1
        User::create([
            'name' => 'Rivay Samangka',
            'level' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'), //password
        ]);

        /* ========================= Pegawai ========================= */
        // 2
        User::create([
            'name' => 'Pegawai Fakultas Ilmu Pendidikan',
            'level' => 'pegawai',
            'email' => 'pegawaifip@gmail.com', 
            'fakultas' => 'Fakultas Ilmu Pendidikan',
            'password' => bcrypt('password'), //password
        ]);

        // 3
        User::create([
            'name' => 'PegawaiFakultas Matematika Ilmu Pengetahuan Alam Dan Kebumian',
            'level' => 'pegawai',
            'email' => 'pegawaifmipa@gmail.com', 
            'fakultas' => 'Fakultas Matematika Ilmu Pengetahuan Alam Dan Kebumian',
            'password' => bcrypt('password'), //password
        ]);

        // 4
        User::create([
            'name' => 'Pegawai Fakultas Ilmu Keolahragaan Dan Kesejahteraan Masyarakat',
            'level' => 'pegawai',
            'email' => 'pegawaifik@gmail.com',
            'fakultas' => 'Fakultas Ilmu Keolahragaan Dan Kesejahteraan Masyarakat',
            'password' => bcrypt('password'), //password
        ]);
        // 5
        User::create([
            'name' => 'Pegawai Fakultas Teknik',
            'level' => 'pegawai',
            'email' => 'pegawaifatek@gmail.com',
            'fakultas' => 'Fakultas Teknik',
            'password' => bcrypt('password'), //password
        ]);
        // 6
        User::create([
            'name' => 'Pegawai Fakultas Ekonomi Dan Bisnis',
            'level' => 'pegawai',
            'email' => 'pegawaifekon@gmail.com',
            'fakultas' => 'Fakultas Ekonomi Dan Bisnis',
            'password' => bcrypt('password'), //password
        ]);
        // 7
        User::create([
            'name' => 'Pegawai Fakultas Ilmu Sosial Dan Hukum',
            'level' => 'pegawai',
            'email' => 'pegawaifis@gmail.com',
            'fakultas' => 'Fakultas Ilmu Sosial Dan Hukum',
            'password' => bcrypt('password'), //password
        ]);
        // 8
        User::create([
            'name' => 'Pegawai Fakultas Bahasa Dan Seni',
            'level' => 'pegawai',
            'email' => 'pegawaifbs@gmail.com',
            'fakultas' => 'Fakultas Bahasa Dan Seni',
            'password' => bcrypt('password'), //password
        ]);

        /* ========================= Dosen ========================= */
        // 9
        User::create([
            'name' => 'dosen Fakultas Ilmu Pendidikan',
            'level' => 'dosen',
            'email' => 'dosenfip@gmail.com', 
            'fakultas' => 'Fakultas Ilmu Pendidikan',
            'password' => bcrypt('password'), //password
            'pangkat_id' => 1,
            'jurusan_prodi' => 'Program Studi Pendidikan Luar Sekolah',
        ]);

        // 10
        User::create([
            'name' => 'dosen Fakultas Matematika Ilmu Pengetahuan Alam Dan Kebumian',
            'level' => 'dosen',
            'email' => 'dosenfmipa@gmail.com', 
            'fakultas' => 'Fakultas Matematika Ilmu Pengetahuan Alam Dan Kebumian',
            'password' => bcrypt('password'), //password
            'pangkat_id' => 1,
            'jurusan_prodi' => 'Program Studi Fisika',
        ]);

        // 11
        User::create([
            'name' => 'dosen Fakultas Ilmu Keolahragaan Dan Kesejahteraan Masyarakat',
            'level' => 'dosen',
            'email' => 'dosenfik@gmail.com',
            'fakultas' => 'Fakultas Ilmu Keolahragaan Dan Kesejahteraan Masyarakat',
            'password' => bcrypt('password'), //password
            'pangkat_id' => 1,
            'jurusan_prodi' => 'Program Studi Ilmu Keolahragaan',
        ]);
        // 12
        User::create([
            'name' => 'dosen Fakultas Teknik',
            'level' => 'dosen',
            'email' => 'dosenfatek@gmail.com',
            'fakultas' => 'Fakultas Teknik',
            'password' => bcrypt('password'), //password
            'pangkat_id' => 1,
            'jurusan_prodi' => 'Program Studi Teknik Sipil',
        ]);
        // 13
        User::create([
            'name' => 'dosen Fakultas Ekonomi Dan Bisnis',
            'level' => 'dosen',
            'email' => 'dosenfekon@gmail.com',
            'fakultas' => 'Fakultas Ekonomi Dan Bisnis',
            'password' => bcrypt('password'), //password
            'pangkat_id' => 1,
            'jurusan_prodi' => 'Program Studi Ilmu Ekonomi',
        ]);
        // 14
        User::create([
            'name' => 'dosen Fakultas Ilmu Sosial Dan Hukum',
            'level' => 'dosen',
            'email' => 'dosenfis@gmail.com',
            'fakultas' => 'Fakultas Ilmu Sosial Dan Hukum',
            'password' => bcrypt('password'), //password
            'pangkat_id' => 1,
            'jurusan_prodi' => 'Program Studi Geografi',
        ]);
        // 15
        User::create([
            'name' => 'dosen Fakultas Bahasa Dan Seni',
            'level' => 'dosen',
            'email' => 'dosenfbs@gmail.com',
            'fakultas' => 'Fakultas Bahasa Dan Seni',
            'password' => bcrypt('password'), //password
            'pangkat_id' => 1,
            'jurusan_prodi' => 'Program Studi Pendidikan Seni Rupa',
        ]);

    }
}
