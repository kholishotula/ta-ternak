<?php

namespace App\Http\Controllers\API;

use App\Ras;
use App\Ternak;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ras = Ras::orderBy("id")->get();

        return response()->json([
            'status' => 'success',
            'ras'  => $ras,
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
            'jenis_ras' => 'required',
            'ket_ras' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'jenis_ras' => $request->jenis_ras,
            'ket_ras' => $request->ket_ras
        );

        $ras = Ras::create($form_data);

        return response()->json([
            'status' => 'success',
            'ras'  => $ras,
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
        $ras = Ras::find($id);
        
        return response()->json([
            'status' => 'success',
            'ras'  => $ras,
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
            'jenis_ras' => 'required',
            'ket_ras' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'jenis_ras' => $request->jenis_ras,
            'ket_ras' => $request->ket_ras
        );

        Ras::find($id)->update($form_data);
        $ras = Ras::find($id);
        
        return response()->json([
            'status' => 'success',
            'ras'  => $ras,
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
        $data = Ras::find($id);
        
        if(Ternak::where('ras_id', $id)->exists()){
            return response()->json([
                'status' => 'error',
                'message' => "Data ras id ". $id ." tidak dapat dihapus.",
            ], 200);
        }
        else{
            $data->delete();
        }

        return response()->json([
            'status' => 'success',
            'message'  => "Data ras id ". $id ." telah berhasil dihapus.",
        ], 200);
    }
}
