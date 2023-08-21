<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSksResetToPakKegiatanPendidikanDanPengajaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pak_kegiatan_pendidikan_dan_pengajarans', function (Blueprint $table) {
            $table->string('sks_reset')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pak_kegiatan_pendidikan_dan_pengajarans', function (Blueprint $table) {
            $table->dropColumn('sks_reset');
        });
    }
}
