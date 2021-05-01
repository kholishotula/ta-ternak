<?php

namespace App\Http\Controllers\Admin;

use App\Ternak;
use App\Pemilik;
use App\Ras;
use App\User;
use App\Perkawinan;
use App\RiwayatPenyakit;
use App\Kematian;
use App\Perkembangan;
use App\Penjualan;
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
        $pemilik = Pemilik::all();
        $ras = Ras::all();
        $peternak = User::where('role', '<>', 'admin')->get();
        $datas = Ternak::join('ras', 'ras.id', '=', 'ternaks.ras_id')->get();

        return $dataTable->render('data.ternak', [
            'title' => $title, 
            'page' => $page, 
            'data' => $datas, 
            'peternak' => $peternak, 
            'ras' => $ras, 
            'pemilik' => $pemilik,
        ]);
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
            'ras_id' => 'required',
            'pemilik_id' => 'required',
            'peternak_id' => 'required',
            'jenis_kelamin' => 'required',
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
            'ras_id' => $request->ras_id,
            'pemilik_id' => $request->pemilik_id,
            'user_id' => $request->peternak_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tgl_lahir' => $request->tgl_lahir,
            'bobot_lahir' => $request->bobot_lahir,
            'pukul_lahir' => $request->pukul_lahir,
            'lama_dikandungan' => $request->lama_dikandungan,
            'lama_laktasi' => $request->lama_laktasi,
            'tgl_lepas_sapih' => $request->tgl_lepas_sapih,
            'necktag_ayah' => $request->necktag_ayah,
            'necktag_ibu' => $request->necktag_ibu,
            'cacat_fisik' => $request->cacat_fisik,
            'ciri_lain' => $request->ciri_lain,
            'status_ada' => $request->status_ada,
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

            if($data->user_id != null){
                $pid = DB::table('users')->where('id', $data->user_id)->first();
                $data->user_id = $pid->name;
            }
            if($data->pemilik_id != null){
                $pid = DB::table('pemiliks')->where('id', $data->pemilik_id)->first();
                $data->pemilik_id = $pid->nama_pemilik;
            }
            if($data->ras_id != null){
                $rid = DB::table('ras')->where('id', $data->ras_id)->first();
                $data->ras_id = $rid->jenis_ras;
            }
            if($data->grup_id != null){
                $gid = DB::table('grup_ternaks')->where('id', $data->grup_id)->first();
                $data->grup_id = $gid->nama_grup;
            }
            if($data->kematian_id != null){
                $kid = DB::table('kematians')->where('id', $data->kematian_id)->first();
                $data->kematian_id = $kid->tgl_kematian.' - '.$kid->waktu_kematian;
            }
            if($data->penjualan_id != null){
                $jid = DB::table('penjualans')->where('id', $data->penjualan_id)->first();
                $data->penjualan_id = $jid->tgl_terjual.' - dibeli oleh '.$jid->ket_pembeli;
            }

            if($data->status_ada == true){
                $data->status_ada = 'Ada';
            }else{
                $data->status_ada = 'Tidak Ada';
            }

            // $rp = DB::select('SELECT public."rp_ternak"(?)', [$data->necktag]);
            if(RiwayatPenyakit::where('necktag', $id)->exists()){
                $rp = RiwayatPenyakit::where('necktag', $id)->get();
            }
            else{
                $rp = null;
            }

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
            'pemilik_id' => 'required',
            'peternak_id' => 'required',
            'jenis_kelamin' => 'required',
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
            'necktag' => $id,
            'pemilik_id' => $request->pemilik_id,
            'ras_id' => $request->ras_id,
            'user_id' => $request->peternak_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tgl_lahir' => $request->tgl_lahir,
            'bobot_lahir' => $request->bobot_lahir,
            'pukul_lahir' => $request->pukul_lahir,
            'lama_dikandungan' => $request->lama_dikandungan,
            'lama_laktasi' => $request->lama_laktasi,
            'tgl_lepas_sapih' => $request->tgl_lepas_sapih,
            'necktag_ayah' => $request->necktag_ayah,
            'necktag_ibu' => $request->necktag_ibu,
            'cacat_fisik' => $request->cacat_fisik,
            'ciri_lain' => $request->ciri_lain,
            'status_ada' => $request->status_ada,
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

        if(Perkawinan::where('necktag', $id)->exists() ||
           RiwayatPenyakit::where('necktag', $id)->exists() ||
           Kematian::where('necktag', $id)->exists() ||
           Perkembangan::where('necktag', $id)->exists() ||
           Penjualan::where('necktag', $id)->exists() ){
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
