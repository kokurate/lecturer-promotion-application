<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pak_kegiatan_pendidikan_dan_pengajaran extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function kategory_pak(){
        return $this->belongsTo(kategori_pak::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tahun_ajaran(){
        return $this->belongsTo(tahun_ajaran::class,'tahun_ajaran_id');
    }
}
