<?php

namespace App\Http\Controllers\Admin;

use App\GrupPeternak;
use App\DataTables\GrupPeternakDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Validator;

class GrupPeternakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GrupPeternakDataTable $dataTable)
    {
        $title = 'GRUP PETERNAK';
        $page = 'Grup Peternak';
        $provinsi = DB::table('wilayahs')->whereRaw('LENGTH(kode) = 2')->orderBy('nama')->get();

        return $dataTable->render('data.grup-peternak', ['title' => $title, 'page' => $page, 'provinsi' => $provinsi]);
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
            'nama_grup' => 'required',
            'alamat' => 'required',
            'provinsi' => 'required',
            'kab_kota' => 'required',
            'kecamatan' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $provinsi = DB::table('wilayahs')->select('nama')->where('kode', '=', $request->provinsi)->get();
        $kab_kota = DB::table('wilayahs')->select('nama')->where('kode', '=', $request->kab_kota)->get();
        $kecamatan = DB::table('wilayahs')->select('nama')->where('kode', '=', $request->kecamatan)->get();

        $form_data = array(
            'nama_grup' => $request->nama_grup,
            'alamat' => $request->alamat,
            'provinsi' => preg_split('/["]/', $provinsi)[3],
            'kab_kota' => preg_split('/["]/', $kab_kota)[3],
            'kecamatan' => preg_split('/["]/', $kecamatan)[3],
            'keterangan' => $request->keterangan,
        );

        GrupPeternak::create($form_data);

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
            $data = GrupPeternak::findOrFail($id);
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
            $data = GrupPeternak::findOrFail($id);
            $provinsi = DB::table('wilayahs')->whereRaw('LENGTH(kode) = 2')->orderBy('nama')->get();
            return response()->json(['result' => $data, 'provinsi' => $provinsi]);
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
            'nama_grup' => 'required',
            'alamat' => 'required',
            'provinsi' => 'required',
            'kab_kota' => 'required',
            'kecamatan' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $provinsi = DB::table('wilayahs')->select('nama')->where('kode', '=', $request->provinsi)->get();
        $kab_kota = DB::table('wilayahs')->select('nama')->where('kode', '=', $request->kab_kota)->get();
        $kecamatan = DB::table('wilayahs')->select('nama')->where('kode', '=', $request->kecamatan)->get();

        $form_data = array(
            'nama_grup' => $request->nama_grup,
            'alamat' => $request->alamat,
            'provinsi' => preg_split('/["]/', $provinsi)[3],
            'kab_kota' => preg_split('/["]/', $kab_kota)[3],
            'kecamatan' => preg_split('/["]/', $kecamatan)[3],
            'keterangan' => $request->keterangan,
        );

        GrupPeternak::whereId($id)->update($form_data);

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
        $data = GrupPeternak::findOrFail($id);
        $data->delete();
    }
}
