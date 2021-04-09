<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penyakit extends Model
{
	protected $fillable = [
    	'nama_penyakit', 'ket_penyakit',
    ];

    public function ternak(){
        return $this->belongsToMany(Ternak::class, 'riwayat_penyakits', 'penyakit_id', 'necktag')
			        ->withPivot('tgl_sakit', 'obat', 'lama_sakit', 'keterangan')
			    	->withTimestamps();
    }
}
