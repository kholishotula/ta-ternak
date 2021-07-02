<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    public function search($id)
    {
		$inst = DB::select('SELECT * FROM public."search_inst"(?)', [$id]);

    	if($inst != null){
    		$spouse = DB::select('SELECT * FROM public."search_spouse"(?)', [$inst[0]->necktag]);
            $parents = DB::select('SELECT * FROM public."search_parent"(?,?)', [$inst[0]->ayah, $inst[0]->ibu]);
            $siblings = DB::select('SELECT * FROM public."search_sibling"(?,?,?)', [$inst[0]->necktag, $inst[0]->ayah, $inst[0]->ibu]);
            $gparents = DB::select('SELECT * FROM public."search_gparent"(?,?)', [$inst[0]->ayah, $inst[0]->ibu]);
            $children = DB::select('SELECT * FROM public."search_child"(?)', [$inst[0]->necktag]);
            $gchildren = DB::select('SELECT * FROM public."search_gchild"(?)', [$inst[0]->necktag]);

            //inst
			$dataInst = [
				'necktag' => $inst[0]->necktag,
				'jenis_kelamin' => $inst[0]->jenis_kelamin,
				'ras' => $inst[0]->jenis_ras,
				'tgl_lahir' => $inst[0]->tgl_lahir,
				'pemilik' => $inst[0]->pemilik,
				'peternak' => $inst[0]->peternak,
				'ayah' => $inst[0]->ayah,
				'ibu' => $inst[0]->ibu,
			];


			//spouse
			$dataSpouse = [];
			if($spouse[0] != null){
				$dataSpouse = [
					'necktag' => $spouse[0]->necktag,
					'jenis_kelamin' => $spouse[0]->jenis_kelamin,
					'ras' => $spouse[0]->jenis_ras,
					'tgl_lahir' => $spouse[0]->tgl_lahir,
					'pemilik' => $spouse[0]->pemilik,
					'peternak' => $spouse[0]->peternak,
					'ayah' => $spouse[0]->ayah,
					'ibu' => $spouse[0]->ibu,
				];
			}

			//parent
			$dataParent = [];
			if($parents != null){
	            foreach($parents as $n){
					$dataParent[] = [
						'necktag' => $n->necktag,
						'jenis_kelamin' => $n->jenis_kelamin,
						'ras' => $n->jenis_ras,
						'tgl_lahir' => $n->tgl_lahir,
						'pemilik' => $n->pemilik,
						'peternak' => $n->peternak,
						'ayah' => $n->ayah,
						'ibu' => $n->ibu,
					];
				}
			}

			//sibling
			$dataSibling = [];
			if($siblings != null){
				foreach($siblings as $n){
					$dataSibling[] = [
						'necktag' => $n->necktag,
						'jenis_kelamin' => $n->jenis_kelamin,
						'ras' => $n->jenis_ras,
						'tgl_lahir' => $n->tgl_lahir,
						'pemilik' => $n->pemilik,
						'peternak' => $n->peternak,
						'ayah' => $n->ayah,
						'ibu' => $n->ibu,
					];
				}
			}

			//child
			$dataChild = [];
			if($children != null){
				foreach($children as $n){
					$c = preg_split("/[(),]/", $n->search_child);
					$dataChild[] = [
						'necktag' => $n->necktag,
						'jenis_kelamin' => $n->jenis_kelamin,
						'ras' => $n->jenis_ras,
						'tgl_lahir' => $n->tgl_lahir,
						'pemilik' => $n->pemilik,
						'peternak' => $n->peternak,
						'ayah' => $n->ayah,
						'ibu' => $n->ibu,
					];
				}
			}

			//gparent
			$dataGParent = [];
			if($gparents != null){
				foreach($gparents as $n){
					$dataGParent[] = [
						'necktag' => $n->necktag,
						'jenis_kelamin' => $n->jenis_kelamin,
						'ras' => $n->jenis_ras,
						'tgl_lahir' => $n->tgl_lahir,
						'pemilik' => $n->pemilik,
						'peternak' => $n->peternak,
						'ayah' => $n->ayah,
						'ibu' => $n->ibu,
					];
				}
			}

			//gchild
			$dataGChild = [];
			if($gchildren != null){
				foreach($gchildren as $n){
					$dataGChild[] = [
						'necktag' => $n->necktag,
						'jenis_kelamin' => $n->jenis_kelamin,
						'ras' => $n->jenis_ras,
						'tgl_lahir' => $n->tgl_lahir,
						'pemilik' => $n->pemilik,
						'peternak' => $n->peternak,
						'ayah' => $n->ayah,
						'ibu' => $n->ibu,
					];
				}
			}

			
			$data = [
				'inst' => $dataInst,
				'spouse' => $dataSpouse,
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
