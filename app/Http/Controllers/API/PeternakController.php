<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Ternak;
use App\User;
use Validator;

class PeternakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $peternak = User::where('role', '<>', 'admin')->orderBy("id")->get();

        return response()->json([
            'status' => 'success',
            'peternak' => $peternak,
        ], 200);
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
            'grup_peternak' => 'required',
            'name' => 'required',
            'role' => 'required',
            'ktp_user' => 'required|digits:16',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        if($request->role == 'ketua grup'){
            $ketua_grup = User::where('grup_id', $request->grup_peternak)
                            ->where('role', 'ketua grup')
                            ->first();
            if($ketua_grup != null){
                return response()->json([
                    'status' => 'error',
                    'error' => ['Ketua grup untuk Grup Peternak ID '.$request->grup_peternak.' sudah ada.']
                ]);
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

        $peternak = User::create($form_data);

        return response()->json([
            'status' => 'success',
            'peternak' => $peternak,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $peternak = User::find($id);
        
        return response()->json([
            'status' => 'success',
            'peternak' => $peternak,
        ], 200);
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
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        if($request->role == 'ketua grup'){
            $ketua_grup = User::where('grup_id', $request->grup_peternak)
                            ->where('role', 'ketua grup')
                            ->first();
            if($ketua_grup != null){
                return response()->json([
                    'status' => 'error',
                    'error' => ['Ketua grup untuk Grup Peternak ID '.$request->grup_peternak.' sudah ada.']
                ]);
            }
        }

        $form_data = array(
            'grup_id' => $request->grup_peternak,
            'name' => $request->name,
            'role' => $request->role,
        );

        $peternak = User::find($id);
        $peternak->update($form_data);

        return response()->json([
            'status' => 'success',
            'peternak' => $peternak,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = User::find($id);
        if(Ternak::where('user_id', $id)->exists()){
            $err = 'Data peternak id '. $id .' tidak dapat dihapus.';
            return response()->json([
                'status' => 'error',
                'error' => $err
            ]);
        }
        else{
            $data->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => "Data peternak id ". $id ." telah berhasil dihapus.",
        ], 200);
    }
}
