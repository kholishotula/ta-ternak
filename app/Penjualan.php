<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualans';
    
    protected $fillable = [
        'tgl_terjual', 'ket_pembeli'
    ];

    public function ternak(){
        return $this->hasOne(Ternak::class);
    }
}
