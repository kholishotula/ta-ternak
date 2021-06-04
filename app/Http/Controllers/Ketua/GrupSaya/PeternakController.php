<?php

namespace App\Http\Controllers\Ketua\GrupSaya;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class PeternakController extends Controller
{
    public function index(){
        $title = 'PETERNAK DI GRUP SAYA';
        $page = 'Peternak di Grup Saya';

        return view('data.grup-saya.peternak')->with(['title'=> $title, 'page' => $page]);
    }

    public function getUsers(){
        $users = User::where('role', '=', 'peternak')
                    ->where('grup_id', Auth::user()->grup_id)
                    ->get();

        return DataTables::of($users)
                  ->addColumn('verified_at', function($data){
                      if($data->verified_at == null){
                          $unverified = '<span class="label label-warning">Belum diverifikasi</span>';
                          return $unverified;
                      }
                      else{
                          $verified = '<span class="label label-success">Terverifikasi</span>';
                          return $verified;
                      }
                  })
                  ->addColumn('action', function($row){
                      if($row->verified_at == null){
                        $btn = '<a href="grup-saya/peternak/verify/'.$row->id.'" class="btn btn-info btn-sm" style="margin: 2px;"><i class="material-icons">done</i> Verifikasi</button>';
                        return $btn;
                      }
                      else{
                        $btn = '<a class="btn btn-secondary btn-sm" style="margin: 2px;" disabled><i class="material-icons">done</i> Verifikasi</button>';
                        return $btn;
                      }
                  })
                  ->rawColumns(['verified_at', 'action'])
                  ->make(true);
    }

    public function verifyUser($id){
        $user = User::where('id', $id)->update(['verified_at' => Carbon::now()]);

        return redirect('ketua-grup/grup-saya/peternak');
    }
}
