<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Ternak;
use App\RiwayatPenyakit;
use App\Perkembangan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GrupSayaController extends Controller
{
    public function peternak(){
        $users = User::where('role', '=', 'peternak')
                    ->where('grup_id', Auth::user()->grup_id)
                    ->get();
        
        return response()->json([
            'status' => 'success',
            'users' => $users,
        ], 200);
    }

    public function ternak(){
        $user_ids = User::where('grup_id', Auth::user()->grup_id)
                        ->pluck('id')->toArray();
        $ternaks = Ternak::whereIn('user_id', $user_ids)->get();

        return response()->json([
            'status' => 'success',
            'ternaks' => $ternaks,
        ], 200);
    }

    public function riwayat(){
        $user_ids = User::where('grup_id', Auth::user()->grup_id)
                        ->pluck('id')->toArray();
        $necktag_ternaks = Ternak::whereIn('user_id', $user_ids)
                                ->pluck('necktag')->toArray();
        $riwayats = RiwayatPenyakit::whereIn('necktag', $necktag_ternaks)
                                    ->orderBy('necktag', 'asc')
                                    ->get();
                        
        return response()->json([
            'status' => 'success',
            'riwayats' => $riwayats,
        ], 200);
    }

    public function perkembangan(){
        $user_ids = User::where('grup_id', Auth::user()->grup_id)
                        ->pluck('id')->toArray();
        $necktag_ternaks = Ternak::whereIn('user_id', $user_ids)
                                ->pluck('necktag')->toArray();
        $perkembangans = Perkembangan::whereIn('necktag', $necktag_ternaks)
                                    ->orderBy('necktag', 'asc')
                                    ->get();

        return response()->json([
            'status' => 'success',
            'perkembangans' => $perkembangans,
        ], 200);
    }
}
