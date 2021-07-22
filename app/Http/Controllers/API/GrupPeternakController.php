<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\GrupPeternak;
use App\User;
use App\Log;
use Validator;
use Carbon\Carbon;

class GrupPeternakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grup = GrupPeternak::orderBy("id")->get();

        return response()->json([
            'status' => 'success',
            'grup' => $grup,
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
            'nama_grup' => 'required',
            'alamat' => 'required',
            'provinsi' => 'required',
            'kab_kota' => 'required',
            'kecamatan' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'nama_grup' => $request->nama_grup,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kab_kota' => $request->kab_kota,
            'kecamatan' => $request->kecamatan,
            'keterangan' => $request->keterangan,
        );

        $grup = GrupPeternak::create($form_data);

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'insert',
            'tabel' => 'grup_peternaks',
            'pk_tabel' => $grup->id,
            'waktu' => Carbon::now()
        ]);

        return response()->json([
            'status' => 'success',
            'grup' => $grup,
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
        $grup = GrupPeternak::find($id);
        
        return response()->json([
            'status' => 'success',
            'grup' => $grup,
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
            'nama_grup' => 'required',
            'alamat' => 'required',
            'provinsi' => 'required',
            'kab_kota' => 'required',
            'kecamatan' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'nama_grup' => $request->nama_grup,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kab_kota' => $request->kab_kota,
            'kecamatan' => $request->kecamatan,
            'keterangan' => $request->keterangan,
        );

        GrupPeternak::find($id)->update($form_data);
        $grup = GrupPeternak::find($id);

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'update',
            'tabel' => 'grup_peternaks',
            'pk_tabel' => $id,
            'waktu' => Carbon::now()
        ]);
        
        return response()->json([
            'status' => 'success',
            'grup' => $grup,
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
        $data = GrupPeternak::find($id);

        if(User::where('grup_id', $id)->exists()){
            return response()->json([
                'status' => 'error',
                'message' => "Data grup peternak id ". $id ." tidak dapat dihapus.",
            ], 200);
        }
        else{
            $data->delete();
            Log::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'delete',
                'tabel' => 'grup_peternaks',
                'pk_tabel' => $id,
                'waktu' => Carbon::now()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => "Data grup peternak id ". $id ." telah berhasil dihapus.",
        ], 200);
    }
}
