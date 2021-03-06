<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Ternak;
use domPDF;
use DNS1D;

class BarcodeController extends Controller
{
    public function index()
    {
        $ketua_grup = Auth::user();
        $user_ids = User::where('grup_id', $ketua_grup->grup_id)->pluck('id')->toArray();

    	$ternak = Ternak::whereIn('user_id', $user_ids)->withTrashed()->latest()->paginate(15); 
	    $no = 1; 

        return view('home.barcode')->with('ternak', $ternak)->with('no', $no);
    }

    public function generatePdf()
    {
        $ketua_grup = Auth::user();
        $user_ids = User::where('grup_id', $ketua_grup->grup_id)->pluck('id')->toArray();

        $ternak = Ternak::whereIn('user_id', $user_ids)->withTrashed()->get();
        $no = 1;
        $html = '<h2 align="center">SITERNAK - Barcode Necktag</h2>';
        $html .= '<table>';
        $html .= '<tr>';

        foreach($ternak as $data){
            $html .= '<td>'.$no.'</td>';
            $html .= '<td align="center" style="border: lpx solid #ccc; padding-left: 10px; padding-right: 10px;">'.$data->necktag.'<br>';
            $html .= '<div style="padding-top: 10px; padding-bottom: 10px;">'.DNS1D::getBarcodeHTML($data->necktag, "C128", 2, 40).'</div>';
            $html .= $data->necktag.'</td>';

            if($no++ %4 == 0){
                $html .= '</tr>';
                $html .= '<tr style="margin-bottom: 10px;">';
            }
        }

        $html .= '</tr>';
        $html .= '</table>';

        $pdf = domPDF::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        // $pdf->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->download('SITERNAK-Barcode.pdf');
	}
}
