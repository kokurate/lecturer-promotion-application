<?php

namespace Database\Seeders;

use App\Models\tipe_kegiatan_pengabdian_pada_masyarakat;
use Illuminate\Database\Seeder;

class TipeKegiatanPengabdianPadaMasyarakatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tipe_kegiatan_pengabdian_pada_masyarakat::create(['nama_kegiatan' => 'Menduduki Jabatan Pimpinan Pada Lembaga Pemerintahan/ Pejabat Negara Yang Harus Dibebaskan Dari Jabatan Organiknya Tiap Semester']); // 1 
        tipe_kegiatan_pengabdian_pada_masyarakat::create(['nama_kegiatan' => 'Melaksanakan Pengembangan Hasil Pendidikan, Dan Penelitian Yang Dapat Dimanfaatkan Oleh Masyarakat/ Industry Setiap Program']); // 2
        tipe_kegiatan_pengabdian_pada_masyarakat::create(['nama_kegiatan' => 'Memberikan Latihan/ Penyuluhan/ Penataran/ Ceramah Pada Masyarakat, Terjadwal/ Terprogram']); // 3
        tipe_kegiatan_pengabdian_pada_masyarakat::create(['nama_kegiatan' => 'Memberi Pelayanan Kepada Masyarakat Atau Kegiatan Lain Yang Menunjang Pelaksanaan Tugas Pemerintahan Dan Pembangunan']); // 4
        tipe_kegiatan_pengabdian_pada_masyarakat::create(['nama_kegiatan' => 'Membuat/ Menulis Karya Pengabdian Pada Masyarakat Yang Tidak Dipublikasikan, Tiap Karya']); // 5

    }
}
