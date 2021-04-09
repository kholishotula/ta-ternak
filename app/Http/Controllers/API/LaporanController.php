<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Ternak;
use App\Perkawinan;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $lahir = Ternak::whereBetween('tgl_lahir', [$request->datefrom, $request->dateto])->get();
        $mati = Ternak::select('ternaks.necktag', 'ternaks.kematian_id', 'kematians.tgl_kematian', 'kematians.waktu_kematian', 'kematians.penyebab', 'kematians.kondisi', 'ternaks.pemilik_id', 'ternaks.peternakan_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.bobot_lahir', 'ternaks.pukul_lahir', 'ternaks.lama_dikandungan', 'ternaks.lama_laktasi', 'ternaks.tgl_lepas_sapih', 'ternaks.blood', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.bobot_tubuh', 'ternaks.panjang_tubuh', 'ternaks.tinggi_tubuh', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                        ->join('public.kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                        ->whereBetween('kematians.tgl_kematian', [$request->datefrom, $request->dateto])
                        ->get();
        $kawin = Perkawinan::whereBetween('tgl', [$request->datefrom, $request->dateto])->get();
        $sakit = DB::table('riwayat_penyakits')->join('public.penyakits', 'penyakits.id', '=', 'riwayat_penyakits.penyakit_id')
                    ->whereBetween('riwayat_penyakits.tgl_sakit', [$request->datefrom, $request->dateto])->get();
        $exists_union = Ternak::join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                                ->where('tgl_kematian', '>', $request->dateto)
                                ->where('tgl_lahir', '<', $request->dateto)
                                ->selectRaw('ternaks.*');
        $exists = Ternak::where('status_ada', true)
                        ->where('tgl_lahir', '<', $request->dateto)
                        ->union($exists_union)
                        ->get();

        return response()->json([
            'status' => 'success',
            'lahir' => $lahir,
            'mati' => $mati,
            'kawin' => $kawin,
            'sakit' => $sakit,
            'ada' => $exists,
        ], 200);

    }
}
