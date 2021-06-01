<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class VerifikasiController extends Controller
{
    public function index(){
        return view('data.verifikasi');
    }

    public function getUsers(){
        $users = User::where('verified_at', null)->get();

        return DataTables::of($lahir)
                  ->make(true);
    }
}
