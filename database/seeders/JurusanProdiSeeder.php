<?php

namespace Database\Seeders;

use App\Models\jurusan_prodi;
use Illuminate\Database\Seeder;

class JurusanProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /// 1 Fakultas Ilmu Pendidikan
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Guru Sekolah Dasar']); //1 
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Guru Anak Usia Dini']); //2 
        jurusan_prodi::create(['nama' => 'Program Studi Psikologi']); // 3 
        jurusan_prodi::create(['nama' => 'Program Studi Bimbingan Konseling']); // 4 
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Khusus']); // 5 
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Luar Sekolah']); // 6 

        // 2. Fakultas Matematika Dan Ilmu Pengetahuan Alam
        jurusan_prodi::create(['nama' => 'Program Studi Fisika']); // 7 
        jurusan_prodi::create(['nama' => 'Program Studi Biologi']); // 8 
        jurusan_prodi::create(['nama' => 'Program Studi Kimia']); // 9 
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Matematika']); // 10 
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Fisika']); // 11 
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Biologi']); // 12 
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Kimia']); // 13 
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan IPA']); // 14

        // 3 Fakultas Ilmu Keolahragaan
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Jasmani Kesehatan Dan Rekreasi']); // 15 
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Kepelatihan Olahraga']); // 16 
        jurusan_prodi::create(['nama' => 'Program Studi Ilmu Keolahragaan']); // 17
        jurusan_prodi::create(['nama' => 'Program Studi Ilmu Kesehatan Masyarakat']); // 18 
        
        // 4 Fakultas Teknik
        jurusan_prodi::create(['nama' => 'Program Studi Teknik Listrik']); // 19 
        jurusan_prodi::create(['nama' => 'Program Studi Teknik Sipil']); // 20  
        jurusan_prodi::create(['nama' => 'Program Studi Teknologi Kayu']); // 21
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Teknik Elektro']); // 22 
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Teknik Mesin']); // 22 
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Teknik Bangunan']); // 23 
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Kesejahteraan Keluarga']); // 24 
        jurusan_prodi::create(['nama' => 'Jurusan Pendidikan Teknologi Informasi Dan Komunikasi']); // 25 
        jurusan_prodi::create(['nama' => 'Program Studi Arsitektur']); // 26 
        jurusan_prodi::create(['nama' => 'Program Studi Teknik Informatika']); // 27 
        jurusan_prodi::create(['nama' => 'Program Studi Teknik Sipil']); // 28 
        jurusan_prodi::create(['nama' => 'Program Studi Teknik Mesin']); // 29 

        // 5. Fakultas Ekonomi
        jurusan_prodi::create(['nama' => 'Program Studi Manajemen Pemasaran']); // 30
        jurusan_prodi::create(['nama' => 'Program Studi Manajemen']); // 31
        jurusan_prodi::create(['nama' => 'Program Studi Akuntansi']); // 32
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Ekonomi']); // 33 
        jurusan_prodi::create(['nama' => 'Program Studi Ilmu Ekonomi']); // 34

        // 6 Fakultas Ilmu Sosial
        jurusan_prodi::create(['nama' => 'Program Studi Geografi']); // 35
        jurusan_prodi::create(['nama' => 'Program Studi Ilmu Administrasi Negara']); // 36
        jurusan_prodi::create(['nama' => 'Program Studi Ilmu Hukum']); // 37
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan IPS']); // 38
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Sejarah']); // 39
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Geografi']); // 40
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Sosiologi']); // 41
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Pancasila Dan Kewarganegaraan']); // 42
        
        // 7 Fakultas Bahasa dan Seni
        jurusan_prodi::create(['nama' => 'Program Studi Bahasa Dan Sastra Inggris']); // 43
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Bahasa dan Sastra Indonesia']); // 44
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Bahasa Inggris']); // 45
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Bahasa Jepang']); // 46
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Bahasa Perancis']); // 47
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Bahasa Jerman']); // 48
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Seni Drama Tari Dan Musik']); // 49
        jurusan_prodi::create(['nama' => 'Program Studi Pendidikan Seni Rupa']); // 50
    }
}
