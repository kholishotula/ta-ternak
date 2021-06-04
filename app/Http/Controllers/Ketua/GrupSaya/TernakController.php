<?php

namespace App\Http\Controllers\Ketua\GrupSaya;

use App\User;
use App\Ternak;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class TernakController extends Controller
{
    public function index(){
        $title = 'TERNAK DI GRUP SAYA';
        $page = 'Ternak di Grup Saya';

        return view('data.grup-saya.ternak')->with(['title'=> $title, 'page' => $page]);
    }

    public function getTernaks(){
        $user_ids = User::where('grup_id', Auth::user()->grup_id)
                        ->pluck('id')->toArray();
        $ternaks = Ternak::whereIn('user_id', $user_ids)->get();

        return DataTables::of($ternaks)
                    ->addIndexColumn()
                    ->addColumn('status_ada', function($data){
                        if($data->status_ada == true){
                            return '<span class="label label-success">Ada</span>';
                        }
                        else{
                            return '<span class="label label-danger">Tidak ada</span>';
                        }
                    })
                    ->rawColumns(['DT_RowIndex', 'status_ada'])
                    ->make(true);
    }
}
