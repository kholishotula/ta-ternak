<?php

namespace App\Http\Controllers\API;

use App\Perkembangan;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class PerkembanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perkembangan = Perkembangan::orderBy("id")->get();

        return response()->json([
            'status' => 'success',
            'perkembangan'  => $perkembangan,
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
            'tgl_perkembangan' => 'required',
            'berat_badan' => 'required',
            'panjang_badan' => 'required',
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
            'tgl_perkembangan' => $request->tgl_perkembangan,
            'berat_badan' => $request->berat_badan,
            'panjang_badan' => $request->panjang_badan,
            'lingkar_dada' => $request->lingkar_dada,
            'tinggi_pundak' => $request->tinggi_pundak,
            'lingkar_skrotum' => $request->lingkar_skrotum,
            'keterangan' => $request->keterangan,
        );

        $perkembangan = Perkembangan::create($form_data);

        return response()->json([
            'status' => 'success',
            'perkembangan'  => $perkembangan,
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
        $perkembangan = Perkembangan::find($id);
        
        return response()->json([
            'status' => 'success',
            'perkembangan'  => $perkembangan,
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
            'tgl_perkembangan' => 'required',
            'berat_badan' => 'required',
            'panjang_badan' => 'required',
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
            'tgl_perkembangan' => $request->tgl_perkembangan,
            'berat_badan' => $request->berat_badan,
            'panjang_badan' => $request->panjang_badan,
            'lingkar_dada' => $request->lingkar_dada,
            'tinggi_pundak' => $request->tinggi_pundak,
            'lingkar_skrotum' => $request->lingkar_skrotum,
            'keterangan' => $request->keterangan,
            'updated_at' => Carbon::now()
        );

        Perkembangan::find($id)->update($form_data);
        $perkembangan = Perkembangan::find($id);
        
        return response()->json([
            'status' => 'success',
            'perkembangan'  => $perkembangan,
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
        $data = Perkembangan::find($id);
        $data->delete();

        return response()->json([
            'status' => 'success',
            'message'  => "Data perkembangan id ". $id ." telah berhasil dihapus.",
        ], 200);
    }
}
