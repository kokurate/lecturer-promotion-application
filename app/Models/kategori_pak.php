<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kategori_pak extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function pak_kegiatan_pendidikan_dan_pengajaran(){
        return $this->hasMany(pak_kegiatan_pendidikan_dan_pengajaran::class);
    }

    public function pak_kegiatan_penelitian(){
        return $this->hasMany(pak_kegiatan_penelitian::class);
    }

    public function pak_kegiatan_penunjang_tri_dharma_pt(){
        return $this->hasMany(pak_kegiatan_penunjang_tri_dharma_pt::class);
    }

    public function pak_kegiatan_pengabdian_pada_masyarakat(){
        return $this->hasMany(pak_kegiatan_pengabdian_pada_masyarakat::class);
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(tahun_ajaran::class, 'tahun_ajaran_id');
    }

}
