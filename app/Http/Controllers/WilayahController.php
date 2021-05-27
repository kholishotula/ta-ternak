<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function getKabupaten($id){
        $kab = DB::table('wilayahs')->where('kode', 'like', $id.'.%')->whereRaw('LENGTH(kode) = 5')->orderBy('nama')->get();
        return response()->json(['kab' => $kab]);
    }

    public function getKecamatan($id){
        $kec = DB::table('wilayahs')->where('kode', 'like', $id.'.%')->whereRaw('LENGTH(kode) = 8')->orderBy('nama')->get();
        return response()->json(['kec' => $kec]);
    }
}
