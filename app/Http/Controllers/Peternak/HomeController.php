<?php

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Ternak;
use App\Perkawinan;

class HomeController extends Controller
{
    public function index()
    {
    	$ternak = Ternak::count();

        $lahir = Ternak::where('tgl_lahir', '>', date("Y-m-d", strtotime('-29 days')))
                        ->whereNotNull('tgl_lahir')
                        ->selectRaw('count(*)')->first();

        $kawin = Perkawinan::where('tgl_kawin', '>', date("Y-m-d", strtotime('-29 days')))
                        ->whereNotNull('tgl_kawin')
                        ->selectRaw('count(*)/2 as count')->first();

        $mati = Ternak::join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                        ->whereNotNull('ternaks.kematian_id')
                        ->where('kematians.tgl_kematian', '>', date("Y-m-d", strtotime('-29 days')))
                        ->selectRaw('count(*)')->first();

        return view('home.dashboard')->with('total_ternak', $ternak)
        							  ->with('kelahiran_baru', $lahir)
        							  ->with('perkawinan_baru', $kawin)
        							  ->with('kematian_baru', $mati);
    }


    public function search(Request $request)
    {
        $inst = DB::select('SELECT public."search_inst"(?)', [$request->necktag]);

        if($inst != null ){
            $sp = preg_split("/[(),]/", $inst[0]->search_inst); 
            //split karena hasil bukan array, tapi string
            //0: kosong, 1:necktag, 2:jenis_kelamin, 3:ras, 4:tgl_lahir, 5:blood, 6:peternakan, 7:ayah, 8:ibu, 9:kosong

            $parent = DB::select('SELECT public."search_parent"(?,?)', [$sp[7], $sp[8]]);
            $sibling = DB::select('SELECT public."search_sibling"(?,?,?)', [$sp[1], $sp[7], $sp[8]]);
            $child = DB::select('SELECT public."search_child"(?)', [$sp[1]]);
            $gparent = DB::select('SELECT public."search_gparent"(?,?)', [$sp[7], $sp[8]]);
            $gchild = DB::select('SELECT public."search_gchild"(?)', [$sp[1]]);
            
            $data = [
                'inst' => $inst,
                'parent' => $parent,
                'sibling' => $sibling,
                'child' => $child,
                'gparent' => $gparent,
                'gchild' => $gchild
            ];  
        }
        else{
            $data = [
                'result' => 'Tidak ada data ternak dengan necktag ' .$request->necktag. '.',
                'necktag' => $request->necktag
            ];
            return response()->json(['errors' => $data]);
        }
        return response()->json(['result' => $data]);
    }
}
