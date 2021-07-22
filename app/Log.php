<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';
	
    protected $fillable = [
    	'user_id', 'aktivitas', 'tabel', 'pk_tabel', 'waktu'
    ];

    public $timestamps = false;
}
