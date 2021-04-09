<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
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
        $peternak = User::where('role', '=', 'peternak')->orderBy("id")->get();

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
            'peternakan_id' => 'required',
            'name' => 'required',
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

        $password = Str::random(8);

        $form_data = array(
            'name' => $request->name,
            'username' => $request->username,
            'peternakan_id' => $request->peternakan_id,
            'email' => $request->email,
            'password_first' => $password,
            'password' => Hash::make($password),
            'register_from_admin' => true,
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
            'peternakan_id' => 'required',
            'name' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $form_data = array(
            'peternakan_id' => $request->peternakan_id,
            'name' => $request->name
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
        $data->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Data peternak id ". $id ." telah berhasil dihapus.",
        ], 200);
    }
}
