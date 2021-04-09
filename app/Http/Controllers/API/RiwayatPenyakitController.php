<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiwayatPenyakitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $riwayat = DB::table('riwayat_penyakits')->orderBy("id")->get();

        return response()->json([
            'status' => 'success',
            'riwayat' => $riwayat,
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
            'penyakit_id' => 'required',
            'necktag' => 'required',
            'tgl_sakit' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'penyakit_id' => $request->penyakit_id,
            'necktag' => $request->necktag,
            'tgl_sakit' => $request->tgl_sakit,
            'obat' => $request->obat,
            'lama_sakit' => $request->lama_sakit,
            'keterangan' => $request->keterangan,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        );

        $create = DB::table('riwayat_penyakits')->insert($form_data);
        $id = DB::getPdo()->lastInsertId();
        $riwayat = DB::table('riwayat_penyakits')->find($id);

        return response()->json([
            'status' => 'success',
            'riwayat' => $riwayat,
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
        $riwayat = DB::table('riwayat_penyakits')->find($id);
        
        return response()->json([
            'status' => 'success',
            'riwayat' => $riwayat,
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
            'penyakit_id' => 'required',
            'necktag' => 'required',
            'tgl_sakit' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'penyakit_id' => $request->penyakit_id,
            'necktag' => $request->necktag,
            'tgl_sakit' => $request->tgl_sakit,
            'obat' => $request->obat,
            'lama_sakit' => $request->lama_sakit,
            'keterangan' => $request->keterangan,
            'updated_at' => Carbon::now()
        );

        DB::table('riwayat_penyakits')->whereId($id)->update($form_data);
        $riwayat = DB::table('riwayat_penyakits')->find($id);
        
        return response()->json([
            'status' => 'success',
            'riwayat' => $riwayat,
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
        $data = DB::table('riwayat_penyakits')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Data riwayat penyakit id ". $id ." telah berhasil dihapus.",
        ], 200);
    }
}
