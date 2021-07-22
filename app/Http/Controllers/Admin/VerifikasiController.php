<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class VerifikasiController extends Controller
{
    public function index(){
        $title = 'VERIFIKASI AKUN PETERNAK';
        $page = 'Verifikasi Akun Peternak';

        return view('data.verifikasi')->with(['title'=> $title, 'page' => $page]);
    }

    public function getUsers(){
        $users = User::where('verified_at', null)
                    ->where('role', '<>', 'admin')
                    ->get();

        return DataTables::of($users)
                  ->addColumn('verified_at', function($data){
                      if($data->verified_at == null){
                          $unverified = '<span class="label label-warning">Belum diverifikasi</span>';
                          return $unverified;
                      }
                  })
                  ->addColumn('action', function($row){
                        $btn = '<a href="verifikasi/users/'.$row->id.'" class="btn btn-success btn-sm" style="margin: 2px;"><i class="material-icons">done</i> Verifikasi</button>';
                        return $btn;
                  })
                  ->rawColumns(['verified_at', 'action'])
                  ->make(true);
    }

    public function verifyUser($id){
        $user = User::where('id', $id)->update(['verified_at' => Carbon::now()]);

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'update',
            'tabel' => 'users',
            'pk_tabel' => $id,
            'waktu' => Carbon::now()
        ]);

        return redirect('admin/verifikasi');
    }
}
