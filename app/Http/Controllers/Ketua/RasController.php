<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Ras;
use App\Ternak;
use App\DataTables\RasDataTable;
use Validator;

class RasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RasDataTable $dataTable)
    {
        $title = 'RAS';
        $page = 'Ras';

        return $dataTable->render('data.ras', ['title' => $title, 'page' => $page]);
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
            'jenis_ras' => 'required',
            'ket_ras' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'jenis_ras' => $request->jenis_ras,
            'ket_ras' => $request->ket_ras
        );

        Ras::create($form_data);

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
            $data = Ras::findOrFail($id);
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
            'jenis_ras' => 'required',
            'ket_ras' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'jenis_ras' => $request->jenis_ras,
            'ket_ras' => $request->ket_ras
        );

        Ras::whereId($id)->update($form_data);

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
        $data = Ras::findOrFail($id);

        if(Ternak::where('ras_id', $id)->exists()){
            $err = 'Data ras id '. $id .' tidak dapat dihapus.';
            return response()->json(['error' => $err]);
        }
        else{
            $data->delete();
        }
    }
}
