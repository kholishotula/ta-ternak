<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ras extends Model
{
    protected $fillable = [
    	'jenis_ras', 'ket_ras',
    ];

    public function ternak(){
        return $this->hasMany(Ternak::class);
    }

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
