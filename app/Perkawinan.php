<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perkawinan extends Model
{
	protected $fillable = [
        'necktag', 'necktag_psg', 'tgl',
    ];

    public function ternak(){
        return $this->belongsTo(Ternak::class);
    }
}
