<?php

namespace App\Http\Controllers\Peternak;

use App\Ras;
use App\Ternak;
use App\Perkawinan;
use App\GrupPeternak;
use App\User;
use App\Charts\RasChart;
use App\Charts\UmurChart;
use App\Charts\KelahiranChart;
use App\Charts\KematianChart;
use App\Charts\PenjualanChart;
use App\Charts\PerkawinanChart;
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
        
        $yearNow = date('Y');
        $year = array();

        for($i = $yearNow; $i > $yearNow-5; $i--){
            $year[] = $i;
        }

        if($request->ajax()){
            return response()->json([
                'grup_id' => Auth::user()->grup_id,
                'years' => $year,
            ]);
        }

        return view('grafik.grafik')->with([
            'ras' => $ras,
            'umur' => $umur,
            'lahir'=> $lahir,
            'mati' => $mati,
            'jual' => $jual,
            'kawin'=> $kawin,
            'grup_id' => Auth::user()->grup_id,
            'years' => $year,
        ]);
    }

    public function grafikRas(Request $request)
    {
        $jantan = array();
        $betina = array();
        $label = array();
        $data = array();

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

        $chart = new RasChart;
        $chart->title('Grafik Ternak - Ras');
        $chart->displayLegend(true);
        $chart->labels($label);

        if($count_jantan != null){
            $chart->dataset('Jantan','bar', $jantan)->options([
                'responsive' => true,
                'fill' => 'true',
                'backgroundColor' => '#36A7C9',
                'borderColor' => '#1A89B4',
                 'tooltip' => [
                    'show' => 'true'
                ],
            ]);
        }

        if($count_betina != null){
            $chart->dataset('Betina','bar', $betina)->options([
                'responsive' => true,
                'fill' => 'true',
                'backgroundColor' => '#F8B195',
                'borderColor' => '#f67280',
                 'tooltip' => [
                    'show' => 'true'
                ],
            ]);
        }

        if($count != null){
            $chart->dataset('Jumlah Ternak', 'bar', $data)->options([
                'responsive' => true,
                'fill' => true,
                'backgroundColor' => '#B2DFDB',
                'borderColor' => '#009688',
                'tooltip' => [
                    'show' => true
                ],
            ]);
        }

        return $chart;
    }

    public function grafikUmur(Request $request)
    {
        $umurj = array();
        $umurb = array();
        $jantan = array();
        $betina = array();
        $label = array();
        $data = array();

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

        $chart = new UmurChart;
        $chart->title('Grafik Ternak - Umur (bulan)');
        $chart->labels($label);

        if($count_jantan != null){
            $chart->dataset('Jantan','bar', $jantan)->options([
                'responsive' => true,
                'fill' => 'true',
                'backgroundColor' => '#36A7C9',
                'borderColor' => '#1A89B4',
                 'tooltip' => [
                    'show' => 'true'
                ],
            ]);
        }

        if($count_betina != null){
            $chart->dataset('Betina','bar', $betina)->options([
                'responsive' => true,
                'fill' => 'true',
                'backgroundColor' => '#F8B195',
                'borderColor' => '#f67280',
                 'tooltip' => [
                    'show' => 'true'
                ],
            ]);
        }

        if($count != null){
            $chart->dataset('Jumlah Ternak', 'bar', $data)->options([
                'responsive' => true,
                'fill' => true,
                'backgroundColor' => '#CDDC39',
                'borderColor' => '#8BC34A',
                'legend' => [
                    'show' => true,
                 ],
                 'tooltip' => [
                    'show' => true
                ],
            ]);
        }

        return $chart;
    }

    public function grafikLahir(Request $request)
    {
        $yearNow = date('Y');

        if ($request->ajax()) {
           $yearNow = $request->tahun;
        }

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

        $chart = new KelahiranChart;
        $chart->title('Grafik Ternak - Kelahiran ('. $yearNow .')');
        $chart->labels(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);

        if($count_jantan != null){
            $chart->dataset('Jantan','bar', $jantan)->options([
                'responsive' => true,
                'fill' => 'true',
                'backgroundColor' => '#36A7C9',
                'borderColor' => '#1A89B4',
                 'tooltip' => [
                    'show' => 'true'
                ],
            ]);
        }

        if($count_betina != null){
            $chart->dataset('Betina','bar', $betina)->options([
                'responsive' => true,
                'fill' => 'true',
                'backgroundColor' => '#F8B195',
                'borderColor' => '#f67280',
                 'tooltip' => [
                    'show' => 'true'
                ],
            ]);
        }

        if($count != null){
            $chart->dataset('Jumlah Ternak','line', $data)->options([
                'responsive' => true,
                // 'fill' => 'true',
                // 'backgroundColor' => '#1A535C',
                'borderColor' => '#607D8B',
                 'tooltip' => [
                    'show' => true
                ],
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
               'data' => $data,
               'jantan' => $jantan,
               'betina' => $betina,
               'tahun' => $yearNow
            ]);
        }

        return $chart;
    }

    public function grafikMati(Request $request)
    {
        $yearNow = date('Y');

        if ($request->ajax()) {
           $yearNow = $request->tahun;
        }

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

        $chart = new KematianChart;
        $chart->title('Grafik Ternak - Kematian ('. $yearNow .')');
        $chart->labels(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);

        if($count_jantan != null){
            $chart->dataset('Jantan','bar', $jantan)->options([
                'responsive' => true,
                'fill' => true,
                'backgroundColor' => '#36A7C9',
                'borderColor' => '#1A89B4',
                 'tooltip' => [
                    'show' => true
                ],
            ]);
        }

        if($count_betina != null){
            $chart->dataset('Betina','bar', $betina)->options([
                'responsive' => true,
                'fill' => true,
                'backgroundColor' => '#F8B195',
                'borderColor' => '#f67280',
                 'tooltip' => [
                    'show' => true
                ],
            ]);
        }

        if($count != null){
            $chart->dataset('Jumlah Ternak', 'line', $data)->options([
                'responsive' => true,
                // 'fill' => 'true',
                // 'backgroundColor' => '#FFE0B2',
                'borderColor' => '#FF9800',
                 'tooltip' => [
                    'show' => true
                ],
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'data' => $data, 
                'jantan' => $jantan, 
                'betina' => $betina, 
                'tahun' => $yearNow
            ]);
        }

        return $chart;
    }

    public function grafikJual(Request $request)
    {
        $yearNow = date('Y');

        if ($request->ajax()) {
           $yearNow = $request->tahun;
        }

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

        for($i=0; $i<12; $i++){
            $data[$i] = 0;
            $jantan[$i] = 0;
            $betina[$i] = 0;
        }

        foreach($count as $jual){
        	$data[$jual->jual - 1] = $jual->jumlah;
        }

        foreach($count_jantan as $lahir){
            $jantan[$lahir->jual - 1] = $lahir->jumlah;
        }

        foreach($count_betina as $lahir){
            $betina[$lahir->jual - 1] = $lahir->jumlah;
        }

        $chart = new PenjualanChart;
        $chart->title('Grafik Ternak - Penjualan ('. $yearNow .')');
        $chart->labels(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);

        if($count_jantan != null){
            $chart->dataset('Jantan','bar', $jantan)->options([
                'responsive' => true,
                'fill' => true,
                'backgroundColor' => '#36A7C9',
                'borderColor' => '#1A89B4',
                 'tooltip' => [
                    'show' => true
                ],
            ]);
        }

        if($count_betina != null){
            $chart->dataset('Betina','bar', $betina)->options([
                'responsive' => true,
                'fill' => true,
                'backgroundColor' => '#F8B195',
                'borderColor' => '#f67280',
                 'tooltip' => [
                    'show' => true
                ],
            ]);
        }

        if($count != null){
    	    $chart->dataset('Jumlah Ternak', 'line', $data)->options([
                'responsive' => true,
    			// 'fill' => 'true',
    			// 'backgroundColor' => '#FFE0B2',
                'borderColor' => '#FF9800',
                 'tooltip' => [
                    'show' => true
                ],
    		]);
        }

        if ($request->ajax()) {
            return response()->json([
               'data' => $data, 
               'jantan' => $jantan, 
               'betina' => $betina, 
               'tahun' => $yearNow
            ]);
        }

		return $chart;
    }

    public function grafikKawin(Request $request)
    {
        $yearNow = date('Y');

        if ($request->ajax()) {
           $yearNow = $request->tahun;
        }

        $necktag_ternaks = Ternak::where('user_id', Auth::id())
                                    ->pluck('necktag')->toArray();

        $count = Perkawinan::whereIn('necktag', $necktag_ternaks)
                            ->whereYear('tgl_kawin', '=', $yearNow)
                            ->selectRaw('count(*) as jumlah, coalesce(extract(month from perkawinans.tgl_kawin), 0) as kawin')
                            ->groupBy('kawin')
                            ->orderBy('kawin')
                            ->get();

        for($i=0; $i<12; $i++){
            $data[$i] = 0;
        }

        foreach($count as $ternak){
        	$data[$ternak->kawin - 1] = $ternak->jumlah;
        }

        $chart = new PerkawinanChart;
        $chart->title('Grafik Ternak - Perkawinan ('. $yearNow .')');
        $chart->labels(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);

        if($count != null){
    	    $chart->dataset('Jumlah Ternak', 'bar', $data)->options([
                'responsive' => true,
    			'fill' => 'true',
    			'backgroundColor' => '#B2DFDB',
                'borderColor' => '#809689',
                 'tooltip' => [
                    'show' => true
                ],
    		]);
        }

        if ($request->ajax()) {
            return response()->json([
                'data' => $data,
                'tahun' => $yearNow
            ]);
        }

		return $chart;
    }
}
