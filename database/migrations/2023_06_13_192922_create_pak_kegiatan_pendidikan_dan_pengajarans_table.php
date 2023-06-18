<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePakKegiatanPendidikanDanPengajaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pak_kegiatan_pendidikan_dan_pengajarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_pak_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->string('kegiatan')->nullable();
            $table->string('tipe_kegiatan')->nullable();
            $table->string('jenis_pendidikan')->nullable();
            $table->string('komponen_kegiatan')->nullable();
            $table->string('kode')->nullable();
            $table->string('sks')->nullable()->default('-');
            // $table->string('sks')->nullable();
            $table->decimal('angka_kredit')->nullable();
            $table->foreignId('tahun_ajaran_id')->nullable();
            $table->string('bukti')->nullable();
            $table->string('slug')->nullable();
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
        Schema::dropIfExists('pak_kegiatan_pendidikan_dan_pengajarans');
    }
}
