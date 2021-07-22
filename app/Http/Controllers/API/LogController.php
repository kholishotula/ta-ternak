<?php

namespace App\Http\Controllers\API;

use App\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
