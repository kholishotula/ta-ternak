<?php

namespace App\Http\Controllers\API;

use App\Ternak;
use App\Perkawinan;
use App\RiwayatPenyakit;
use App\Kematian;
use App\Perkembangan;
use App\Penjualan;
use App\User;
use Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TernakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role == 'admin'){
            $ternak = Ternak::orderBy("created_at")->get();
        }
        else{
            $ternak = Ternak::where('user_id', Auth::id())
                        ->orderBy("created_at")->get();
        }
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
            'ras_id' => 'required',
            'pemilik_id' => 'required',
            'peternak_id' => 'required',
            'jenis_kelamin' => 'required',
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
            'ras_id' => $request->ras_id,
            'pemilik_id' => $request->pemilik_id,
            'user_id' => $request->peternak_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tgl_lahir' => $request->tgl_lahir,
            'bobot_lahir' => $request->bobot_lahir,
            'pukul_lahir' => $request->pukul_lahir,
            'lama_dikandungan' => $request->lama_dikandungan,
            'lama_laktasi' => $request->lama_laktasi,
            'tgl_lepas_sapih' => $request->tgl_lepas_sapih,
            'necktag_ayah' => $request->necktag_ayah,
            'necktag_ibu' => $request->necktag_ibu,
            'cacat_fisik' => $request->cacat_fisik,
            'ciri_lain' => $request->ciri_lain,
            'status_ada' => $request->status_ada,
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
            'ras_id' => 'required',
            'pemilik_id' => 'required',
            'peternak_id' => 'required',
            'jenis_kelamin' => 'required',
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
            'ras_id' => $request->ras_id,
            'user_id' => $request->peternak_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tgl_lahir' => $request->tgl_lahir,
            'bobot_lahir' => $request->bobot_lahir,
            'pukul_lahir' => $request->pukul_lahir,
            'lama_dikandungan' => $request->lama_dikandungan,
            'lama_laktasi' => $request->lama_laktasi,
            'tgl_lepas_sapih' => $request->tgl_lepas_sapih,
            'necktag_ayah' => $request->necktag_ayah,
            'necktag_ibu' => $request->necktag_ibu,
            'cacat_fisik' => $request->cacat_fisik,
            'ciri_lain' => $request->ciri_lain,
            'status_ada' => $request->status_ada,
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

        if(Perkawinan::where('necktag', $id)->exists() ||
           RiwayatPenyakit::where('necktag', $id)->exists() ||
           Kematian::where('necktag', $id)->exists() ||
           Perkembangan::where('necktag', $id)->exists() ||
           Penjualan::where('necktag', $id)->exists() ){
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
        if(Auth::user()->role == 'admin'){
            $ternak = Ternak::onlyTrashed()
                        ->orderBy("deleted_at")->get();
        }
        else{
            $ternak = Ternak::onlyTrashed()
                        ->where('user_id', Auth::id())
                        ->orderBy("deleted_at")->get();
        }
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
        if(Auth::user()->role == 'admin'){
            $ternak = Ternak::onlyTrashed();
            $ternak->restore();
        }
        else{
            $ternak = Ternak::onlyTrashed()->where('user_id', Auth::id());
            $ternak->restore();
        }

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
        if(Auth::user()->role == 'admin'){
            $ternak = Ternak::onlyTrashed();
            $ternak->forceDelete();
        }
        else{
            $ternak = Ternak::onlyTrashed()->where('user_id', Auth::id());
            $ternak->forceDelete();
        }

        return response()->json([
            'status' => 'success',
            'message' => "Data ternak pada tong sampah telah berhasil dihapus permanen.",
        ], 200);
    }
}
