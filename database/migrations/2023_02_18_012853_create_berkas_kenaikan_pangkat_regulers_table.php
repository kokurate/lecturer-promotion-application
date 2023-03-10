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
            $table->foreignId('user_id')->nullable();
            $table->string('kartu_pegawai_nip_baru_bkn')->nullable();
            $table->string('sk_cpns')->nullable();
            $table->string('sk_pangkat_terakhir')->nullable();
            $table->string('sk_jabfung_terakhir_dan_pak')->nullable();
            $table->string('ppk_dan_skp')->nullable();
            $table->string('ijazah_terakhir')->nullable();
            $table->string('sk_tugas_belajar_atau_surat_izin_studi')->nullable();
            $table->string('keterangan_membina_mata_kuliah_dari_jurusan')->nullable();
            $table->string('surat_pernyataan_setiap_bidang_tridharma')->nullable();
            $table->string('merge')->nullable();
            $table->boolean('check_kartu_pegawai_nip_baru_bkn')->default(0);
            $table->boolean('check_sk_cpns')->default(0);
            $table->boolean('check_sk_pangkat_terakhir')->default(0);
            $table->boolean('check_sk_jabfung_terakhir_dan_pak')->default(0);
            $table->boolean('check_ppk_dan_skp')->default(0);
            $table->boolean('check_ijazah_terakhir')->default(0);
            $table->boolean('check_sk_tugas_belajar_atau_surat_izin_studi')->default(0);
            $table->boolean('check_keterangan_membina_mata_kuliah_dari_jurusan')->default(0);
            $table->boolean('check_surat_pernyataan_setiap_bidang_tridharma')->default(0);
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
