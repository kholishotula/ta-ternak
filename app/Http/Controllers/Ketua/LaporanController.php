<?php

namespace App\Http\Controllers\Ketua;

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
use App\Perkembangan;
use App\Kematian;
use App\Penjualan;
use App\GrupPeternak;
use App\User;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $grup_id = Auth::user()->grup_id;
        $nama_grup = GrupPeternak::where('id', $grup_id)->first()
                    ->nama_grup;
        
        if($request->ajax()){
            return response()->json([
                'start' => $request->datefrom,
                'end' => $request->dateto,
                'grup_id' => $grup_id,
                'nama_grup' => $nama_grup
            ]);
        }

        return view('laporan.laporan')->with('grup_id', $grup_id);
    }

    public function lahir(Request $request)
    {
        if($request->ajax()){
            $user_ids = User::where('grup_id', $request->grup_id)
                            ->pluck('id')->toArray();
            
            $lahir = Ternak::whereBetween('tgl_lahir', [$request->datefrom, $request->dateto])
                            ->whereIn('user_id', $user_ids)
                            ->get();

            return DataTables::of($lahir)
                  ->addIndexColumn()
                  ->make(true);
        }
    }

    public function mati(Request $request)
    {
        if($request->ajax()){
            $user_ids = User::where('grup_id', $request->grup_id)
                            ->pluck('id')->toArray();
            
            $mati = Kematian::select('ternaks.necktag', 'ternaks.kematian_id', 'kematians.tgl_kematian', 'kematians.waktu_kematian', 'kematians.penyebab', 'kematians.kondisi', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                        ->join('public.ternaks', 'kematians.id', '=', 'ternaks.kematian_id')
                        ->whereBetween('kematians.tgl_kematian', [$request->datefrom, $request->dateto])
                        ->whereIn('ternaks.user_id', $user_ids)
                        ->get();
    
            return DataTables::of($mati)
                  ->addIndexColumn()
                  ->make(true);
        }
    }

    public function jual(Request $request)
    {
        if($request->ajax()){
            $user_ids = User::where('grup_id', $request->grup_id)
                            ->pluck('id')->toArray();
            
            $jual = Penjualan::select('ternaks.necktag', 'ternaks.penjualan_id', 'penjualans.tgl_terjual', 'penjualans.ket_pembeli', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                        ->join('public.ternaks', 'penjualans.id', '=', 'ternaks.penjualan_id')
                        ->whereBetween('penjualans.tgl_terjual', [$request->datefrom, $request->dateto])
                        ->whereIn('ternaks.user_id', $user_ids)
                        ->get();
        
            return DataTables::of($jual)
                  ->addIndexColumn()
                  ->make(true);
        }
    }

    public function kawin(Request $request)
    {
        if($request->ajax()){
            $user_ids = User::where('grup_id', $request->grup_id)
                            ->pluck('id')->toArray();
            
            $kawin = Perkawinan::select('perkawinans.*')
                                ->join('public.ternaks', 'perkawinans.necktag', '=', 'ternaks.necktag')
                                ->whereIn('ternaks.user_id', $user_ids)
                                ->whereBetween('tgl_kawin', [$request->datefrom, $request->dateto])->get();

            return DataTables::of($kawin)
                  ->make(true);
        }
    }

    public function sakit(Request $request)
    {
        if($request->ajax()){
            $user_ids = User::where('grup_id', $request->grup_id)
                            ->pluck('id')->toArray();

            $sakit = RiwayatPenyakit::select('riwayat_penyakits.*')
                                    ->join('public.ternaks', 'riwayat_penyakits.necktag', '=', 'ternaks.necktag')
                                    ->whereIn('ternaks.user_id', $user_ids)
                                    ->whereBetween('tgl_sakit', [$request->datefrom, $request->dateto])->get();

            return DataTables::of($sakit)
                  ->make(true);
        }
    }

    public function perkembangan(Request $request){
        if($request->ajax()){
            $user_ids = User::where('grup_id', $request->grup_id)
                            ->pluck('id')->toArray();

            $perkembangan = Perkembangan::select('perkembangans.*', 'ternaks.jenis_kelamin')
                                        ->join('public.ternaks', 'perkembangans.necktag', '=', 'ternaks.necktag')
                                        ->whereBetween('perkembangans.tgl_perkembangan', [$request->datefrom, $request->dateto])
                                        ->whereIn('ternaks.user_id', $user_ids)
                                        ->get();

            return DataTables::of($perkembangan)
                  ->make(true);
        }
    }

    public function ada(Request $request)
    {
        if($request->ajax()){
            $user_ids = User::where('grup_id', $request->grup_id)
                            ->pluck('id')->toArray();

            $existFromDead = Ternak::join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                            ->whereIn('ternaks.user_id', $user_ids)
                            ->where('kematians.tgl_kematian', '>', $request->dateto)
                            ->where('ternaks.tgl_lahir', '<=', $request->dateto)
                            ->selectRaw('ternaks.*');

            $existFromSold = Ternak::join('penjualans', 'penjualans.id', '=', 'ternaks.penjualan_id')
                            ->whereIn('ternaks.user_id', $user_ids)
                            ->where('penjualans.tgl_terjual', '>', $request->dateto)
                            ->where('ternaks.tgl_lahir', '<=', $request->dateto)
                            ->selectRaw('ternaks.*');

            $exists = Ternak::where('status_ada', true)
                    ->whereIn('user_id', $user_ids)
                    ->where('tgl_lahir', '<=', $request->dateto)
                    ->union($existFromDead)
                    ->union($existFromSold)
                    ->get();

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

        $nama_grup = GrupPeternak::where('id', $sp[5])->first()->nama_grup;
        return Excel::download($export, 'SITERNAK_Laporan_'.$sp[1].'_'.$sp[3].'_grup_peternak_'.$nama_grup.'.xlsx');
    }
}
