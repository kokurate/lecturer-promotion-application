<?php

namespace Database\Seeders;

use App\Models\pangkat;
use Illuminate\Database\Seeder;

class PangkatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1
        Pangkat::create([
            'jabatan_fungsional' => 'Asisten Ahli', 
            'pangkat' => 'Penata Muda' ,
            'golongan' => 'III/a' ,
        ]);

        // 2
        Pangkat::create([
            'jabatan_fungsional' => 'Asisten Ahli', 
            'pangkat' => 'Penata Muda Tk.I' ,
            'golongan' => 'III/b' ,
        ]);

        // 3
        Pangkat::create([
            'jabatan_fungsional' => 'Lektor', 
            'pangkat' => 'Penata' ,
            'golongan' => 'III/c' ,
        ]);

        // 4
        Pangkat::create([
            'jabatan_fungsional' => 'Lektor', 
            'pangkat' => 'Penata Tk.I' ,
            'golongan' => 'III/d' ,
        ]);

        // 5
        Pangkat::create([
            'jabatan_fungsional' => 'Lektor Kepala', 
            'pangkat' => 'Pembina' ,
            'golongan' => 'IV/a' ,
        ]);

        // 6
        Pangkat::create([
            'jabatan_fungsional' => 'Lektor Kepala', 
            'pangkat' => 'Pembina Tk.I' ,
            'golongan' => 'IV/b' ,
        ]);

        // 7
        Pangkat::create([
            'jabatan_fungsional' => 'Lektor Kepala', 
            'pangkat' => 'Pembina Utama Muda' ,
            'golongan' => 'IV/c' ,
        ]);

        // 8
        Pangkat::create([
            'jabatan_fungsional' => 'Guru Besar', 
            'pangkat' => 'Pembina Utama Madya' ,
            'golongan' => 'IV/d' ,
        ]);

        // 9
        Pangkat::create([
            'jabatan_fungsional' => 'Guru Besar', 
            'pangkat' => 'Pembina Utama' ,
            'golongan' => 'IV/e' ,
        ]);

    }
}
