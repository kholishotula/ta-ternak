<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrupPeternak extends Model
{
    protected $table = 'grup_peternaks';
    
    protected $fillable = [
    	'nama_grup', 'alamat', 'provinsi', 'kab_kota', 'kecamatan', 'keterangan',
    ];

    public function peternak(){
        return $this->hasMany(User::class);
    }
}
