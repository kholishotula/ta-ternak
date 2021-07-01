<?php

namespace App\Http\Controllers\API;

use App\Penjualan;
use App\Ternak;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $penjualan = Penjualan::orderBy("id")->get();

        return response()->json([
            'status' => 'success',
            'penjualan' => $penjualan,
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
        if(Penjualan::where('necktag', $request->necktag)->exists()){
            return response()->json([
                'status' => 'error',
                'error' => ['Data penjualan untuk ternak '.$request->necktag.' sudah ada.']
            ]);
        }

        $rules = array(
            'necktag' => 'required',
            'tgl_terjual' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'necktag' => $request->necktag,
            'tgl_terjual' => $request->tgl_terjual,
            'ket_pembeli' => $request->ket
        );

        $penjualan = Penjualan::create($form_data);

        return response()->json([
            'status' => 'success',
            'penjualan' => $penjualan,
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
        $penjualan = Penjualan::find($id);
        
        return response()->json([
            'status' => 'success',
            'penjualan' => $penjualan,
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
        if(Penjualan::where('necktag', $request->necktag)
                    ->where('id', '<>', $id)
                    ->exists()){
            return response()->json(['errors' => ['Data penjualan untuk ternak '.$request->necktag.' sudah ada.']]);
        }

        $rules = array(
            'necktag' => 'required',
            'tgl_terjual' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'necktag' => $request->necktag,
            'tgl_terjual' => $request->tgl_terjual,
            'ket_pembeli' => $request->ket
        );

        Penjualan::whereId($id)->update($form_data);
        $penjualan = Penjualan::find($id);
        
        return response()->json([
            'status' => 'success',
            'penjualan' => $penjualan,
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
        $data = Penjualan::findOrFail($id);
        $data->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Data perkawinan id ". $id ." telah berhasil dihapus.",
        ], 200);
    }
}
