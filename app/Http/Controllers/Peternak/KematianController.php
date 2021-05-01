<?php

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Kematian;
use App\Ternak;
use App\DataTables\KematianDataTable;
use Yajra\Datatables\Datatables;
use Validator;

class KematianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(KematianDataTable $dataTable)
    {
        $title = 'TERNAK MATI';
        $page = 'Ternak Mati';
        $ternaks = Ternak::all();

        return $dataTable->render('data.kematian', ['title' => $title, 'page' => $page, 'ternaks' => $ternaks]);
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
            'necktag' => 'required',
            'tgl_kematian' => 'required',
            'waktu_kematian' => 'required',
            'penyebab' => 'required',
            'kondisi' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'necktag' => $request->necktag,
            'tgl_kematian' => $request->tgl_kematian,
            'waktu_kematian' => $request->waktu_kematian,
            'penyebab' => $request->penyebab,
            'kondisi' => $request->kondisi
        );

        Kematian::create($form_data);

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
        //
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
            $data = Kematian::findOrFail($id);
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
            'tgl_kematian' => 'required',
            'waktu_kematian' => 'required',
            'penyebab' => 'required',
            'kondisi' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'tgl_kematian' => $request->tgl_kematian,
            'waktu_kematian' => $request->waktu_kematian,
            'penyebab' => $request->penyebab,
            'kondisi' => $request->kondisi
        );

        Kematian::whereId($id)->update($form_data);

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
        $data = Kematian::findOrFail($id);
        
        if(Ternak::where('kematian_id', $id)->exists()){
            $err = 'Data kematian id '. $id .' tidak dapat dihapus.';
            return response()->json(['error' => $err]);
        }
        else{
            $data->delete();
        }
    }
}
