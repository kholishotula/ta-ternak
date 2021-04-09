<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
	protected $fillable = [
    	'ktp', 'nama_pemilik',
    ];

    public function ternak(){
        return $this->hasMany(Ternak::class);
    }
}
