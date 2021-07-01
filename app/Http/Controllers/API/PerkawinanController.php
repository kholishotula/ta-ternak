<?php

namespace App\Http\Controllers\API;

use App\Ternak;
use App\Perkawinan;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PerkawinanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perkawinan = Perkawinan::orderBy("id")->get();

        return response()->json([
            'status' => 'success',
            'perkawinan' => $perkawinan,
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
            'necktag' => 'required',
            'necktag_psg' => 'required',
            'tgl_kawin' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $cek1 = Ternak::find($request->necktag);
        $cek2 = Ternak::find($request->necktag_psg);

        if($cek1->jenis_kelamin == $cek2->jenis_kelamin){
            return response()->json(['error' => 'Tidak dapat kawin jika jenis kelamin sama']);
        }

        $form_data = array(
            'necktag' => $request->necktag,
            'necktag_psg' => $request->necktag_psg,
            'tgl_kawin' => $request->tgl_kawin
        );

        $perkawinan = Perkawinan::create($form_data);

        return response()->json([
            'status' => 'success',
            'perkawinan' => $perkawinan,
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
        $perkawinan = Perkawinan::find($id);
        
        return response()->json([
            'status' => 'success',
            'perkawinan' => $perkawinan,
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
            'necktag' => 'required',
            'necktag_psg' => 'required',
            'tgl_kawin' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $cek1 = Ternak::find($request->necktag);
        $cek2 = Ternak::find($request->necktag_psg);

        if($cek1->jenis_kelamin == $cek2->jenis_kelamin){
            return response()->json(['error' => 'Tidak dapat kawin jika jenis kelamin sama']);
        }

        $form_data = array(
            'necktag' => $request->necktag,
            'necktag_psg' => $request->necktag_psg,
            'tgl_kawin' => $request->tgl_kawin
        );

        Perkawinan::whereId($id)->update($form_data);
        $perkawinan = Perkawinan::find($id);
        
        return response()->json([
            'status' => 'success',
            'perkawinan' => $perkawinan,
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
        $data = Perkawinan::find($id);
        $data->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Data perkawinan id ". $id ." telah berhasil dihapus.",
        ], 200);
    }
}
