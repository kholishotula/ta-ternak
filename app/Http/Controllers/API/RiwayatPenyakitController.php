<?php

namespace App\Http\Controllers\API;

use App\Ternak;
use App\RiwayatPenyakit;
use App\Log;
use Carbon\Carbon;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPenyakitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role == 'admin'){
            $riwayat = RiwayatPenyakit::orderBy('created_at')->get();
        }
        else{
            $necktag_ternaks = Ternak::where('user_id', Auth::id())
                                ->pluck('necktag')->toArray();
            $riwayat = RiwayatPenyakit::whereIn('necktag', $necktag_ternaks)
                        ->orderBy('created_at')->get();
        }

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
            'nama_penyakit' => 'required',
            'necktag' => 'required',
            'tgl_sakit' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'errors' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'necktag' => $request->necktag,
            'nama_penyakit' => $request->nama_penyakit,
            'tgl_sakit' => $request->tgl_sakit,
            'obat' => $request->obat,
            'lama_sakit' => $request->lama_sakit,
            'keterangan' => $request->keterangan,
        );

        $riwayat = RiwayatPenyakit::create($form_data);

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'insert',
            'tabel' => 'riwayat_penyakits',
            'pk_tabel' => $riwayat->id,
            'waktu' => Carbon::now()
        ]);

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
        $riwayat = RiwayatPenyakit::find($id);
        
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
            'nama_penyakit' => 'required',
            'necktag' => 'required',
            'tgl_sakit' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'errors' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'nama_penyakit' => $request->nama_penyakit,
            'tgl_sakit' => $request->tgl_sakit,
            'obat' => $request->obat,
            'lama_sakit' => $request->lama_sakit,
            'keterangan' => $request->keterangan,
            'updated_at' => Carbon::now()
        );

        RiwayatPenyakit::whereId($id)->update($form_data);
        $riwayat = RiwayatPenyakit::find($id);

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'update',
            'tabel' => 'riwayat_penyakits',
            'pk_tabel' => $id,
            'waktu' => Carbon::now()
        ]);
        
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
        $data = RiwayatPenyakit::findOrFail($id);
        $data->delete();

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'delete',
            'tabel' => 'riwayat_penyakits',
            'pk_tabel' => $id,
            'waktu' => Carbon::now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Data riwayat penyakit id ". $id ." telah berhasil dihapus.",
        ], 200);
    }
}
