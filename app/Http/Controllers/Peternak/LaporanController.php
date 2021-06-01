<?php

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Ternak;
use App\Perkawinan;
use App\RiwayatPenyakit;
use App\Kematian;
use App\Penjualan;
use App\User;

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

        return view('laporan.laporan');
    }

    public function lahir(Request $request)
    {
        if($request->ajax()){
            $lahir = Ternak::whereBetween('tgl_lahir', [$request->datefrom, $request->dateto])
                            ->where('user_id', Auth::id())
                            ->get();

            return DataTables::of($lahir)
                  ->addIndexColumn()
                  ->make(true);
        }
    }

    public function mati(Request $request)
    {
        if($request->ajax()){
            $mati = Kematian::select('ternaks.necktag', 'ternaks.kematian_id', 'kematians.tgl_kematian', 'kematians.waktu_kematian', 'kematians.penyebab', 'kematians.kondisi', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                    ->join('public.ternaks', 'kematians.id', '=', 'ternaks.kematian_id')
                    ->where('ternaks.user_id', Auth::id())
                    ->whereBetween('kematians.tgl_kematian', [$request->datefrom, $request->dateto])
                    ->get();

            return DataTables::of($mati)
                  ->addIndexColumn()
                  ->make(true);
        }
    }

    public function jual(Request $request)
    {
        if($request->ajax()){
            $jual = Penjualan::select('ternaks.necktag', 'ternaks.penjualan_id', 'penjualans.tgl_terjual', 'penjualans.ket_pembeli', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                    ->join('public.ternaks', 'penjualans.id', '=', 'ternaks.penjualan_id')
                    ->where('ternaks.user_id', Auth::id())
                    ->whereBetween('penjualans.tgl_terjual', [$request->datefrom, $request->dateto])
                    ->get();

            return DataTables::of($jual)
                  ->addIndexColumn()
                  ->make(true);
        }
    }

    public function kawin(Request $request)
    {
        if($request->ajax()){
            $necktag_ternaks = Ternak::where('user_id', Auth::id())->pluck('necktag')->toArray();

            $kawin = Perkawinan::whereBetween('tgl_kawin', [$request->datefrom, $request->dateto])
                                ->whereIn('necktag', $necktag_ternaks)
                                ->get();

            return DataTables::of($kawin)
                  ->make(true);
        }
    }

    public function sakit(Request $request)
    {
        if($request->ajax()){
            $necktag_ternaks = Ternak::where('user_id', Auth::id())->pluck('necktag')->toArray();
            
            $sakit = RiwayatPenyakit::whereBetween('tgl_sakit', [$request->datefrom, $request->dateto])
                                    ->whereIn('necktag', $necktag_ternaks)
                                    ->get();

            return DataTables::of($sakit)
                  ->make(true);
        }
    }

    public function ada(Request $request)
    {
        if($request->ajax()){
            $exists = Ternak::where('status_ada', true)
                        ->where('user_id', Auth::id())
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

        $export = new LaporanExport($sp[1], $sp[3], null, Auth::id());

        return Excel::download($export, 'SITERNAK_Laporan_'.$sp[1].'_'.$sp[3].'_peternak_id_'.Auth::id().'.xlsx');
    }
}
