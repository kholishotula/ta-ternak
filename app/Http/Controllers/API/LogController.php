<?php

namespace App\Http\Controllers\API;

use App\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::all();

        return response()->json([
            'status' => 'success',
            'logs' => $logs,
        ], 200);
    }
    public function getLogBeforeTime(Request $request)
    {
        $waktu = DateTime::createFromFormat("Y-m-d H:i:s", $request->waktu);
        $tabel = $request->tabel;

        $logs = Log::where('waktu', '<', $waktu)
                    ->where('tabel', '=', $tabel)
                    ->get();
        
        return response()->json([
            'status' => 'success',
            'logs' => $logs,
        ], 200);
    }

    public function getLogActivityBased(Request $request)
    {
        $waktu = DateTime::createFromFormat("Y-m-d H:i:s", $request->waktu);
        $tabel = $request->tabel;
        $aktivitas = $request->aktivitas;

        $logs = Log::where([
                        ['waktu', '<', $waktu],
                        ['tabel', '=', $tabel],
                        ['aktivitas', '=', $aktivitas]
                    ])
                    ->get();
        
        return response()->json([
            'status' => 'success',
            'logs' => $logs,
        ], 200);
    }

    public function getLogDataBased(Request $request)
    {
        $waktu = DateTime::createFromFormat("Y-m-d H:i:s", $request->waktu);
        $tabel = $request->tabel;
        $pk_tabel = $request->pk_tabel;
        $aktivitas = $request->aktivitas;

        $log = Log::where([
                        ['waktu', '>', $waktu],
                        ['tabel', '=', $tabel],
                        ['pk_tabel', '=', $pk_tabel],
                        ['aktivitas', '=', $aktivitas]
                    ])
                    ->orderBy('waktu', 'desc')
                    ->first();
        
        return response()->json([
            'status' => 'success',
            'log' => $log,
        ], 200);
    }
}
