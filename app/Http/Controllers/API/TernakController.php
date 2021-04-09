<?php

namespace App\Http\Controllers\API;

use App\Ternak;
use App\Perkawinan;
use Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TernakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ternak = Ternak::orderBy("created_at")->get();

        return response()->json([
            'status' => 'success',
            'ternak' => $ternak,
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
            'peternakan_id' => 'required',            
            'ras_id' => 'required',
            'jenis_kelamin' => 'required',
            'blood' => 'required',
            'tgl_lahir' => 'required',
            'status_ada' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $necktag = Str::random(6);
        while(Ternak::where('necktag', $necktag)->exists()) {
            $necktag = Str::random(6);
        }

        $form_data = array(
            'necktag' => $necktag,
            'pemilik_id' => $request->pemilik_id,
            'peternakan_id' => $request->peternakan_id,
            'ras_id' => $request->ras_id,
            'kematian_id' => $request->kematian_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tgl_lahir' => $request->tgl_lahir,
            'bobot_lahir' => $request->bobot_lahir,
            'pukul_lahir' => $request->pukul_lahir,
            'lama_dikandungan' => $request->lama_dikandungan,
            'lama_laktasi' => $request->lama_laktasi,
            'tgl_lepas_sapih' => $request->tgl_lepas_sapih,
            'blood' => $request->blood,
            'necktag_ayah' => $request->necktag_ayah,
            'necktag_ibu' => $request->necktag_ibu,
            'bobot_tubuh' => $request->bobot_tubuh,
            'panjang_tubuh' => $request->panjang_tubuh,
            'tinggi_tubuh' => $request->tinggi_tubuh,
            'cacat_fisik' => $request->cacat_fisik,
            'ciri_lain' => $request->ciri_lain,
            'status_ada' => $request->status_ada
        );

        $ternak = Ternak::create($form_data);

        return response()->json([
            'status' => 'success',
            'ternak' => $ternak,
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
        $ternak = Ternak::find($id);
        
        return response()->json([
            'status' => 'success',
            'ternak' => $ternak,
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
            'peternakan_id' => 'required',
            'ras_id' => 'required',
            'jenis_kelamin' => 'required',
            'blood' => 'required',
            'tgl_lahir' => 'required',
            'status_ada' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        if($request->necktag_ayah == $id || $request->necktag_ibu == $id){
            $err = 'Individu tidak bisa menjadi orangtua untuk dirinya sendiri';
            return response()->json(['error' => $err]);
        }

        $form_data = array(
            'necktag' => $id,
            'pemilik_id' => $request->pemilik_id,
            'peternakan_id' => $request->peternakan_id,
            'ras_id' => $request->ras_id,
            'kematian_id' => $request->kematian_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tgl_lahir' => $request->tgl_lahir,
            'bobot_lahir' => $request->bobot_lahir,
            'pukul_lahir' => $request->pukul_lahir,
            'lama_dikandungan' => $request->lama_dikandungan,
            'lama_laktasi' => $request->lama_laktasi,
            'tgl_lepas_sapih' => $request->tgl_lepas_sapih,
            'blood' => $request->blood,
            'necktag_ayah' => $request->necktag_ayah,
            'necktag_ibu' => $request->necktag_ibu,
            'bobot_tubuh' => $request->bobot_tubuh,
            'panjang_tubuh' => $request->panjang_tubuh,
            'tinggi_tubuh' => $request->tinggi_tubuh,
            'cacat_fisik' => $request->cacat_fisik,
            'ciri_lain' => $request->ciri_lain,
            'status_ada' => $request->status_ada
        );

        Ternak::where('necktag',$id)->update($form_data);
        $ternak = Ternak::find($id);
        
        return response()->json([
            'status' => 'success',
            'ternak' => $ternak,
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
        $data = Ternak::find($id);

        if(Perkawinan::where('necktag', $id)->exists()){
            return response()->json([
                'status' => 'error',
                'message' => "Data ternak id ". $id ." tidak dapat dihapus.",
            ], 200);
        }
        else{
            $data->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => "Data ternak id ". $id ." telah berhasil dihapus.",
        ], 200);
    }


    //-----------------------------trash-----------------------------------

    //trash
    public function trash()
    {
        $ternak = Ternak::onlyTrashed()->orderBy("deleted_at")->get();

        return response()->json([
            'status' => 'success',
            'ternak' => $ternak,
        ], 200);
    }

    public function trashid($id)
    {
        $ternak = Ternak::onlyTrashed()->find($id);

        return response()->json([
            'status' => 'success',
            'ternak' => $ternak,
        ], 200);
    }

    //restore 
    public function restore($id)
    {
        $ternak = Ternak::onlyTrashed()->where('necktag',$id);
        $ternak->restore();

        return response()->json([
            'status' => 'success',
            'message' => "Data ternak id ". $id ." telah berhasil dikembalikan.",
        ], 200);
    }

    public function restoreAll()
    {
        $ternak = Ternak::onlyTrashed();
        $ternak->restore();

        return response()->json([
            'status' => 'success',
            'message' => "Data ternak pada tong sampah telah berhasil dikembalikan.",
        ], 200);
    }

    //force delete
    public function fdelete($id)
    {
        $ternak = Ternak::onlyTrashed()->where('necktag',$id);
        $ternak->forceDelete();

        return response()->json([
            'status' => 'success',
            'message' => "Data ternak id ". $id ." telah berhasil dihapus permanen.",
        ], 200);
    }

    public function fdeleteAll()
    {
        $ternak = Ternak::onlyTrashed();
        $ternak->forceDelete();

        return response()->json([
            'status' => 'success',
            'message' => "Data ternak pada tong sampah telah berhasil dihapus permanen.",
        ], 200);
    }
}
