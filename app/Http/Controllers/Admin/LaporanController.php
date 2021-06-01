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
use App\Kematian;
use App\Penjualan;
use App\GrupPeternak;
use App\User;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $grup = GrupPeternak::all();
        
        if($request->ajax()){
            return response()->json([
                'start' => $request->datefrom,
                'end' => $request->dateto,
                'grup_id' => $request->grup_id
            ]);
        }

        return view('laporan.laporan')->with('grups', $grup);
    }

    public function lahir(Request $request)
    {
        if($request->ajax()){
            if($request->grup_id != null){
                $user_ids = User::where('grup_id', $request->grup_id)->pluck('id')->toArray();

                if($user_ids != null){
                    $lahir = Ternak::whereBetween('tgl_lahir', [$request->datefrom, $request->dateto])->whereIn('user_id', $user_ids)->get();
                }
                else{
                    $lahir = [];
                }
            }
            else{
                $lahir = Ternak::whereBetween('tgl_lahir', [$request->datefrom, $request->dateto])->get();
            }

            return DataTables::of($lahir)
                  ->addIndexColumn()
                  ->make(true);
        }
    }

    public function mati(Request $request)
    {
        if($request->ajax()){
            if($request->grup_id != null){
                $user_ids = User::where('grup_id', $request->grup_id)->pluck('id')->toArray();

                if($user_ids != null){
                    $mati = Kematian::select('ternaks.necktag', 'ternaks.kematian_id', 'kematians.tgl_kematian', 'kematians.waktu_kematian', 'kematians.penyebab', 'kematians.kondisi', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                        ->join('public.ternaks', 'kematians.id', '=', 'ternaks.kematian_id')
                        ->whereBetween('kematians.tgl_kematian', [$request->datefrom, $request->dateto])
                        ->whereIn('ternaks.user_id', $user_ids)
                        ->get();
                }
                else{
                    $mati = [];
                }
            }
            else{
                $mati = Kematian::select('ternaks.necktag', 'ternaks.kematian_id', 'kematians.tgl_kematian', 'kematians.waktu_kematian', 'kematians.penyebab', 'kematians.kondisi', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                    ->join('public.ternaks', 'kematians.id', '=', 'ternaks.kematian_id')
                    ->whereBetween('kematians.tgl_kematian', [$request->datefrom, $request->dateto])
                    ->get();
            }
    
            return DataTables::of($mati)
                  ->addIndexColumn()
                  ->make(true);
        }
    }

    public function jual(Request $request)
    {
        if($request->ajax()){
            if($request->grup_id != null){
                $user_ids = User::where('grup_id', $request->grup_id)->pluck('id')->toArray();

                if($user_ids != null){
                    $jual = Penjualan::select('ternaks.necktag', 'ternaks.penjualan_id', 'penjualans.tgl_terjual', 'penjualans.ket_pembeli', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                        ->join('public.ternaks', 'penjualans.id', '=', 'ternaks.penjualan_id')
                        ->whereBetween('penjualans.tgl_terjual', [$request->datefrom, $request->dateto])
                        ->whereIn('ternaks.user_id', $user_ids)
                        ->get();
                }
                else{
                    $jual = [];
                }
            }
            else{
                $jual = Penjualan::select('ternaks.necktag', 'ternaks.penjualan_id', 'penjualans.tgl_terjual', 'penjualans.ket_pembeli', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                        ->join('public.ternaks', 'penjualans.id', '=', 'ternaks.penjualan_id')
                        ->whereBetween('penjualans.tgl_terjual', [$request->datefrom, $request->dateto])
                        ->get();
            }
            return DataTables::of($jual)
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
            if($request->grup_id != null){
                $user_ids = User::where('grup_id', $request->grup_id)->pluck('id')->toArray();

                if($user_ids != null){
                    $exists = Ternak::where('status_ada', true)
                        ->whereIn('user_id', $user_ids)
                        ->get();
                }
                else{
                    $exists = [];
                }
            }
            else{
                $exists = Ternak::where('status_ada', true)
                        ->get();
            }

            return DataTables::of($exists)
                  ->addIndexColumn()
                  ->make(true);
        } 
    }

    public function export($param) 
    {
        $sp = preg_split("/[=&]/", $param); 
        //0: datefrom, 1:tgl, 2:dateto, 3:tgl, 4:grup_id, 5:grup_id

        $export = new LaporanExport($sp[1], $sp[3], $sp[5], null);

        if($sp[5] != null){
            return Excel::download($export, 'SITERNAK_Laporan_'.$sp[1].'_'.$sp[3].'_grup_peternak_id_'.$sp[5].'.xlsx');
        }
        else{
            return Excel::download($export, 'SITERNAK_Laporan_'.$sp[1].'_'.$sp[3].'.xlsx');
        }
    }

}
