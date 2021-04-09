<?php

namespace App\Http\Controllers\Admin;

use App\Peternakan;
use App\User;
use App\Ternak;
use App\DataTables\PeternakanDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;

class PeternakanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PeternakanDataTable $dataTable)
    {
        $title = 'PETERNAKAN';
        $page = 'Peternakan';

        return $dataTable->render('data.peternakan', ['title' => $title, 'page' => $page]);
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
            'nama_peternakan' => 'required',
            'keterangan' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'nama_peternakan' => $request->nama_peternakan,
            'keterangan' => $request->keterangan
        );

        Peternakan::create($form_data);

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
            $data = Peternakan::findOrFail($id);
            return response()->json(['result' => $data]);
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
            $data = Peternakan::findOrFail($id);
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
            'nama_peternakan' => 'required',
            'keterangan' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'nama_peternakan' => $request->nama_peternakan,
            'keterangan' => $request->keterangan
        );

        Peternakan::whereId($id)->update($form_data);

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
        $data = Peternakan::findOrFail($id);

        if(Ternak::where('peternakan_id', $id)->exists() || User::where('peternakan_id', $id)->exists()){
            $err = 'Data peternakan id '. $id .' tidak dapat dihapus.';
            return response()->json(['error' => $err]);
        }
        else{
            $data->delete();
        }
    }
}
