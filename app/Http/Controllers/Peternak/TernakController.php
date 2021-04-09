<?php

namespace App\Http\Controllers\Peternak;

use App\Ternak;
use App\Perkawinan;
use App\DataTables\TernakDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Validator;

class TernakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TernakDataTable $dataTable)
    {
        $title = 'TERNAK';
        $page = 'Ternak';
        $pemilik = DB::table('pemiliks')->orderBy('nama_pemilik', 'asc')->get();
        $peternakan = DB::table('peternakans')->orderBy('nama_peternakan', 'asc')->get();
        $ras = DB::table('ras')->orderBy('jenis_ras', 'asc')->get();
        $kematian = DB::table('kematians')->orderBy('id', 'asc')->get();
        $datas = Ternak::join('ras', 'ras.id', '=', 'ternaks.ras_id')->get();

        return $dataTable->render('data.ternak', [
            'title' => $title, 
            'page' => $page, 
            'data' => $datas, 
            'kematian' => $kematian, 
            'ras' => $ras, 
            'pemilik' => $pemilik,
            'peternakan' => $peternakan
        ]);
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
            'ras_id' => 'required',
            'jenis_kelamin' => 'required',
            'blood' => 'required',
            'tgl_lahir' => 'required',
            'status_ada' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $necktag = Str::random(6);
        while(Ternak::where('necktag', $necktag)->exists()) {
            $necktag = Str::random(6);
        }

        $form_data = array(
            'necktag' => $necktag,
            'pemilik_id' => $request->pemilik_id,
            'ras_id' => $request->ras_id,
            'kematian_id' => $request->kematian_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tgl_lahir' => $request->tgl_lahir,
            'bobot_lahir' => $request->bobot_lahir,
            'pukul_lahir' => $request->pukul_lahir,
            'lama_dikandungan' => $request->lama_dikandungan,
            'lama_laktasi' => $request->lama_laktasi,
            'tgl_lepas_sapih' => $request->tgl_lepas_sapih,
            'blood' => $request->blood,
            'necktag_ayah' => $request->necktag_ayah,
            'necktag_ibu' => $request->necktag_ibu,
            'bobot_tubuh' => $request->bobot_tubuh,
            'panjang_tubuh' => $request->panjang_tubuh,
            'tinggi_tubuh' => $request->tinggi_tubuh,
            'cacat_fisik' => $request->cacat_fisik,
            'ciri_lain' => $request->ciri_lain,
            'status_ada' => $request->status_ada,
            'peternakan_id' => $request->peternakan_id
        );

        Ternak::create($form_data);

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
            $data = Ternak::findOrFail($id);

            if($data->pemilik_id != null){
                $pid = DB::table('pemiliks')->where('id', $data->pemilik_id)->first();
                $data->pemilik_id = $pid->nama_pemilik;
            }
            if($data->ras_id != null){
                $rid = DB::table('ras')->where('id', $data->ras_id)->first();
                $data->ras_id = $rid->jenis_ras;
            }
            if($data->peternakan_id != null){
                $ptid = DB::table('peternakans')->where('id', $data->peternakan_id)->first();
                $data->peternakan_id = $ptid->nama_peternakan;
            }
            if($data->kematian_id != null){
                $kid = DB::table('kematians')->where('id', $data->kematian_id)->first();
                $data->kematian_id = $kid->tgl_kematian.' - '.$kid->waktu_kematian;
            }

            if($data->status_ada == true){
                $data->status_ada = 'Ada';
            }else{
                $data->status_ada = 'Tidak Ada';
            }

            $rp = DB::select('SELECT public."rp_ternak"(?)', [$data->necktag]);

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
            $data = Ternak::findOrFail($id);

            if($data->status_ada){
                $data->status_ada = 'true';
            }else{
                $data->status_ada = 'false';
            }

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
            'ras_id' => 'required',
            'jenis_kelamin' => 'required',
            'blood' => 'required',
            'tgl_lahir' => 'required',
            'status_ada' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if($request->necktag_ayah == $id || $request->necktag_ibu == $id){
            $err = 'Individu tidak bisa menjadi orangtua untuk dirinya sendiri';
            return response()->json(['error' => $err]);
        }

        $form_data = array(
            'necktag' => $request->necktag,
            'pemilik_id' => $request->pemilik_id,
            'ras_id' => $request->ras_id,
            'kematian_id' => $request->kematian_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tgl_lahir' => $request->tgl_lahir,
            'bobot_lahir' => $request->bobot_lahir,
            'pukul_lahir' => $request->pukul_lahir,
            'lama_dikandungan' => $request->lama_dikandungan,
            'lama_laktasi' => $request->lama_laktasi,
            'tgl_lepas_sapih' => $request->tgl_lepas_sapih,
            'blood' => $request->blood,
            'necktag_ayah' => $request->necktag_ayah,
            'necktag_ibu' => $request->necktag_ibu,
            'bobot_tubuh' => $request->bobot_tubuh,
            'panjang_tubuh' => $request->panjang_tubuh,
            'tinggi_tubuh' => $request->tinggi_tubuh,
            'cacat_fisik' => $request->cacat_fisik,
            'ciri_lain' => $request->ciri_lain,
            'status_ada' => $request->status_ada,
            'peternakan_id' => $request->peternakan_id
        );

        Ternak::where('necktag',$id)->update($form_data);

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
        $data = Ternak::findOrFail($id);

        if(Perkawinan::where('necktag', $id)->exists()){
            $err = 'Data ternak id '. $id .' tidak dapat dihapus.';
            return response()->json(['error' => $err]);
        }
        else{
            $data->delete();
        }
    }


    // trash
    public function trash()
    {
        $ternak = Ternak::onlyTrashed()->get();

        return Datatables::of($ternak)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<button type="button" name="restore" id="'.$row->necktag.'" class="restore btn btn-warning btn-sm" ><i class="material-icons">restore</i></button>';
                    $btn .= '<button type="button" name="delete" id="'.$row->necktag.'" class="fdelete btn btn-danger btn-sm" ><i class="material-icons">delete_forever</i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    //restore
    public function restore($id)
    {
        $ternak = Ternak::onlyTrashed()->where('necktag',$id);
        $ternak->restore();
    }

    public function restoreAll()
    {
        $ternak = Ternak::onlyTrashed();
        $ternak->restore();
    }

    //force delete
    public function fdelete($id)
    {
        $ternak = Ternak::onlyTrashed()->where('necktag',$id);
        $ternak->forceDelete();
    }

    public function fdeleteAll()
    {
        $ternak = Ternak::onlyTrashed();
        $ternak->forceDelete();
    }
}
