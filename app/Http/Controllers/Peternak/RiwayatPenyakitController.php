<?php

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\DataTables\RiwayatDataTable;
use App\Penyakit;
use App\Ternak;
use Carbon\Carbon;
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
        $ternak = Ternak::all();
        $penyakit = Penyakit::all();
        
        return $dataTable->render('data.riwayat', ['title' => $title, 'page' => $page, 'ternak' => $ternak, 'penyakit' => $penyakit]);
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
            'penyakit_id' => 'required',
            'necktag' => 'required',
            'tgl_sakit' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $s_penyakit = Penyakit::find($request->penyakit_id);

        $s_penyakit->ternak()->attach($request->necktag, [
            'tgl_sakit' => $request->tgl_sakit,
            'obat' => $request->obat,
            'lama_sakit' => $request->lama_sakit,
            'keterangan' => $request->keterangan 
        ]);

        // $form_data = array(
        //     'penyakit_id' => $request->penyakit_id,
        //     'necktag' => $request->necktag,
        //     'tgl_sakit' => $request->tgl_sakit,
        //     'obat' => $request->obat,
        //     'lama_sakit' => $request->lama_sakit,
        //     'keterangan' => $request->keterangan,
        // );

        // $riwayat = DB::table('riwayat_penyakits')->insert($form_data);

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
            $data = DB::table('riwayat_penyakits')->find($id);
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
            'penyakit_id' => 'required',
            'necktag' => 'required',
            'tgl_sakit' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'penyakit_id' => $request->penyakit_id,
            'necktag' => $request->necktag,
            'tgl_sakit' => $request->tgl_sakit,
            'obat' => $request->obat,
            'lama_sakit' => $request->lama_sakit,
            'keterangan' => $request->keterangan,
            'updated_at' => Carbon::now()
        );

        DB::table('riwayat_penyakits')->whereId($id)->update($form_data);

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
        $data = DB::table('riwayat_penyakits')->where('id', $id)->delete();
    }
}
