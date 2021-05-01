<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatPenyakit extends Model
{
    protected $table = 'riwayat_penyakits';
	
    protected $fillable = [
    	'necktag', 'nama_penyakit', 'lama_sakit', 'obat', 'keterangan', 'tgl_sakit'
    ];

    public function ternak(){
        return $this->belongsTo(Ternak::class);
    }
}
