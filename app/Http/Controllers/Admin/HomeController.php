<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Ternak;
use App\Perkawinan;
use App\Kematian;

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

        $mati = Kematian::where('tgl_kematian', '>', date("Y-m-d", strtotime('-29 days')))
                        ->whereNotNull('tgl_kematian')
                        ->selectRaw('count(*)')->first();

        return view('home.dashboard')->with('total_ternak', $ternak)
        							  ->with('kelahiran_baru', $lahir)
        							  ->with('perkawinan_baru', $kawin)
        							  ->with('kematian_baru', $mati);
    }

    public function search(Request $request)
    {
        $result = [];

        $inst = DB::select('SELECT * FROM public."search_inst"(?)', [$request->necktag]);
        // $inst[0]->searched = true;

    	if($inst != null){
            $spouse = DB::select('SELECT * FROM public."search_spouse"(?)', [$inst[0]->necktag]);
            $parents = DB::select('SELECT * FROM public."search_parent"(?,?)', [$inst[0]->ayah, $inst[0]->ibu]);
            $siblings = DB::select('SELECT * FROM public."search_sibling"(?,?,?)', [$inst[0]->necktag, $inst[0]->ayah, $inst[0]->ibu]);
            $gparents = DB::select('SELECT * FROM public."search_gparent"(?,?)', [$inst[0]->ayah, $inst[0]->ibu]);
            if($spouse != null){
                $children = DB::select('SELECT * FROM public."search_child"(?)', [$inst[0]->necktag]);
                $gchildren = DB::select('SELECT * FROM public."search_gchild"(?)', [$inst[0]->necktag]);
            }
	        
            if($gparents != null){
                for($i = 0; $i < sizeof($gparents); $i++)
                    array_push($result, $gparents[$i]);
            }
            if($parents != null){
                for($i = 0; $i < sizeof($parents); $i++)
                    array_push($result, $parents[$i]);
            }
            if($spouse != null){
                array_push($result, $spouse[0]);
            }
            array_push($result, $inst[0]);
            if($siblings != null){
                for($i = 0; $i < sizeof($siblings); $i++)
                    array_push($result, $siblings[$i]);
            }
            if($spouse != null){
                if($children != null){
                    for($i = 0; $i < sizeof($children); $i++)
                        array_push($result, $children[$i]);
                }
                if($gchildren != null){
                    for($i = 0; $i < sizeof($gchildren); $i++)
                        array_push($result, $gchildren[$i]);
                }
            }
    	}
        else{
    		$result = [
                'result' => 'Tidak ada data ternak dengan necktag ' .$request->necktag. '.',
            ];
    		return response()->json(['errors' => $result]);
    	}
        return response()->json(['result' => $result]);
    }
}
