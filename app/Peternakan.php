<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Peternakan extends Model
{
    protected $fillable = [
    	'nama_peternakan', 'keterangan',
    ];

    public function ternak(){
        return $this->hasMany(Ternak::class);
    }

    public function peternak(){
        return $this->hasMany(Peternak::class);
    }
}
