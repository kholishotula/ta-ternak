<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WilayahController extends Controller
{
    public function getKabupaten($id){        
        $client = new Client();
        $response = $client->get('https://kholishotula.github.io/api-wilayah-indonesia/api/regencies/'.$id.'.json');
        $kab = json_decode($response->getBody(), true);

        return response()->json(['kab' => $kab]);
    }

    public function getKecamatan($id){
        $client = new Client();
        $response = $client->get('https://kholishotula.github.io/api-wilayah-indonesia/api/districts/'.$id.'.json');
        $kec = json_decode($response->getBody(), true);

        return response()->json(['kec' => $kec]);
    }
}
