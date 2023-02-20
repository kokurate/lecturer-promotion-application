<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBerkasKenaikanPangkatRegulersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berkas_kenaikan_pangkat_regulers', function (Blueprint $table) {
            $table->id();
            $table->string('kartu_pegawai_nip_baru_bkn')->nullable();
            $table->string('sk_cpns')->nullable();
            $table->string('sk_pangkat_terakhir')->nullable();
            $table->string('sk_jabfung_terakhir_dan_pak')->nullable();
            $table->string('ppk_dan_skp')->nullable();
            $table->string('ijazah_terakhir')->nullable();
            $table->string('sk_tugas_belajar_atau_surat_izin_studi')->nullable();
            $table->string('keterangan_membina_mata_kuliah_dari_jurusan')->nullable();
            $table->string('surat_pernyataan_setiap_bidang_tridharma')->nullable();
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
        Schema::dropIfExists('berkas_kenaikan_pangkat_regulers');
    }
}
