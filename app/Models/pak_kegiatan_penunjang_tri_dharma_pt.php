<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pak_kegiatan_penunjang_tri_dharma_pt extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relationship
        public function kategory_pak(){
            return $this->belongsTo(kategori_pak::class);
        }

        public function user(){
            return $this->belongsTo(User::class);
        }

        public function tahun_ajaran(){
            return $this->belongsTo(tahun_ajaran::class,'tahun_ajaran_id');
        }

        public function scopeQueryTotalAK($query){
            return $query->with('tahun_ajaran')
                    ->whereHas('tahun_ajaran', function ($query) {
                        $query->where('now', true);
                    })
                    ->where('user_id', auth()->user()->id)
                    ->where('kategori_pak_id', 4);
        }

        // query for take the count 

    public function scopeQueryCount($query){
        
        return $query->with('tahun_ajaran')
            ->whereHas('tahun_ajaran', function ($query) {
                $query->where('now', true);
            })
            ->where('user_id', auth()->user()->id)
            ->where('kategori_pak_id', 4);
    }

    public function scopeQueryKode($query, $kode)
    {
        return $query->where('kode', $kode);
    }

}
