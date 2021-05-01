<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perkembangan extends Model
{
    protected $table = 'perkembangans';
    
    protected $fillable = [
    	'necktag', 'tgl_perkembangan', 'berat_badan', 'panjang_badan', 'lingkar_dada',
        'tinggi_pundak', 'lingkar_skrotum', 'keterangan', 'foto'
    ];

    public function ternak(){
        return $this->belongsTo(Ternak::class);
    }
}
