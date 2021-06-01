<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Ternak;

class MatchController extends Controller
{
    //fitur perkawinan

    public function index()
    {
        $ternak = Ternak::join('ras', 'ras.id', '=', 'ternaks.ras_id')->get();
        return view('perkawinan.match')->with('ternak', $ternak);
    }

    public function match(Request $request)
    {

    	$jantan = DB::select('SELECT public."search_inst"(?)', [$request->necktag_jt]);
    	$betina = $request->necktag_bt;

    	// trace salah satu saja -> jantan
    	if($jantan != null ){
			$sp = preg_split("/[(),]/", $jantan[0]->search_inst); 
	    	//split karena hasil bukan array, tapi string
	    	//0: kosong, 1:necktag, 2:jenis_kelamin, 3:ras, 4:tgl_lahir, 5:pemilik, 6:peternak, 7:ayah, 8:ibu, 9:kosong

			$j_parent = DB::select('SELECT public."search_parent"(?,?)', [$sp[7], $sp[8]]);
			foreach($j_parent as $jp){
				$j_parent_sp = preg_split("/[(),]/", $jp->search_parent);

				if($betina == $j_parent_sp[1]){
					return response()->json([
						'result' => 'gagal', 
						'message' => 'Tidak boleh dikawinkan karena hubungan ibu dan anak'
					]);
				}
			}
			
	        $j_sibling = DB::select('SELECT public."search_sibling"(?,?,?)', [$sp[1], $sp[7], $sp[8]]);
	        foreach($j_sibling as $js){
		        $j_sibling_sp = preg_split("/[(),]/", $js->search_sibling);
				
				if($betina == $j_sibling_sp[1]){
					return response()->json([
						'result' => 'gagal', 
						'message' => 'Tidak boleh dikawinkan karena hubungan saudara kandung'
					]);
				}
			}

	        $j_child = DB::select('SELECT public."search_child"(?)', [$sp[1]]);
	        foreach($j_child as $jc){
		        $j_child_sp = preg_split("/[(),]/", $jc->search_child);
				
				if($betina == $j_child_sp[1]){
					return response()->json([
						'result' => 'gagal', 
						'message' => 'Tidak boleh dikawinkan karena hubungan ayah dan anak'
					]);
				}
			}

	        $j_gparent = DB::select('SELECT public."search_gparent"(?,?)', [$sp[7], $sp[8]]);
	        foreach($j_gparent as $jgp){
		        $j_gparent_sp = preg_split("/[(),]/", $jgp->search_gparent);
				
				if($betina == $j_gparent_sp[1]){
					return response()->json([
						'result' => 'gagal', 
						'message' => 'Tidak boleh dikawinkan karena hubungan cucu dan nenek'
					]);
				}
			}

	        $j_gchild = DB::select('SELECT public."search_gchild"(?)', [$sp[1]]);
	        foreach($j_gchild as $jgc){
		        $j_gchild_sp = preg_split("/[(),]/", $jgc->search_gchild);
				
				if($betina == $j_gchild_sp[1]){
					return response()->json([
						'result' => 'gagal', 
						'message' => 'Tidak boleh dikawinkan karena hubungan kakek dan cucu'
					]);
				}
			}

	        return response()->json(['result' => 'berhasil']);
	    }
    }
}
