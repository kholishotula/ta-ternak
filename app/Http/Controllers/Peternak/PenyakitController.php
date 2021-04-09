<?php

namespace App\Http\Controllers\Peternak;

use App\Penyakit;
use App\DataTables\PenyakitDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;

class PenyakitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PenyakitDataTable $dataTable)
    {
        $title = 'PENYAKIT';
        $page = 'Penyakit';

        return $dataTable->render('data.penyakit', ['title' => $title, 'page' => $page]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'ket_penyakit' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'nama_penyakit' => $request->nama_penyakit,
            'ket_penyakit' => $request->ket_penyakit
        );

        Penyakit::create($form_data);

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
            $data = Penyakit::findOrFail($id);

            $rp = DB::select('SELECT public."rp_penyakit"(?)', [$data->id]);

            return response()->json(['result' => $data, 'riwayat' => $rp]);
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
            $data = Penyakit::findOrFail($id);
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
            'nama_penyakit' => 'required',
            'ket_penyakit' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'nama_penyakit' => $request->nama_penyakit,
            'ket_penyakit' => $request->ket_penyakit
        );

        Penyakit::whereId($id)->update($form_data);

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
        $data = Penyakit::findOrFail($id);

        $exists= DB::table('public.riwayat_penyakits')
                            ->where('penyakit_id', '=', $id)
                            ->first();

        if(is_null($exists)){
            $data->delete();
        }
        else{
            $err = 'Data penyakit id '. $id .' tidak dapat dihapus.';
            return response()->json(['error' => $err]);
        }
    }
}
