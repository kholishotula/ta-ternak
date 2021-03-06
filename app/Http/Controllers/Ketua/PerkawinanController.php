<?php

namespace App\Http\Controllers\Ketua;

use App\Ternak;
use App\Perkawinan;
use App\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\DataTables\PerkawinanDataTable;
use Validator;
use Carbon\Carbon;

class PerkawinanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PerkawinanDataTable $dataTable)
    {
        $title = 'PERKAWINAN';
        $page = 'Perkawinan';
        $ternak = Ternak::join('ras', 'ras.id', '=', 'ternaks.ras_id')
                        ->where('user_id', Auth::id())
                        ->get();

        return $dataTable->with('peternak_id', Auth::id())->render('data.perkawinan', [
            'title' => $title,
            'page' => $page,
            'ternak' => $ternak]);
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
            'necktag' => 'required',
            'necktag_psg' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'necktag' => $request->necktag,
            'necktag_psg' => $request->necktag_psg,
            'tgl_kawin' => $request->tgl
        );

        $perkawinan = Perkawinan::create($form_data);

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'insert',
            'tabel' => 'perkawinans',
            'pk_tabel' => $perkawinan->id,
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
    // public function show($id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(request()->ajax()){
            $data = Perkawinan::findOrFail($id);
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
            'necktag' => 'required',
            'necktag_psg' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'necktag' => $request->necktag,
            'necktag_psg' => $request->necktag_psg,
            'tgl_kawin' => $request->tgl
        );

        Perkawinan::whereId($id)->update($form_data);

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'update',
            'tabel' => 'perkawinans',
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
        $data = Perkawinan::findOrFail($id);
        $data->delete();

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'delete',
            'tabel' => 'perkawinans',
            'pk_tabel' => $id,
            'waktu' => Carbon::now()
        ]);
    }

    // necktag pasangan - dependent dropdown
    public function getPasangan($id)
    {
        $tes = Ternak::find($id);

        $ternak = Ternak::join('ras', 'ras.id', '=', 'ternaks.ras_id')
                        ->where('necktag', '<>', $id)
                        ->where('jenis_kelamin', '<>', $tes->jenis_kelamin)
                        ->get();

        return response()->json(['ternak' => $ternak]);
    }
}
