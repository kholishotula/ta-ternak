<?php

namespace App\Http\Controllers\Ketua;

use App\Ternak;
use App\Penjualan;
use App\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\DataTables\PenjualanDataTable;
use Yajra\Datatables\Datatables;
use Validator;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PenjualanDataTable $dataTable)
    {
        $title = 'TERNAK TERJUAL';
        $page = 'Ternak Terjual';
        $ternaks = Ternak::where('user_id', Auth::id())
                        ->where('kematian_id', null)->get();

        return $dataTable->with('peternak_id', Auth::id())->render('data.penjualan', [
            'title' => $title,
            'page' => $page, 
            'ternaks' => $ternaks
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
        if(Penjualan::where('necktag', $request->necktag)->exists()){
            return response()->json(['errors' => ['Data penjualan untuk ternak '.$request->necktag.' sudah ada.']]);
        }

        $rules = array(
            'necktag' => 'required',
            'tgl_terjual' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'necktag' => $request->necktag,
            'tgl_terjual' => $request->tgl_terjual,
            'ket_pembeli' => $request->ket
        );

        $penjualan = Penjualan::create($form_data);

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'insert',
            'tabel' => 'penjualans',
            'pk_tabel' => $penjualan->id,
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
            $data = Penjualan::findOrFail($id);
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
        if(Penjualan::where('necktag', $request->necktag)
                    ->where('id', '<>', $id)
                    ->exists()){
            return response()->json(['errors' => ['Data penjualan untuk ternak '.$request->necktag.' sudah ada.']]);
        }
        $rules = array(
            'necktag' => 'required',
            'tgl_terjual' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'necktag' => $request->necktag,
            'tgl_terjual' => $request->tgl_terjual,
            'ket_pembeli' => $request->ket
        );

        Penjualan::whereId($id)->update($form_data);

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'update',
            'tabel' => 'penjualans',
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
        $data = Penjualan::findOrFail($id);
        $data->delete();

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'delete',
            'tabel' => 'penjualans',
            'pk_tabel' => $id,
            'waktu' => Carbon::now()
        ]);
    }
}
