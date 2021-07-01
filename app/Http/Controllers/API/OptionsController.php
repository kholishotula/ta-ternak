<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Kematian;
use App\Pemilik;
use App\GrupPeternak;
use App\Ras;
use App\Ternak;
use App\Penjualan;

class OptionsController extends Controller
{
    public function index()
    {
    	$kematian = Kematian::orderBy("id")->select("id", "waktu_kematian", "tgl_kematian", "penyebab", "kondisi")->get();
    	$pemilik = Pemilik::orderBy("id")->select("id", "nama_pemilik")->get();
    	$grup = GrupPeternak::orderBy("id")->select("id", "nama_grup")->get();
    	$ras = Ras::orderBy("id")->select("id", "jenis_ras")->get();
    	$ternak = Ternak::orderBy("created_at")->select("necktag", "jenis_kelamin")->get(); 
        $penjualan = Penjualan::orderBy("id")->select('id', 'tgl_terjual', 'ket_pembeli');

        return response()->json([
            'kematian' => $kematian,
            'pemilik' => $pemilik,
            'grup' => $grup,
            'ras' => $ras,
            'ternak' => $ternak,
            'penjualan' => $penjualan
        ], 200);
    }
}
