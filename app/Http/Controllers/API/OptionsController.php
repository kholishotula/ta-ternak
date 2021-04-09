<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Kematian;
use App\Pemilik;
use App\Penyakit;
use App\Peternakan;
use App\Ras;
use App\Ternak;

class OptionsController extends Controller
{
    public function index()
    {
    	$kematian = Kematian::orderBy("id")->select("id", "waktu_kematian", "tgl_kematian", "penyebab", "kondisi")->get();
    	$pemilik = Pemilik::orderBy("id")->select("id", "nama_pemilik")->get();
    	$penyakit = Penyakit::orderBy("id")->select("id", "nama_penyakit")->get();
    	$peternakan = Peternakan::orderBy("id")->select("id", "nama_peternakan")->get();
    	$ras = Ras::orderBy("id")->select("id", "jenis_ras")->get();
    	$ternak = Ternak::orderBy("created_at")->select("necktag", "jenis_kelamin")->get(); 

        return response()->json([
            'kematian' => $kematian,
            'pemilik' => $pemilik,
            'penyakit' => $penyakit,
            'peternakan' => $peternakan,
            'ras' => $ras,
            'ternak' => $ternak,
        ], 200);
    }
}
