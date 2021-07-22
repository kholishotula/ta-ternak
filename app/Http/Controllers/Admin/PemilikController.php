<?php

namespace App\Http\Controllers\Admin;

use App\Pemilik;
use App\Ternak;
use App\Log;
use App\DataTables\PemilikDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use Validator;
use Carbon\Carbon;

class PemilikController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PemilikDataTable $dataTable)
    {
        $title = 'PEMILIK';
        $page = 'Pemilik';

        return $dataTable->render('data.pemilik', ['title' => $title, 'page' => $page]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'nama_pemilik' => 'required',
            'ktp_pemilik' => 'required|digits:16|unique:pemiliks'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'nama_pemilik' => $request->nama_pemilik,
            'ktp_pemilik' => $request->ktp_pemilik
        );

        $pemilik = Pemilik::create($form_data);
        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'insert',
            'tabel' => 'pemiliks',
            'pk_tabel' => $pemilik->id,
            'waktu' => Carbon::now()
        ]);

        return response()->json(['success' => 'Data telah berhasil ditambahkan.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(request()->ajax()){
            $data = Pemilik::findOrFail($id);
            $ternak = Ternak::where('pemilik_id', '=', $data->id)->get();

            return response()->json(['result' => $data, 'ternak' => $ternak]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(request()->ajax()){
            $data = Pemilik::findOrFail($id);
            return response()->json(['result' => $data]);
        }
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
            'nama_pemilik' => 'required',
            'ktp_pemilik' => 'required|digits:16'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'nama_pemilik' => $request->nama_pemilik,
            'ktp_pemilik' => $request->ktp_pemilik
        );

        Pemilik::whereId($id)->update($form_data);
        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'update',
            'tabel' => 'pemiliks',
            'pk_tabel' => $id,
            'waktu' => Carbon::now()
        ]);

        return response()->json(['success' => 'Data telah berhasil diubah.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Pemilik::findOrFail($id);

        if(Ternak::where('pemilik_id', $id)->exists()){
            $err = 'Data pemilik id '. $id .' tidak dapat dihapus.';
            return response()->json(['error' => $err]);
        }
        else{
            $data->delete();
            Log::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'delete',
                'tabel' => 'pemiliks',
                'pk_tabel' => $id,
                'waktu' => Carbon::now()
            ]);
        }
    }
}
