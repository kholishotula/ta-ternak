<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
    protected $table = 'pemiliks';
	
    protected $fillable = [
    	'ktp_pemilik', 'nama_pemilik'
    ];

    public function ternak(){
        return $this->hasMany(Ternak::class, 'necktag');
    }
}
