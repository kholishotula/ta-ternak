<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ternak;
use App\User;
use Illuminate\Support\Facades\Auth;

class BarcodeController extends Controller
{
    public function index()
    {
        if(Auth::user()->role == 'admin'){
    	    $ternak = Ternak::withTrashed()->latest()->get(); 
        }
        elseif(Auth::user()->role == 'ketua-grup'){
            $user_ids = User::where('grup_id', Auth::user()->grup_id)
                                ->pluck('id')->toArray();
    	    $ternak = Ternak::whereIn('user_id', $user_ids)
                        ->withTrashed()->latest()->get(); 
        }
        else{
    	    $ternak = Ternak::where('user_id', Auth::id())
                        ->withTrashed()->latest()->get(); 
        }

        return response()->json([
            'status' => 'success',
            'ternak' => $ternak,
        ], 200);
    }
}
