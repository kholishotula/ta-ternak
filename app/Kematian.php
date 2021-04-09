<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kematian extends Model
{
	protected $fillable = [
    	'tgl_kematian', 'waktu_kematian', 'penyebab', 'kondisi', 
    ];

    public function ternak(){
        return $this->hasMany(Ternak::class);
    }
}
