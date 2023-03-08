<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pangkat extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

   public function user(){
        return $this->hasMany(User::class);
    }

    public function history()
    {
        return $this->hasMany(history::class);
    }

    // public function user(){
    //     return $this->belongsTo(User::class);
    // }
}
