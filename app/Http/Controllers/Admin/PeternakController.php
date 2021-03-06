<?php

namespace App\Http\Controllers\Admin;

use App\GrupPeternak;
use App\Ternak;
use App\User;
use App\Log;
use Carbon\Carbon;
use App\DataTables\PeternakDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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
            'grup_peternak' => 'required',
            'name' => 'required',
            'role' => 'required',
            'ktp_user' => 'required|max:16',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }
        
        if($request->role == 'ketua grup'){
            $ketua_grup = User::where('grup_id', $request->grup_peternak)
                            ->where('role', 'ketua grup')
                            ->first();
            if($ketua_grup != null){
                return response()->json(['errors' => ['Ketua grup untuk Grup Peternak ID '.$request->grup_peternak.' sudah ada.']]);
            }
        }

        $password = Str::random(8);

        $form_data = array(
            'name' => $request->name,
            'ktp_user' => $request->ktp_user,
            'username' => $request->username,
            'grup_id' => $request->grup_peternak,
            'email' => $request->email,
            'password_first' => $password,
            'password' => Hash::make($password),
            'role' => $request->role,
        );

        $user = User::create($form_data);

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'insert',
            'tabel' => 'users',
            'pk_tabel' => $user->id,
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
            'name' => 'required',
            'role' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if($request->role == 'ketua grup'){
            $ketua_grup = User::where('grup_id', $request->grup_peternak)
                            ->where('role', 'ketua grup')
                            ->first();
            if($ketua_grup != null){
                return response()->json(['errors' => ['Ketua grup untuk Grup Peternak ID '.$request->grup_peternak.' sudah ada.']]);
            }
        }

        $form_data = array(
            'grup_id' => $request->grup_peternak,
            'name' => $request->name,
            'role' => $request->role,
        );

        $user = User::whereId($id)->update($form_data);

        Log::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'update',
            'tabel' => 'users',
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
        $data = User::findOrFail($id);

        if(Ternak::where('user_id', $id)->exists()){
            $err = 'Data peternak id '. $id .' tidak dapat dihapus.';
            return response()->json(['error' => $err]);
        }
        else{
            $data->delete();
            Log::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'delete',
                'tabel' => 'users',
                'pk_tabel' => $id,
                'waktu' => Carbon::now()
            ]);
        }
    }
}
