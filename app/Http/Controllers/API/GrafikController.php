<?php

namespace App\Http\Controllers\API;

use App\Ras;
use App\Ternak;
use App\Perkawinan;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GrafikController extends Controller
{
    public function index(Request $request)
    {
	    $ras = $this->grafikRas($request);
	    $umur = $this->grafikUmur($request);
	    $lahir = $this->grafikLahir($request);
	    $mati = $this->grafikMati($request);
        $jual = $this->grafikJual($request);
        $kawin = $this->grafikKawin($request);

        return response()->json([
            'status' => 'success',
            'ras' => $ras,
            'umur' => $umur,
            'lahir'=> $lahir,
            'mati' => $mati,
            'jual' => $jual,
            'kawin' => $kawin,
        ], 200);

    }

    public function grafikRas(Request $request)
    {
        $jantan = array();
        $betina = array();
        $rasb = array();
        $rasj = array();
        $label = array();
        $data = array();

        $grup_id = null;

        if(Auth::user()->role == 'peternak'){
            $count = Ternak::where('status_ada', '=', true)
                        ->where('user_id', Auth::id())
                        ->rightJoin('ras', 'ras.id', '=', 'ternaks.ras_id')
                        ->groupBy('ras.jenis_ras')
                        ->orderBy('ras.jenis_ras')
                        ->selectRaw('ras.jenis_ras as ras, coalesce(count(ternaks.necktag), 0) as jumlah')
                        ->get();

            $count_jantan = Ternak::where('status_ada', '=', true)
                            ->where('user_id', Auth::id())
                            ->where('jenis_kelamin', '=', 'Jantan')
                            ->rightJoin('ras', 'ras.id', '=', 'ternaks.ras_id')
                            ->groupBy('ras.jenis_ras')
                            ->orderBy('ras.jenis_ras')
                            ->selectRaw('ras.jenis_ras as ras, coalesce(count(ternaks.necktag), 0) as jumlah')
                            ->get();

            $count_betina = Ternak::where('status_ada', '=', true)
                            ->where('user_id', Auth::id())
                            ->where('jenis_kelamin', '=', 'Betina')
                            ->rightJoin('ras', 'ras.id', '=', 'ternaks.ras_id')
                            ->groupBy('ras.jenis_ras')
                            ->orderBy('ras.jenis_ras')
                            ->selectRaw('ras.jenis_ras as ras, coalesce(count(ternaks.necktag), 0) as jumlah')
                            ->get();
        }
        else{
            if($request->grup_id != null){
                $grup_id = $request->grup_id;
            }

            if($grup_id == null){
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
            }
            else{
                $user_ids = User::where('grup_id', $grup_id)
                                ->pluck('id')->toArray();
                $count = Ternak::where('status_ada', '=', true)
                                ->whereIn('user_id', $user_ids)
                                ->rightJoin('ras', 'ras.id', '=', 'ternaks.ras_id')
                                ->groupBy('ras.jenis_ras')
                                ->orderBy('ras.jenis_ras')
                                ->selectRaw('ras.jenis_ras as ras, coalesce(count(ternaks.necktag), 0) as jumlah')
                                ->get();

                $count_jantan = Ternak::where('status_ada', '=', true)
                                ->where('jenis_kelamin', '=', 'Jantan')
                                ->whereIn('user_id', $user_ids)
                                ->rightJoin('ras', 'ras.id', '=', 'ternaks.ras_id')
                                ->groupBy('ras.jenis_ras')
                                ->orderBy('ras.jenis_ras')
                                ->selectRaw('ras.jenis_ras as ras, coalesce(count(ternaks.necktag), 0) as jumlah')
                                ->get();

                $count_betina = Ternak::where('status_ada', '=', true)
                                ->where('jenis_kelamin', '=', 'Betina')
                                ->whereIn('user_id', $user_ids)
                                ->rightJoin('ras', 'ras.id', '=', 'ternaks.ras_id')
                                ->groupBy('ras.jenis_ras')
                                ->orderBy('ras.jenis_ras')
                                ->selectRaw('ras.jenis_ras as ras, coalesce(count(ternaks.necktag), 0) as jumlah')
                                ->get();
            }
        }

        $i = 0;
        foreach($count as $ternak){
            $label[] = $ternak->ras;
            $data[] = $ternak->jumlah;
            $rasb[$i] = null;
            $rasj[$i] = null;
            $i++;
        }

        $i = 0;
        foreach($count_jantan as $ternak){
            $rasj[$i] = $ternak->ras;
            $jt[] = $ternak->jumlah;
            $i++;
        }

        $i = 0;
        foreach($count_betina as $ternak){
            $rasb[$i] = $ternak->ras;
            $bt[] = $ternak->jumlah;
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

        $data = [
            'label' => $label,
            'data' => $data, 
            'jantan' => $jantan, 
            'betina' => $betina,
        ];

        if ($grup_id != null) {
            return response()->json([
                 'status' => 'success',
                 'data' => $data,
             ], 200);
         }

       	return $data;

    }

    public function grafikUmur(Request $request)
    {
        $umurj = array();
        $umurb = array();
        $jantan = array();
        $betina = array();
        $label = array();
        $data = array();

        $grup_id = null;

        if(Auth::user()->role == 'peternak'){
            $count = Ternak::where('status_ada', '=', true)
                        ->where('user_id', Auth::id())
                        ->selectRaw('count(*) as jumlah, coalesce((extract(month from current_date) -
    extract(month from ternaks.tgl_lahir)), 0) as bulan')
                        ->groupBy('bulan')
                        ->orderBy('bulan')
                        ->get();

        $count_jantan = Ternak::where('status_ada', '=', true)
                        ->where('user_id', Auth::id())
                        ->where('jenis_kelamin', '=', 'Jantan')
                        ->selectRaw('count(*) as jumlah, coalesce((extract(month from current_date) -
    extract(month from ternaks.tgl_lahir)), 0) as bulan')
                        ->groupBy('bulan')
                        ->orderBy('bulan')
                        ->get();

        $count_betina = Ternak::where('status_ada', '=', true)
                        ->where('user_id', Auth::id())
                        ->where('jenis_kelamin', '=', 'Betina')
                        ->selectRaw('count(*) as jumlah, coalesce((extract(month from current_date) -
    extract(month from ternaks.tgl_lahir)), 0) as bulan')
                        ->groupBy('bulan')
                        ->orderBy('bulan')
                        ->get();
        }
        else{
            if($request->grup_id != null){
                $grup_id = $request->grup_id;
            }

            if($grup_id == null){
                $count = Ternak::where('status_ada', '=', true)
                            ->selectRaw('count(*) as jumlah, coalesce((extract(month from current_date) -
        extract(month from ternaks.tgl_lahir)), 0) as bulan')
                            ->groupBy('bulan')
                            ->orderBy('bulan')
                            ->get();

                $count_jantan = Ternak::where('status_ada', '=', true)
                                ->where('jenis_kelamin', '=', 'Jantan')
                                ->selectRaw('count(*) as jumlah, coalesce((extract(month from current_date) -
            extract(month from ternaks.tgl_lahir)), 0) as bulan')
                                ->groupBy('bulan')
                                ->orderBy('bulan')
                                ->get();

                $count_betina = Ternak::where('status_ada', '=', true)
                                ->where('jenis_kelamin', '=', 'Betina')
                                ->selectRaw('count(*) as jumlah, coalesce((extract(month from current_date) -
            extract(month from ternaks.tgl_lahir)), 0) as bulan')
                                ->groupBy('bulan')
                                ->orderBy('bulan')
                                ->get();
            }
            else{
                $user_ids = User::where('grup_id', $grup_id)
                                ->pluck('id')->toArray();
                $count = Ternak::where('status_ada', '=', true)
                                ->whereIn('user_id', $user_ids)
                                ->selectRaw('count(*) as jumlah, coalesce((extract(month from current_date) -
            extract(month from ternaks.tgl_lahir)), 0) as bulan')
                                ->groupBy('bulan')
                                ->orderBy('bulan')
                                ->get();

                $count_jantan = Ternak::where('status_ada', '=', true)
                                ->where('jenis_kelamin', '=', 'Jantan')
                                ->whereIn('user_id', $user_ids)
                                ->selectRaw('count(*) as jumlah, coalesce((extract(month from current_date) -
            extract(month from ternaks.tgl_lahir)), 0) as bulan')
                                ->groupBy('bulan')
                                ->orderBy('bulan')
                                ->get();

                $count_betina = Ternak::where('status_ada', '=', true)
                                ->where('jenis_kelamin', '=', 'Betina')
                                ->whereIn('user_id', $user_ids)
                                ->selectRaw('count(*) as jumlah, coalesce((extract(month from current_date) -
            extract(month from ternaks.tgl_lahir)), 0) as bulan')
                                ->groupBy('bulan')
                                ->orderBy('bulan')
                                ->get();
            }
        }

        $i = 0;
        foreach($count as $umur){
        	$label[] = $umur->bulan;
        	$data[] = $umur->jumlah;
            $umurj[$i] = null;
            $umurb[$i] = null;
            $i++;
        }
        
        $i = 0;
        foreach($count_jantan as $umur){
            $umurj[$i] = $umur->bulan;
            $jt[] = $umur->jumlah;
            $i++;
        }

        $i = 0;
        foreach($count_betina as $umur){
            $umurb[$i] = $umur->bulan;
            $bt[] = $umur->jumlah;
            $i++;
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
        
        $data = [
            'label' => $label,
            'data' => $data, 
            'jantan' => $jantan, 
            'betina' => $betina,
        ];

        if ($grup_id != null) {
            return response()->json([
                 'status' => 'success',
                 'data' => $data,
             ], 200);
         }

       	return $data;
    }

    public function grafikLahir(Request $request)
    {
        $jantan = array();
        $betina = array();
        $label = array();
        $data = array();

        $yearNow = date('Y');
        $grup_id = null;

        if ($request->tahun) {
           $yearNow = $request->tahun;
        }

        if(Auth::user()->role == 'peternak'){
            $count = Ternak::whereYear('tgl_lahir', '=' , $yearNow)
                        ->where('user_id', Auth::id())
                        ->selectRaw('count(*) as jumlah, coalesce(extract(month from tgl_lahir), 0) as lahir')
                        ->groupBy('lahir')
                        ->orderBy('lahir')
                        ->get();

            $count_jantan = Ternak::whereYear('tgl_lahir', '=' , $yearNow)
                            ->where('user_id', Auth::id())
                            ->where('jenis_kelamin', '=', 'Jantan')
                            ->selectRaw('count(*) as jumlah, coalesce(extract(month from tgl_lahir), 0) as lahir')
                            ->groupBy('lahir')
                            ->orderBy('lahir')
                            ->get();

            $count_betina = Ternak::whereYear('tgl_lahir', '=' , $yearNow)
                            ->where('user_id', Auth::id())
                            ->where('jenis_kelamin', '=', 'Betina')
                            ->selectRaw('count(*) as jumlah, coalesce(extract(month from tgl_lahir), 0) as lahir')
                            ->groupBy('lahir')
                            ->orderBy('lahir')
                            ->get();
        }
        else{
            if($request->grup_id != null){
                $grup_id = $request->grup_id;
            }

            if($grup_id == null){
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
            }
            else{
                $user_ids = User::where('grup_id', $grup_id)
                            ->pluck('id')->toArray();
                $count = Ternak::whereYear('tgl_lahir', '=' , $yearNow)
                                ->whereIn('user_id', $user_ids)
                                ->selectRaw('count(*) as jumlah, coalesce(extract(month from tgl_lahir), 0) as lahir')
                                ->groupBy('lahir')
                                ->orderBy('lahir')
                                ->get();

                $count_jantan = Ternak::whereYear('tgl_lahir', '=' , $yearNow)
                                ->whereIn('user_id', $user_ids)
                                ->where('jenis_kelamin', '=', 'Jantan')
                                ->selectRaw('count(*) as jumlah, coalesce(extract(month from tgl_lahir), 0) as lahir')
                                ->groupBy('lahir')
                                ->orderBy('lahir')
                                ->get();

                $count_betina = Ternak::whereYear('tgl_lahir', '=' , $yearNow)
                                ->whereIn('user_id', $user_ids)
                                ->where('jenis_kelamin', '=', 'Betina')
                                ->selectRaw('count(*) as jumlah, coalesce(extract(month from tgl_lahir), 0) as lahir')
                                ->groupBy('lahir')
                                ->orderBy('lahir')
                                ->get();
            }
        }

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
        $grup_id = null;

        if ($request->tahun) {
           $yearNow = $request->tahun;
        }

        if(Auth::user()->role == 'peternak'){
            $count = Ternak::join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                        ->where('user_id', Auth::id())
                        ->whereNotNull('kematian_id')
                        ->whereYear('tgl_kematian', '=', $yearNow)
                        ->selectRaw('count(*) as jumlah, coalesce(extract(month from kematians.tgl_kematian), 0) as mati')
                        ->groupBy('mati')
                        ->orderBy('mati')
                        ->get();

            $count_jantan = Ternak::join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                            ->where('user_id', Auth::id())
                            ->whereNotNull('kematian_id')
                            ->whereYear('tgl_kematian', '=', $yearNow)
                            ->where('ternaks.jenis_kelamin', '=', 'Jantan')
                            ->selectRaw('count(*) as jumlah, coalesce(extract(month from kematians.tgl_kematian), 0) as mati')
                            ->groupBy('mati')
                            ->orderBy('mati')
                            ->get();

            $count_betina = Ternak::join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                            ->where('user_id', Auth::id())
                            ->whereNotNull('kematian_id')
                            ->whereYear('tgl_kematian', '=', $yearNow)
                            ->where('ternaks.jenis_kelamin', '=', 'Betina')
                            ->selectRaw('count(*) as jumlah, coalesce(extract(month from kematians.tgl_kematian), 0) as mati')
                            ->groupBy('mati')
                            ->orderBy('mati')
                            ->get();
        }
        else{
            if($request->grup_id != null){
                $grup_id = $request->grup_id;
            }

            if($grup_id == null){
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
            }
            else{
                $user_ids = User::where('grup_id', $grup_id)
                            ->pluck('id')->toArray();
                $count = Ternak::whereIn('user_id', $user_ids)
                            ->join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                            ->whereNotNull('kematian_id')
                            ->whereYear('tgl_kematian', '=', $yearNow)
                            ->selectRaw('count(*) as jumlah, coalesce(extract(month from kematians.tgl_kematian), 0) as mati')
                            ->groupBy('mati')
                            ->orderBy('mati')
                            ->get();

                $count_jantan = Ternak::whereIn('user_id', $user_ids)
                                ->join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                                ->whereNotNull('kematian_id')
                                ->whereYear('tgl_kematian', '=', $yearNow)
                                ->where('ternaks.jenis_kelamin', '=', 'Jantan')
                                ->selectRaw('count(*) as jumlah, coalesce(extract(month from kematians.tgl_kematian), 0) as mati')
                                ->groupBy('mati')
                                ->orderBy('mati')
                                ->get();

                $count_betina = Ternak::whereIn('user_id', $user_ids)
                                ->join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                                ->whereNotNull('kematian_id')
                                ->whereYear('tgl_kematian', '=', $yearNow)
                                ->where('ternaks.jenis_kelamin', '=', 'Betina')
                                ->selectRaw('count(*) as jumlah, coalesce(extract(month from kematians.tgl_kematian), 0) as mati')
                                ->groupBy('mati')
                                ->orderBy('mati')
                                ->get();
            }
        }

        for($i=0; $i<12; $i++){
            $data[$i] = 0;
            $jantan[$i] = 0;
            $betina[$i] = 0;
        }

        foreach($count as $mati){
        	$data[$mati->mati - 1] = $mati->jumlah;
        }

        foreach($count_jantan as $mati){
            $jantan[$mati->mati - 1] = $mati->jumlah;
        }

        foreach($count_betina as $mati){
            $betina[$mati->mati - 1] = $mati->jumlah;
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

    public function grafikJual(Request $request)
    {
        $jantan = array();
        $betina = array();
        $label = array();
        $data = array();
        
        $yearNow = date('Y');
        $grup_id = null;

        if ($request->tahun) {
           $yearNow = $request->tahun;
        }

        if(Auth::user()->role == 'peternak'){
            $count = Ternak::join('penjualans', 'penjualans.id', '=', 'ternaks.penjualan_id')
                        ->where('user_id', Auth::id())
                        ->whereNotNull('penjualan_id')
                        ->whereYear('tgl_terjual', '=', $yearNow)
                        ->selectRaw('count(*) as jumlah, coalesce(extract(month from penjualans.tgl_terjual), 0) as jual')
                        ->groupBy('jual')
                        ->orderBy('jual')
                        ->get();

            $count_jantan = Ternak::join('penjualans', 'penjualans.id', '=', 'ternaks.penjualan_id')
                            ->where('user_id', Auth::id())
                            ->whereNotNull('penjualan_id')
                            ->whereYear('tgl_terjual', '=', $yearNow)
                            ->where('ternaks.jenis_kelamin', '=', 'Jantan')
                            ->selectRaw('count(*) as jumlah, coalesce(extract(month from penjualans.tgl_terjual), 0) as jual')
                            ->groupBy('jual')
                            ->orderBy('jual')
                            ->get();

            $count_betina = Ternak::join('penjualans', 'penjualans.id', '=', 'ternaks.penjualan_id')
                            ->where('user_id', Auth::id())
                            ->whereNotNull('penjualan_id')
                            ->whereYear('tgl_terjual', '=', $yearNow)
                            ->where('ternaks.jenis_kelamin', '=', 'Betina')
                            ->selectRaw('count(*) as jumlah, coalesce(extract(month from penjualans.tgl_terjual), 0) as jual')
                            ->groupBy('jual')
                            ->orderBy('jual')
                            ->get();
        }
        else{
            if($request->grup_id != null){
                $grup_id = $request->grup_id;
            }

            if($grup_id == null){
                $count = Ternak::join('penjualans', 'penjualans.id', '=', 'ternaks.penjualan_id')
                            ->whereNotNull('penjualan_id')
                            ->whereYear('tgl_terjual', '=', $yearNow)
                            ->selectRaw('count(*) as jumlah, coalesce(extract(month from penjualans.tgl_terjual), 0) as jual')
                            ->groupBy('jual')
                            ->orderBy('jual')
                            ->get();

                $count_jantan = Ternak::join('penjualans', 'penjualans.id', '=', 'ternaks.penjualan_id')
                                ->whereNotNull('penjualan_id')
                                ->whereYear('tgl_terjual', '=', $yearNow)
                                ->where('ternaks.jenis_kelamin', '=', 'Jantan')
                                ->selectRaw('count(*) as jumlah, coalesce(extract(month from penjualans.tgl_terjual), 0) as jual')
                                ->groupBy('jual')
                                ->orderBy('jual')
                                ->get();

                $count_betina = Ternak::join('penjualans', 'penjualans.id', '=', 'ternaks.penjualan_id')
                                ->whereNotNull('penjualan_id')
                                ->whereYear('tgl_terjual', '=', $yearNow)
                                ->where('ternaks.jenis_kelamin', '=', 'Betina')
                                ->selectRaw('count(*) as jumlah, coalesce(extract(month from penjualans.tgl_terjual), 0) as jual')
                                ->groupBy('jual')
                                ->orderBy('jual')
                                ->get();
            }
            else{
                $user_ids = User::where('grup_id', $grup_id)
                            ->pluck('id')->toArray();
                $count = Ternak::whereIn('user_id', $user_ids)
                            ->join('penjualans', 'penjualans.id', '=', 'ternaks.penjualan_id')
                            ->whereNotNull('penjualan_id')
                            ->whereYear('tgl_terjual', '=', $yearNow)
                            ->selectRaw('count(*) as jumlah, coalesce(extract(month from penjualans.tgl_terjual), 0) as jual')
                            ->groupBy('jual')
                            ->orderBy('jual')
                            ->get();

                $count_jantan = Ternak::whereIn('user_id', $user_ids)
                                ->join('penjualans', 'penjualans.id', '=', 'ternaks.penjualan_id')
                                ->whereNotNull('penjualan_id')
                                ->whereYear('tgl_terjual', '=', $yearNow)
                                ->where('ternaks.jenis_kelamin', '=', 'Jantan')
                                ->selectRaw('count(*) as jumlah, coalesce(extract(month from penjualans.tgl_terjual), 0) as jual')
                                ->groupBy('jual')
                                ->orderBy('jual')
                                ->get();

                $count_betina = Ternak::whereIn('user_id', $user_ids)
                                ->join('penjualans', 'penjualans.id', '=', 'ternaks.penjualan_id')
                                ->whereNotNull('penjualan_id')
                                ->whereYear('tgl_terjual', '=', $yearNow)
                                ->where('ternaks.jenis_kelamin', '=', 'Betina')
                                ->selectRaw('count(*) as jumlah, coalesce(extract(month from penjualans.tgl_terjual), 0) as jual')
                                ->groupBy('jual')
                                ->orderBy('jual')
                                ->get();
            }
        }

        for($i=0; $i<12; $i++){
            $data[$i] = 0;
            $jantan[$i] = 0;
            $betina[$i] = 0;
        }

        foreach($count as $jual){
        	$data[$jual->jual - 1] = $jual->jumlah;
        }

        foreach($count_jantan as $jual){
            $jantan[$jual->jual - 1] = $jual->jumlah;
        }

        foreach($count_betina as $jual){
            $betina[$jual->jual - 1] = $jual->jumlah;
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

    public function grafikKawin(Request $request){
        $label = array();
        $data = array();
        
        $yearNow = date('Y');
        $grup_id = null;

        if ($request->tahun) {
           $yearNow = $request->tahun;
        }

        if(Auth::user()->role == 'peternak'){
            $necktag_ternaks = Ternak::where('user_id', Auth::id())
                                    ->pluck('necktag')->toArray();

            $count = Perkawinan::whereIn('necktag', $necktag_ternaks)
                            ->whereYear('tgl_kawin', '=', $yearNow)
                            ->selectRaw('count(*) as jumlah, coalesce(extract(month from perkawinans.tgl_kawin), 0) as kawin')
                            ->groupBy('kawin')
                            ->orderBy('kawin')
                            ->get();
        }
        else{
            if($request->grup_id != null){
                $grup_id = $request->grup_id;
            }

            if($grup_id == null){
                $count = Perkawinan::whereYear('tgl_kawin', '=', $yearNow)
                            ->selectRaw('count(*)/2 as jumlah, coalesce(extract(month from perkawinans.tgl_kawin), 0) as kawin')
                            ->groupBy('kawin')
                            ->orderBy('kawin')
                            ->get();
            }
            else{
                $user_ids = User::where('grup_id', $grup_id)
                            ->pluck('id')->toArray();
                $necktag_ternaks = Ternak::whereIn('user_id', $user_ids)
                                        ->pluck('necktag')->toArray();

                $count = Perkawinan::whereIn('necktag', $necktag_ternaks)
                            ->whereYear('tgl_kawin', '=', $yearNow)
                            ->selectRaw('count(*) as jumlah, coalesce(extract(month from perkawinans.tgl_kawin), 0) as kawin')
                            ->groupBy('kawin')
                            ->orderBy('kawin')
                            ->get();
            }
        }
        
        for($i=0; $i<12; $i++){
            $data[$i] = 0;
        }

        foreach($count as $ternak){
        	$data[$ternak->kawin - 1] = $ternak->jumlah;
        }

        $label = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $data = [
            'label' => $label,
            'data' => $data, 
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
