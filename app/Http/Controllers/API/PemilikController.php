<?php

namespace App\Http\Controllers\API;

use App\Pemilik;
use App\Ternak;
use App\Log;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PemilikController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pemilik = Pemilik::orderBy("id")->get();

        return response()->json([
            'status' => 'success',
            'pemilik'  => $pemilik,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'nama_pemilik' => 'required',
            'ktp_pemilik' => 'required|digits:16|unique:pemiliks'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'nama_pemilik' => $request->nama_pemilik,
            'ktp_pemilik' => $request->ktp_pemilik
        );

        $pemilik = Pemilik::create($form_data);

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'insert',
            'tabel' => 'pemiliks',
            'pk_tabel' => $pemilik->id,
            'waktu' => Carbon::now()
        ]);

        return response()->json([
            'status' => 'success',
            'pemilik'  => $pemilik,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pemilik = Pemilik::find($id);
        
        return response()->json([
            'status' => 'success',
            'pemilik'  => $pemilik,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'nama_pemilik' => 'required',
            'ktp_pemilik' => 'required|digits:16'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'nama_pemilik' => $request->nama_pemilik,
            'ktp_pemilik' => $request->ktp_pemilik
        );

        Pemilik::whereId($id)->update($form_data);
        $pemilik = Pemilik::find($id);

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'update',
            'tabel' => 'pemiliks',
            'pk_tabel' => $id,
            'waktu' => Carbon::now()
        ]);
        
        return response()->json([
            'status' => 'success',
            'pemilik' => $pemilik,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Pemilik::find($id);

        if(Ternak::where('pemilik_id', $id)->exists()){
            return response()->json([
                'status' => 'error',
                'message' => "Data pemilik id ". $id ." tidak dapat dihapus.",
            ], 200);
        }
        else{
            $data->delete();
            Log::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'delete',
                'tabel' => 'pemiliks',
                'pk_tabel' => $id,
                'waktu' => Carbon::now()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => "Data pemilik id ". $id ." telah berhasil dihapus.",
        ], 200);
    }
}
