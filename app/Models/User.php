<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function berkas_kenaikan_pangkat_reguler(){
        return $this->hasOne(berkas_kenaikan_pangkat_reguler::class);
    }

    public function pangkat(){
        return $this->belongsTo(pangkat::class);
    }

    public function my_storage(){
        return $this->hasMany(my_storage::class);
    }

    public function history(){
        return $this->hasMany(history::class);
    }

    public function status_kenaikan_pangkat(){
        return $this->hasOne(status_kenaikan_pangkat::class);
    }

    public function pak_kegiatan_pendidikan_dan_pengajaran(){
        return $this->hasMany(pak_kegiatan_pendidikan_dan_pengajaran::class);
    }

    public function pak_kegiatan_penelitian(){
        return $this->hasMany(pak_kegiatan_penelitian::class);
    }

    public function pak_kegiatan_pengabdian_pada_masyarakat(){
        return $this->hasMany(pak_kegiatan_pengabdian_pada_masyarakat::class);
    }

    public function pak_kegiatan_penunjang_tri_dharma_pt(){
        return $this->hasMany(pak_kegiatan_penunjang_tri_dharma_pt::class);
    }

}
