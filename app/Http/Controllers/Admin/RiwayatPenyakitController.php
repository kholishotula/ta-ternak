<?php

namespace App\Http\Controllers\Admin;

use App\Ternak;
use App\RiwayatPenyakit;
use App\Log;
use Carbon\Carbon;
use App\DataTables\RiwayatDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use Validator;

class RiwayatPenyakitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RiwayatDataTable $dataTable)
    {
        $title = 'RIWAYAT PENYAKIT';
        $page = 'Riwayat Penyakit';
        $ternaks = Ternak::all();
        
        return $dataTable->render('data.riwayat', ['title' => $title, 'page' => $page, 'ternaks' => $ternaks]);
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
            'nama_penyakit' => 'required',
            'necktag' => 'required',
            'tgl_sakit' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
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
            $data = RiwayatPenyakit::find($id);
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
            'necktag' => 'required',
            'tgl_sakit' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
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
        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'update',
            'tabel' => 'riwayat_penyakits',
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
        $data = RiwayatPenyakit::findOrFail($id);
        $data->delete();
        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'delete',
            'tabel' => 'riwayat_penyakits',
            'pk_tabel' => $id,
            'waktu' => Carbon::now()
        ]);
    }
}
