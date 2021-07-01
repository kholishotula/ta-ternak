<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VerifikasiController extends Controller
{
    public function index()
    {
        $users = User::where('verified_at', null)
                    ->where('role', '<>', 'admin')
                    ->get();

        return response()->json([
            'status' => 'success',
            'users' => $users,
        ], 200);
    }

    public function verifyUser($id){
        $user = User::where('id', $id)
                    ->update(['verified_at' => Carbon::now()]);
        
        return response()->json([
            'status' => 'success',
            'user' => $user,
        ], 200);
    }
}
