<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ternak extends Model
{
    use SoftDeletes;
    
    protected $keyType = 'string';

    protected $primaryKey = 'necktag';

    protected $table = 'ternaks';
    
    protected $fillable = [
        'necktag', 'pemilik_id', 'ras_id', 'user_id', 'kematian_id', 'penjualan_id',
        'jenis_kelamin', 'tgl_lahir', 'bobot_lahir', 'pukul_lahir', 
        'lama_dikandungan', 'lama_laktasi', 'tgl_lepas_sapih',
        'necktag_ayah', 'necktag_ibu', 'cacat_fisik', 'ciri_lain', 'status_ada'
    ];

    public function riwayatPenyakit(){
        return $this->hasMany(RiwayatPenyakit::class);
    }

    public function ras(){
        return $this->belongsTo(Ras::class);
    }

    public function perkawinan(){
        return $this->hasMany(Perkawinan::class);
    }

    public function pemilik(){
        return $this->belongsTo(Pemilik::class);
    }

    public function kematian(){
        return $this->belongsTo(Kematian::class);
    }

    public function perkembangan(){
        return $this->hasMany(Perkembangan::class);
    }

    public function penjualan(){
        return $this->belongsTo(Penjualan::class);
    }

    public function peternak(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
