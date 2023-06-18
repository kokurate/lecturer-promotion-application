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
}
