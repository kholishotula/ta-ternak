<?php

namespace App\Http\Controllers\Admin;

use App\GrupPeternak;
use App\User;
use App\Log;
use App\DataTables\GrupPeternakDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use GuzzleHttp\Client;
use Validator;
use Carbon\Carbon;

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

        $client = new Client();
        $response = $client->get('https://kholishotula.github.io/api-wilayah-indonesia/api/provinces.json');
        $prov = json_decode($response->getBody(), true);

        return $dataTable->render('data.grup-peternak', ['title' => $title, 'page' => $page, 'provinsi' => $prov]);
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

        $provinsi = explode('-', $request->provinsi)[1];
        $kab_kota = explode('-', $request->kab_kota)[1];
        $kecamatan = explode('-', $request->kecamatan)[1];

        $form_data = array(
            'nama_grup' => $request->nama_grup,
            'alamat' => $request->alamat,
            'provinsi' => $provinsi,
            'kab_kota' => $kab_kota,
            'kecamatan' => $kecamatan,
            'keterangan' => $request->keterangan,
        );

        $grup = GrupPeternak::create($form_data);
        
        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'insert',
            'tabel' => 'grup_peternaks',
            'pk_tabel' => $grup->id,
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
    //     
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
            $data = GrupPeternak::findOrFail($id);

            $client = new Client();
            $response = $client->get('https://kholishotula.github.io/api-wilayah-indonesia/api/provinces.json');
            $prov = json_decode($response->getBody(), true);

            return response()->json(['result' => $data, 'provinsi' => $prov]);
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

        $provinsi = explode('-', $request->provinsi)[1];
        $kab_kota = explode('-', $request->kab_kota)[1];
        $kecamatan = explode('-', $request->kecamatan)[1];

        $form_data = array(
            'nama_grup' => $request->nama_grup,
            'alamat' => $request->alamat,
            'provinsi' => $provinsi,
            'kab_kota' => $kab_kota,
            'kecamatan' => $kecamatan,
            'keterangan' => $request->keterangan,
        );

        GrupPeternak::whereId($id)->update($form_data);

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'update',
            'tabel' => 'grup_peternaks',
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
        $data = GrupPeternak::findOrFail($id);
        if(User::where('grup_id', $id)->exists()){
            $err = 'Data grup peternak id '. $id .' tidak dapat dihapus.';
            return response()->json(['error' => $err]);
        }
        $data->delete();

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'delete',
            'tabel' => 'grup_peternaks',
            'pk_tabel' => $id,
            'waktu' => Carbon::now()
        ]);
    }
}
