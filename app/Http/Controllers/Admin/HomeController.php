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
        $inst = DB::select('SELECT * FROM public."search_inst"(?)', [$request->necktag]);

    	if($inst != null){
            $inst[0]->searched = true;
            $spouse = DB::select('SELECT * FROM public."search_spouse"(?)', [$inst[0]->necktag]);
            $parents = DB::select('SELECT * FROM public."search_parent"(?,?)', [$inst[0]->ayah, $inst[0]->ibu]);
            $siblings = DB::select('SELECT * FROM public."search_sibling"(?,?,?)', [$inst[0]->necktag, $inst[0]->ayah, $inst[0]->ibu]);
            $gparents = DB::select('SELECT * FROM public."search_gparent"(?,?)', [$inst[0]->ayah, $inst[0]->ibu]);
            $children = DB::select('SELECT * FROM public."search_child"(?)', [$inst[0]->necktag]);
            $gchildren = DB::select('SELECT * FROM public."search_gchild"(?)', [$inst[0]->necktag]);
    	}
        else{
    		$result = [
                'result' => 'Tidak ada data ternak dengan necktag "' .$request->necktag. '".',
            ];
    		return response()->json(['errors' => $result]);
    	}
        return response()->json([
            'inst' => $inst[0],
            'spouse' => $spouse,
            'gparents' => $gparents,
            'parents' => $parents,
            'siblings' => $siblings,
            'children' => $children,
            'gchildren' => $gchildren
        ]);
    }
}
