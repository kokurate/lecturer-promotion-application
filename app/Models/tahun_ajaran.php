<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tahun_ajaran extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function pak_kegiatan_pendidikan_dan_pengajaran(){
        return $this->hasMany(pak_kegiatan_pendidikan_dan_pengajaran::class, 'tahun_ajaran_id');
    }

    public function pak_kegiatan_penelitian(){
        return $this->hasMany(pak_kegiatan_penelitian::class,'tahun_ajaran_id');
    }

    public function pak_kegiatan_pengabdian_pada_masyarakat(){
        return $this->hasMany(pak_kegiatan_pengabdian_pada_masyarakat::class,'tahun_ajaran_id');
    }

    public function pak_kegiatan_penunjang_tri_dharma_pt(){
        return $this->hasMany(pak_kegiatan_penunjang_tri_dharma_pt::class,'tahun_ajaran_id');
    }
    
}
