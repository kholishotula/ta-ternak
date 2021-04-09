<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    public function search($id)
    {
    	// if(Ternak::::where('necktag', $id)->exists()){}
        $inst = DB::select('SELECT public."search_inst"(?)', [$id]);

    	if($inst != null){
    		$sp = preg_split("/[(),]/", $inst[0]->search_inst); 
            //split karena hasil bukan array, tapi string
            //0: kosong, 1:necktag, 2:jenis_kelamin, 3:ras, 4:tgl_lahir, 5:blood, 6:peternakan, 7:ayah, 8:ibu, 9:kosong

            $parent = DB::select('SELECT public."search_parent"(?,?)', [$sp[7], $sp[8]]);
            $sibling = DB::select('SELECT public."search_sibling"(?,?,?)', [$sp[1], $sp[7], $sp[8]]);
            $child = DB::select('SELECT public."search_child"(?)', [$sp[1]]);
            $gparent = DB::select('SELECT public."search_gparent"(?,?)', [$sp[7], $sp[8]]);
            $gchild = DB::select('SELECT public."search_gchild"(?)', [$sp[1]]);


            //inst
			$dataInst = [
				'necktag' => $sp[1],
				'jenis_kelamin' => $sp[2],
				'ras' => $sp[3],
				'tgl_lahir' => $sp[4],
				'blood' => $sp[5],
				'peternakan' => $sp[6],
				'ayah' => $sp[7],
				'ibu' => $sp[8],
			];

			//parent
			$dataParent = [];
			if($parent != null){
	            foreach($parent as $n){
					$p = preg_split("/[(),]/", $n->search_parent);
					$dataParent[] = [
						'necktag' => $p[1],
						'jenis_kelamin' => $p[2],
						'ras' => $p[3],
						'tgl_lahir' => $p[4],
						'blood' => $p[5],
						'peternakan' => $p[6],
						'ayah' => $p[7],
						'ibu' => $p[8],
					];
				}
			}

			//sibling
			$dataSibling = [];
			if($sibling != null){
				foreach($sibling as $n){
					$s = preg_split("/[(),]/", $n->search_sibling);
					$dataSibling[] = [
						'necktag' => $s[1],
						'jenis_kelamin' => $s[2],
						'ras' => $s[3],
						'tgl_lahir' => $s[4],
						'blood' => $s[5],
						'peternakan' => $s[6],
						'ayah' => $s[7],
						'ibu' => $s[8],
					];
				}
			}

			//child
			$dataChild = [];
			if($child != null){
				foreach($child as $n){
					$c = preg_split("/[(),]/", $n->search_child);
					$dataChild[] = [
						'necktag' => $c[1],
						'jenis_kelamin' => $c[2],
						'ras' => $c[3],
						'tgl_lahir' => $c[4],
						'blood' => $c[5],
						'peternakan' => $c[6],
						'ayah' => $c[7],
						'ibu' => $c[8],
					];
				}
			}

			//gparent
			$dataGParent = [];
			if($gparent != null){
				foreach($gparent as $n){
					$gp = preg_split("/[(),]/", $n->search_gparent);
					$dataGParent[] = [
						'necktag' => $gp[1],
						'jenis_kelamin' => $gp[2],
						'ras' => $gp[3],
						'tgl_lahir' => $gp[4],
						'blood' => $gp[5],
						'peternakan' => $gp[6],
						'ayah' => $gp[7],
						'ibu' => $gp[8],
					];
				}
			}

			//gchild
			$dataGChild = [];
			if($gchild != null){
				foreach($gchild as $n){
					$gc = preg_split("/[(),]/", $n->search_gchild);
					$dataGChild[] = [
						'necktag' => $gc[1],
						'jenis_kelamin' => $gc[2],
						'ras' => $gc[3],
						'tgl_lahir' => $gc[4],
						'blood' => $gc[5],
						'peternakan' => $gc[6],
						'ayah' => $gc[7],
						'ibu' => $gc[8],
					];
				}
			}

			
			$data = [
				'inst' => $dataInst,
	        	'parent' => $dataParent,
	        	'sibling' => $dataSibling,
	        	'child' => $dataChild,
	        	'gparent' => $dataGParent,
	        	'gchild' => $dataGChild
	        ];
    	}
        else{
    		return response()->json([
	            'status' => 'error',
	            'message' => 'Tidak ada data ternak dengan necktag ' .$id. '.'
	        ], 200);
    	}

        return response()->json([
            'status' => 'success',
            'result' => $data,
        ], 200);
    }
}
