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
        // Admin
        User::create([
            'name' => 'Rivay',
            'level' => 'pegawai',
            'email' => 'rivay@unima.ac.id',
            'password' => bcrypt('password'), //password
        ]);
    }
}
