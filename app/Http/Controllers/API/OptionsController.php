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
use Illuminate\Support\Facades\Auth;

class OptionsController extends Controller
{
    public function index()
    {
        if(Auth::user()->role == 'admin'){
            $kematian = Kematian::orderBy("id")->select("id", "waktu_kematian", "tgl_kematian", "penyebab", "kondisi")->get();
            $pemilik = Pemilik::orderBy("id")->select("id", "nama_pemilik")->get();
            $grup = GrupPeternak::orderBy("id")->select("id", "nama_grup")->get();
            $ras = Ras::orderBy("id")->select("id", "jenis_ras")->get();
            $ternak = Ternak::orderBy("created_at")->select("necktag", "jenis_kelamin")->get(); 
            $penjualan = Penjualan::orderBy("id")->select('id', 'tgl_terjual', 'ket_pembeli');
        }
        else{
            $necktag_ternaks = Ternak::where('user_id', Auth::id())
                                ->pluck('necktag')->toArray();
            $kematian = Kematian::whereIn('necktag', $necktag_ternaks)
                            ->select("id", "waktu_kematian", "tgl_kematian", "penyebab", "kondisi")
                            ->orderBy("id")->get();
            $pemilik = Pemilik::select("id", "nama_pemilik")
                        ->orderBy("id")->get();
            $grup = GrupPeternak::select("id", "nama_grup")
                        ->orderBy("id")->get();
            $ras = Ras::select("id", "jenis_ras")
                        ->orderBy("id")->get();
            $ternak = Ternak::whereIn('necktag', $necktag_ternaks)
                            ->select("necktag", "jenis_kelamin")
                            ->orderBy("created_at")->get(); 
            $penjualan = Penjualan::whereIn('necktag', $necktag_ternaks)
                            ->select('id', 'tgl_terjual', 'ket_pembeli')
                            ->orderBy("id")->get();
        }

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
