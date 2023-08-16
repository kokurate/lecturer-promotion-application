<?php

namespace Database\Seeders;

use App\Models\status_kenaikan_pangkat;
use Illuminate\Database\Seeder;

class StatusKenaikanPangkatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        status_kenaikan_pangkat::create(['user_id' => 9]);
        status_kenaikan_pangkat::create(['user_id' => 10]);
        status_kenaikan_pangkat::create(['user_id' => 11]);
        status_kenaikan_pangkat::create(['user_id' => 12]);
        status_kenaikan_pangkat::create(['user_id' => 13]);
        status_kenaikan_pangkat::create(['user_id' => 14]);
        status_kenaikan_pangkat::create(['user_id' => 15]);
    }
}
