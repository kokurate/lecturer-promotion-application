<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class history extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function pangkatSekarang()
    {
        return $this->belongsTo(pangkat::class, 'pangkat_sekarang');
    }

    public function pangkatBerikut()
    {
        return $this->belongsTo(pangkat::class, 'pangkat_berikut');
    }
    
    public function User(){
        return $this->belongsTo(User::class);
    }
}
