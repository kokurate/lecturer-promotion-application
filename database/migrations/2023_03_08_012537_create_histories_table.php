<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->integer('user_id_old')->nullable();
            $table->unsignedBigInteger('pangkat_sekarang'); //pangkat sekarang
            $table->unsignedBigInteger('pangkat_berikut')->nullable();
            $table->string('nama')->nullable();
            $table->string('email')->nullable();
            $table->string('nip')->nullable();
            $table->string('nidn')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('jurusan_prodi')->nullable();
            $table->string('status')->nullable();
            $table->string('tanggapan')->nullable();
            $table->timestamps();

            $table->foreign('pangkat_sekarang')->references('id')->on('pangkats')->onDelete('cascade');
            $table->foreign('pangkat_berikut')->references('id')->on('pangkats')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histories');
    }
}
