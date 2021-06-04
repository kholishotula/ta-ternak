<?php

namespace App\Http\Controllers\Ketua\GrupSaya;

use App\User;
use App\Ternak;
use App\RiwayatPenyakit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class RiwayatPenyakitController extends Controller
{
    public function index(){
        $title = 'RIWAYAT PENYAKIT TERNAK DI GRUP SAYA';
        $page = 'Riwayat Penyakit Ternak di Grup Saya';

        return view('data.grup-saya.riwayat')->with(['title'=> $title, 'page' => $page]);
    }

    public function getRiwayats(){
        $user_ids = User::where('grup_id', Auth::user()->grup_id)
                        ->pluck('id')->toArray();
        $necktag_ternaks = Ternak::whereIn('user_id', $user_ids)
                                ->pluck('necktag')->toArray();
        $riwayats = RiwayatPenyakit::whereIn('necktag', $necktag_ternaks)
                                    ->orderBy('necktag', 'asc')
                                    ->get();

        return DataTables::of($riwayats)
                    ->addIndexColumn()
                    ->rawColumns(['DT_RowIndex'])
                    ->make(true);
    }
}
