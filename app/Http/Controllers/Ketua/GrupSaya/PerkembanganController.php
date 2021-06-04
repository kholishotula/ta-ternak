<?php

namespace App\Http\Controllers\Ketua\GrupSaya;

use App\User;
use App\Ternak;
use App\Perkembangan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Yajra\DataTables\DataTables;

class PerkembanganController extends Controller
{
    public function index(){
        $title = 'PERKEMBANGAN TERNAK DI GRUP SAYA';
        $page = 'Perkembangan Ternak di Grup Saya';

        return view('data.grup-saya.perkembangan')->with(['title'=> $title, 'page' => $page]);
    }

    public function getPerkembangans(){
        $user_ids = User::where('grup_id', Auth::user()->grup_id)
                        ->pluck('id')->toArray();
        $necktag_ternaks = Ternak::whereIn('user_id', $user_ids)
                                ->pluck('necktag')->toArray();
        $perkembangans = Perkembangan::whereIn('necktag', $necktag_ternaks)
                                    ->orderBy('necktag', 'asc')
                                    ->get();

        // $baseURL = explode('ketua-grup/', URL::current())[0];

        return DataTables::of($perkembangans)
                    ->addIndexColumn()
                    ->addColumn('foto', function($data){
                        if($data->foto != null){
                            return '<img src="'.explode('ketua-grup/', URL::current())[0].$data->foto.'" style="width: 150px">';
                        }
                        else{
                            return null;
                        }
                    })
                    ->rawColumns(['DT_RowIndex', 'foto'])
                    ->make(true);
    }
}
