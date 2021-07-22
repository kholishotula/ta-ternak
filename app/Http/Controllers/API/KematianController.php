<?php

namespace App\Http\Controllers\API;

use App\Kematian;
use App\Ternak;
use App\Log;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KematianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role == 'admin'){
            $kematian = Kematian::orderBy("id")->get();
        }
        else{
            $necktag_ternaks = Ternak::where('user_id', Auth::id())
                                ->pluck('necktag')->toArray();
            $kematian = Kematian::whereIn('necktag', $necktag_ternaks)
                            ->orderBy("id")->get();
        }

        return response()->json([
            'status' => 'success',
            'kematian' => $kematian,
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
        if(Kematian::where('necktag', $request->necktag)->exists()){
            return response()->json([
                'status' => 'error',
                'errors' => ['Data kematian untuk ternak '.$request->necktag.' sudah ada.']
            ]);
        }
        
        $rules = array(
            'necktag' => 'required',
            'tgl_kematian' => 'required',
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
            'tgl_kematian' => $request->tgl_kematian,
            'waktu_kematian' => $request->waktu_kematian,
            'penyebab' => $request->penyebab,
            'kondisi' => $request->kondisi
        );

        $kematian = Kematian::create($form_data);

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'insert',
            'tabel' => 'kematians',
            'pk_tabel' => $kematian->id,
            'waktu' => Carbon::now()
        ]);

        return response()->json([
            'status' => 'success',
            'kematian' => $kematian,
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
        $kematian = Kematian::find($id);
        
        return response()->json([
            'status' => 'success',
            'kematian' => $kematian,
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
        if(Kematian::where('necktag', $request->necktag)
                    ->where('id', '<>', $id)
                    ->exists()){
            return response()->json([
                'status' => 'error',
                'errors' => ['Data kematian untuk ternak '.$request->necktag.' sudah ada.']
            ]);
        }

        $rules = array(
            'necktag' => 'required',
            'tgl_kematian' => 'required'
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
            'tgl_kematian' => $request->tgl_kematian,
            'waktu_kematian' => $request->waktu_kematian,
            'penyebab' => $request->penyebab,
            'kondisi' => $request->kondisi
        );

        Kematian::whereId($id)->update($form_data);
        $kematian = Kematian::find($id);

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'update',
            'tabel' => 'kematians',
            'pk_tabel' => $id,
            'waktu' => Carbon::now()
        ]);
        
        return response()->json([
            'status' => 'success',
            'kematian' => $kematian,
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
        $data = Kematian::find($id);
        $data->delete();

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'delete',
            'tabel' => 'kematians',
            'pk_tabel' => $id,
            'waktu' => Carbon::now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Data kematian id ". $id ." telah berhasil dihapus.",
        ], 200);
    }
}
