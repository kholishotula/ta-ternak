<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Peternakan;
use App\User;
use App\Ternak;
use Validator;

class PeternakanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $peternakan = Peternakan::orderBy("id")->get();

        return response()->json([
            'status' => 'success',
            'peternakan' => $peternakan,
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
            'nama_peternakan' => 'required',
            'keterangan' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'nama_peternakan' => $request->nama_peternakan,
            'keterangan' => $request->keterangan
        );

        $peternakan = Peternakan::create($form_data);

        return response()->json([
            'status' => 'success',
            'peternakan' => $peternakan,
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
        $peternakan = Peternakan::find($id);
        
        return response()->json([
            'status' => 'success',
            'peternakan' => $peternakan,
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
            'nama_peternakan' => 'required',
            'keterangan' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'nama_peternakan' => $request->nama_peternakan,
            'keterangan' => $request->keterangan
        );

        Peternakan::find($id)->update($form_data);
        $peternakan = Peternakan::find($id);
        
        return response()->json([
            'status' => 'success',
            'peternakan' => $peternakan,
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
        $data = Peternakan::find($id);

        if(Ternak::where('peternakan_id', $id)->exists() || User::where('peternakan_id', $id)->exists()){
            return response()->json([
                'status' => 'error',
                'message' => "Data peternakan id ". $id ." tidak dapat dihapus.",
            ], 200);
        }
        else{
            $data->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => "Data peternakan id ". $id ." telah berhasil dihapus.",
        ], 200);
    }
}
