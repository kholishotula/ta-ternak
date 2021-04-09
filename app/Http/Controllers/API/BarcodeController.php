<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ternak;

class BarcodeController extends Controller
{
    public function index()
    {
    	$ternak = Ternak::withTrashed()->latest()->get(); 

        return response()->json([
            'status' => 'success',
            'ternak' => $ternak,
        ], 200);
    }
}
