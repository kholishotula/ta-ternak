<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kematian extends Model
{
    protected $table = 'kematians';
	
    protected $fillable = [
    	'tgl_kematian', 'waktu_kematian', 'penyebab', 'kondisi', 
    ];

    public function ternak(){
        return $this->hasOne(Ternak::class);
    }
}
