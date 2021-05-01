<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Ternak;
use App\Perkawinan;
use App\RiwayatPenyakit;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            return response()->json([
                'start' => $request->datefrom,
                'end' => $request->dateto
            ]);
        }

        return view('laporan.laporan')->with('start', date("Y-m-d", strtotime('-29 days')))
                    ->with('end', date("Y-m-d"));
    }

    public function lahir(Request $request)
    {
        if($request->ajax()){
            $lahir = Ternak::whereBetween('tgl_lahir', [$request->datefrom, $request->dateto])->get();

            return DataTables::of($lahir)
                  ->addIndexColumn()
                  ->make(true);
        }
    }

    public function mati(Request $request)
    {
        if($request->ajax()){
            $mati = Ternak::select('ternaks.necktag', 'ternaks.kematian_id', 'kematians.tgl_kematian', 'kematians.waktu_kematian', 'kematians.penyebab', 'kematians.kondisi', 'ternaks.pemilik_id', 'ternaks.peternakan_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.bobot_lahir', 'ternaks.pukul_lahir', 'ternaks.lama_dikandungan', 'ternaks.lama_laktasi', 'ternaks.tgl_lepas_sapih', 'ternaks.blood', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.bobot_tubuh', 'ternaks.panjang_tubuh', 'ternaks.tinggi_tubuh', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                        ->join('public.kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                        ->whereBetween('kematians.tgl_kematian', [$request->datefrom, $request->dateto])
                        ->get();

            return DataTables::of($mati)
                  ->addIndexColumn()
                  ->make(true);
        }
    }

    public function kawin(Request $request)
    {
        if($request->ajax()){
            $kawin = Perkawinan::whereBetween('tgl_kawin', [$request->datefrom, $request->dateto])->get();

            return DataTables::of($kawin)
                  ->make(true);
        }
    }

    public function sakit(Request $request)
    {
        if($request->ajax()){
            $sakit = RiwayatPenyakit::whereBetween('tgl_sakit', [$request->datefrom, $request->dateto])->get();

            return DataTables::of($sakit)
                  ->make(true);
        }
    }

    public function ada(Request $request)
    {
        if($request->ajax()){
            $exists_union = Ternak::join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                                ->where('tgl_kematian', '>', $request->dateto)
                                ->where('tgl_lahir', '<', $request->dateto)
                                ->selectRaw('ternaks.*');

            $exists = Ternak::where('status_ada', true)
                        ->where('tgl_lahir', '<', $request->dateto)
                        ->union($exists_union)
                        ->get();

            return DataTables::of($exists)
                  ->addIndexColumn()
                  ->make(true);
        } 
    }

    public function export($date) 
    {
        $sp = preg_split("/[=&]/", $date); 
        //0: datefrom, 1:tgl, 2:dateto, 3:tgl

        $export = new LaporanExport($sp[1], $sp[3]);

        return Excel::download($export, 'SITERNAK_Laporan_'.$sp[1].'_'.$sp[3].'.xlsx');
    }

}
