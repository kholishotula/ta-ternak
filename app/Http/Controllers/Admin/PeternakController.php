<?php

namespace App\Http\Controllers\Admin;

use App\GrupPeternak;
use App\Ternak;
use App\User;
use App\DataTables\PeternakDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Validator;

class PeternakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PeternakDataTable $dataTable)
    {
        $title = 'PETERNAK';
        $page = 'Peternak';
        $grupPeternak = GrupPeternak::orderBy('nama_grup', 'asc')->get();

        return $dataTable->render('data.peternak', [
            'title' => $title, 
            'page' => $page,
            'grupPeternak' => $grupPeternak
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
            'grup_peternak_id' => 'required',
            'name' => 'required',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }
        
        $password = Str::random(8);

        $form_data = array(
            'name' => $request->name,
            'username' => $request->username,
            'grup_peternak_id' => $request->grup_peternak_id,
            'email' => $request->email,
            'password_first' => $password,
            'password' => Hash::make($password),
            'register_from_admin' => true,
        );

        User::create($form_data);

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
            $data = User::findOrFail($id);
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
            $data = User::findOrFail($id);
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
            'grup_peternak' => 'required',
            'verify' => 'required',
            'ketua_grup' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'grup_id' => $request->grup_peternak,
            'verify' => $request->verify,
            'ketua_grup' => $request->ketua_grup
        );

        $user = User::whereId($id)->update($form_data);

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
        $data = User::findOrFail($id);

        if(Ternak::where('user_id', $id)->exists()){
            $err = 'Data grup peternak id '. $id .' tidak dapat dihapus.';
            return response()->json(['error' => $err]);
        }
        else{
            $data->delete();
        }
    }
}
