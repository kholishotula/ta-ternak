<?php

namespace App\Http\Controllers\API;

use App\Penyakit;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenyakitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $penyakit = Penyakit::orderBy("id")->get();

        return response()->json([
            'status' => 'success',
            'penyakit' => $penyakit,
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
            'nama_penyakit' => 'required',
            'ket_penyakit' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'nama_penyakit' => $request->nama_penyakit,
            'ket_penyakit' => $request->ket_penyakit
        );

        $penyakit = Penyakit::create($form_data);

        return response()->json([
            'status' => 'success',
            'penyakit' => $penyakit,
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
        $penyakit = Penyakit::find($id);
        
        return response()->json([
            'status' => 'success',
            'penyakit' => $penyakit,
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
            'nama_penyakit' => 'required',
            'ket_penyakit' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'nama_penyakit' => $request->nama_penyakit,
            'ket_penyakit' => $request->ket_penyakit
        );

        Penyakit::whereId($id)->update($form_data);
        $penyakit = Penyakit::find($id);
        
        return response()->json([
            'status' => 'success',
            'penyakit' => $penyakit,
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
        $data = Penyakit::find($id);

        $exists= DB::table('public.riwayat_penyakits')
                            ->where('penyakit_id', '=', $id)
                            ->first();

        if(is_null($exists)){
            $data->delete();
        }
        else{
            return response()->json([
                'status' => 'error',
                'message' => "Data penyakit id ". $id ." tidak dapat dihapus.",
            ], 200);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => "Data penyakit id ". $id ." telah berhasil dihapus.",
        ], 200);
    }
}
