<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perkawinan extends Model
{
    protected $table = 'perkawinans';
	
    protected $fillable = [
        'necktag', 'necktag_psg', 'tgl_kawin',
    ];

    public function ternak(){
        return $this->belongsTo(Ternak::class);
    }
}
