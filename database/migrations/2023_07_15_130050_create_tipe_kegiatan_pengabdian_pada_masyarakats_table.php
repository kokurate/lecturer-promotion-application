<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipeKegiatanPengabdianPadaMasyarakatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipe_kegiatan_pengabdian_pada_masyarakats', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipe_kegiatan_pengabdian_pada_masyarakats');
    }
}
