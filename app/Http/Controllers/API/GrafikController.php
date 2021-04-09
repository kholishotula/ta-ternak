<?php

namespace App\Http\Controllers\API;

use App\Ras;
use App\Ternak;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GrafikController extends Controller
{
    public function index(Request $request)
    {
	    $ras = $this->grafikRas();
	    $umur = $this->grafikUmur();
	    $lahir = $this->grafikLahir($request);
	    $mati = $this->grafikMati($request);

        return response()->json([
            'status' => 'success',
            'ras' => $ras,
            'umur' => $umur,
            'lahir'=> $lahir,
            'mati' => $mati,
        ], 200);

    }

    public function grafikRas()
    {
        $jantan = array();
        $betina = array();
        $rasb = array();
        $rasj = array();
        $label = array();
        $data = array();

        $count = Ternak::where('status_ada', '=', true)
                        ->rightJoin('ras', 'ras.id', '=', 'ternaks.ras_id')
                        ->groupBy('ras.jenis_ras')
                        ->orderBy('ras.jenis_ras')
                        ->selectRaw('ras.jenis_ras as ras, coalesce(count(ternaks.necktag), 0) as jumlah')
                        ->get();

        $count_jantan = Ternak::where('status_ada', '=', true)
                        ->where('jenis_kelamin', '=', 'Jantan')
                        ->rightJoin('ras', 'ras.id', '=', 'ternaks.ras_id')
                        ->groupBy('ras.jenis_ras')
                        ->orderBy('ras.jenis_ras')
                        ->selectRaw('ras.jenis_ras as ras, coalesce(count(ternaks.necktag), 0) as jumlah')
                        ->get();

        $count_betina = Ternak::where('status_ada', '=', true)
                        ->where('jenis_kelamin', '=', 'Betina')
                        ->rightJoin('ras', 'ras.id', '=', 'ternaks.ras_id')
                        ->groupBy('ras.jenis_ras')
                        ->orderBy('ras.jenis_ras')
                        ->selectRaw('ras.jenis_ras as ras, coalesce(count(ternaks.necktag), 0) as jumlah')
                        ->get();

        $i = 0;
        foreach($count as $ras){
        	$label[] = $ras->ras;
        	$data[] = $ras->jumlah;
            $rasb[$i] = null;
            $rasj[$i] = null;
            $i++;
        }

        $i = 0;
        foreach($count_jantan as $ras){
            $rasj[$i] = $ras->ras;
            $jt[] = $ras->jumlah;
            $i++;
        }

        $i = 0;
        foreach($count_betina as $ras){
            $rasb[$i] = $ras->ras;
            $bt[] = $ras->jumlah;
            $i++;
        }

        $j = 0;
        $b = 0;

        if($label != null){
            for($i = 0; $i < count($label); $i++){
                if($rasj != null){
                    if($rasj[$b] == null){
                        $jantan[$i] = 0;
                    }
                    else{
                        if($label[$i] == $rasj[$j]){
                            $jantan[$i] = $jt[$j];
                            $j++;
                        }
                        else{
                            $jantan[$i] = 0;
                        }
                    }
                }else{
                    $jantan[$i] = 0;
                }

                if($rasb != null){
                    if($rasb[$b] == null){
                        $betina[$i] = 0;
                    }
                    else {
                        if($label[$i] == $rasb[$b]){
                            $betina[$i] = $bt[$b];
                            $b++;
                        }
                        else{
                            $betina[$i] = 0;
                        }
                    }
                }else{
                    $betina[$i] = 0;
                }
            }
        }

       	return [
       		'label' => $label,
       		'data' => $data, 
       		'jantan' => $jantan, 
       		'betina' => $betina
       	];

    }

    public function grafikUmur()
    {
        $umurj = array();
        $umurb = array();
        $jantan = array();
        $betina = array();
        $label = array();
        $data = array();

        $count = Ternak::where('status_ada', '=', true)
                        ->selectRaw('count(*) as jumlah, coalesce((extract(year from current_date) - 
    extract(year from ternaks.tgl_lahir)), 0) as tahun')
                        ->groupBy('tahun')
                        ->orderBy('tahun')
                        ->get();

        $count_jantan = Ternak::where('status_ada', '=', true)
                        ->where('jenis_kelamin', '=', 'Jantan')
                        ->selectRaw('count(*) as jumlah, coalesce((extract(year from current_date) - 
    extract(year from ternaks.tgl_lahir)), 0) as tahun')
                        ->groupBy('tahun')
                        ->orderBy('tahun')
                        ->get();

        $count_betina = Ternak::where('status_ada', '=', true)
                        ->where('jenis_kelamin', '=', 'Betina')
                        ->selectRaw('count(*) as jumlah, coalesce((extract(year from current_date) - 
    extract(year from ternaks.tgl_lahir)), 0) as tahun')
                        ->groupBy('tahun')
                        ->orderBy('tahun')
                        ->get();

        foreach($count as $umur){
        	$label[] = $umur->tahun;
        	$data[] = $umur->jumlah;
        }
        
        foreach($count_jantan as $umur){
            $umurj[] = $umur->tahun;
            $jt[] = $umur->jumlah;
        }

        foreach($count_betina as $umur){
            $umurb[] = $umur->tahun;
            $bt[] = $umur->jumlah;
        }

        $j = 0;
        $b = 0;

        if($label != null){
            for($i = 0; $i < count($label); $i++){
                if($umurj != null){
                    if($label[$i] == $umurj[$j]){
                        $jantan[$i] = $jt[$j];
                        $j++;
                    }
                    else{
                        $jantan[$i] = 0;
                    }
                }else{
                    $jantan[$i] = 0;
                }

                if($umurb != null){
                    if($label[$i] == $umurb[$b]){
                        $betina[$i] = $bt[$b];
                        $b++;
                    }
                    else{
                        $betina[$i] = 0;
                    }
                }else{
                    $betina[$i] = 0;
                }
            }
        }
        
        return [
        	'label' => $label,
       		'data' => $data, 
       		'jantan' => $jantan, 
       		'betina' => $betina
       	];
    }

    public function grafikLahir(Request $request)
    {
        $jantan = array();
        $betina = array();
        $label = array();
        $data = array();

        $yearNow = date('Y');

        if ($request->tahun) {
           $yearNow = $request->tahun;
        }

        $count = Ternak::whereYear('tgl_lahir', '=' , $yearNow)
                        ->selectRaw('count(*) as jumlah, coalesce(extract(month from tgl_lahir), 0) as lahir')
                        ->groupBy('lahir')
                        ->orderBy('lahir')
                        ->get();

        $count_jantan = Ternak::whereYear('tgl_lahir', '=' , $yearNow)
                        ->where('jenis_kelamin', '=', 'Jantan')
                        ->selectRaw('count(*) as jumlah, coalesce(extract(month from tgl_lahir), 0) as lahir')
                        ->groupBy('lahir')
                        ->orderBy('lahir')
                        ->get();

        $count_betina = Ternak::whereYear('tgl_lahir', '=' , $yearNow)
                        ->where('jenis_kelamin', '=', 'Betina')
                        ->selectRaw('count(*) as jumlah, coalesce(extract(month from tgl_lahir), 0) as lahir')
                        ->groupBy('lahir')
                        ->orderBy('lahir')
                        ->get();

        for($i=0; $i<12; $i++){
            $data[$i] = 0;
            $jantan[$i] = 0;
            $betina[$i] = 0;
        }
    
        foreach($count as $lahir){
        	$data[$lahir->lahir - 1] = $lahir->jumlah;
        }

        foreach($count_jantan as $lahir){
            $jantan[$lahir->lahir - 1] = $lahir->jumlah;
        }

        foreach($count_betina as $lahir){
            $betina[$lahir->lahir - 1] = $lahir->jumlah;
        }

        $label = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $data = [
            'label' => $label,
            'data' => $data, 
            'jantan' => $jantan, 
            'betina' => $betina,
        ];

        if ($request->tahun) {
           return response()->json([
                'status' => 'success',
                'data' => $data,
            ], 200);
        }

        return $data;
    }

    public function grafikMati(Request $request)
    {
        $jantan = array();
        $betina = array();
        $label = array();
        $data = array();
        
        $yearNow = date('Y');

        if ($request->tahun) {
           $yearNow = $request->tahun;
        }

        $count = Ternak::join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                        ->whereNotNull('kematian_id')
                        ->whereYear('tgl_kematian', '=', $yearNow)
                        ->selectRaw('count(*) as jumlah, coalesce(extract(month from kematians.tgl_kematian), 0) as mati')
                        ->groupBy('mati')
                        ->orderBy('mati')
                        ->get();

        $count_jantan = Ternak::join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                        ->whereNotNull('kematian_id')
                        ->whereYear('tgl_kematian', '=', $yearNow)
                        ->where('ternaks.jenis_kelamin', '=', 'Jantan')
                        ->selectRaw('count(*) as jumlah, coalesce(extract(month from kematians.tgl_kematian), 0) as mati')
                        ->groupBy('mati')
                        ->orderBy('mati')
                        ->get();

        $count_betina = Ternak::join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                        ->whereNotNull('kematian_id')
                        ->whereYear('tgl_kematian', '=', $yearNow)
                        ->where('ternaks.jenis_kelamin', '=', 'Betina')
                        ->selectRaw('count(*) as jumlah, coalesce(extract(month from kematians.tgl_kematian), 0) as mati')
                        ->groupBy('mati')
                        ->orderBy('mati')
                        ->get();

        for($i=0; $i<12; $i++){
            $data[$i] = 0;
            $jantan[$i] = 0;
            $betina[$i] = 0;
        }

        foreach($count as $mati){
        	$data[$mati->mati - 1] = $mati->jumlah;
        }

        foreach($count_jantan as $lahir){
            $jantan[$lahir->mati - 1] = $lahir->jumlah;
        }

        foreach($count_betina as $lahir){
            $betina[$lahir->mati - 1] = $lahir->jumlah;
        }

        $label = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $data = [
            'label' => $label,
            'data' => $data, 
            'jantan' => $jantan, 
            'betina' => $betina,
        ];

        if ($request->tahun) {
           return response()->json([
                'status' => 'success',
                'data' => $data,
            ], 200);
        }

        return $data;
    }
}
