<?php

namespace App\Http\Controllers\API;

use App\Ternak;
use App\Perkembangan;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use File;
use Image;

class PerkembanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role == 'admin'){
            $perkembangan = Perkembangan::orderBy("id")->get();
        }
        else{
            $necktag_ternaks = Ternak::where('user_id', Auth::id())
                                ->pluck('necktag')->toArray();
            $perkembangan = Perkembangan::whereIn('necktag', $necktag_ternaks)
                                ->orderBy("id")->get();
        }

        return response()->json([
            'status' => 'success',
            'perkembangan'  => $perkembangan,
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
            'necktag' => 'required',
            'tgl_perkembangan' => 'required',
            'berat_badan' => 'required',
            'panjang_badan' => 'required',
            'foto' => 'image|mimes:jpeg,jpg,bmp,png,gif,svg|max:2048',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $date = date('Y-m-d', strtotime($request->tgl_perkembangan));

        if($request->hasFile('foto')){                
            $file = $request->file('foto');
            $name_img = 'images/perkembangan/' . $request->necktag . '_' . $date . '_' . time(). '.' . $file->getClientOriginalExtension();

            $img = Image::make($file->path());
            $img->resize(1280, 1280, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($name_img);
        }
        else{
            $name_img = null;
        }

        $form_data = array(
            'necktag' => $request->necktag,
            'tgl_perkembangan' => $request->tgl_perkembangan,
            'berat_badan' => $request->berat_badan,
            'panjang_badan' => $request->panjang_badan,
            'lingkar_dada' => $request->lingkar_dada,
            'tinggi_pundak' => $request->tinggi_pundak,
            'lingkar_skrotum' => $request->lingkar_skrotum,
            'foto' => $name_img,
            'keterangan' => $request->keterangan,
        );

        $perkembangan = Perkembangan::create($form_data);

        return response()->json([
            'status' => 'success',
            'perkembangan'  => $perkembangan,
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
        $perkembangan = Perkembangan::find($id);
        
        return response()->json([
            'status' => 'success',
            'perkembangan'  => $perkembangan,
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
            'necktag' => 'required',
            'tgl_perkembangan' => 'required',
            'berat_badan' => 'required',
            'panjang_badan' => 'required',
            'foto' => 'image|mimes:jpeg,jpg,bmp,png,gif,svg|max:2048',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json([
                'status' => 'error',
                'error' => $error->errors()->all()
            ]);
        }

        $date = date('Y-m-d', strtotime($request->tgl_perkembangan));

        $perkembangan = Perkembangan::find($id);

        if($request->hasFile('foto')){
            if($perkembangan->foto != null){
                unlink($perkembangan->foto);
            }
                
            $file = $request->file('foto');
            $name_img = 'images/perkembangan/' . $request->necktag . '_' . $date . '_' . time(). '.' . $file->getClientOriginalExtension();

            $img = Image::make($file->path());
            $img->resize(1280, 1280, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($name_img);
        }
        elseif($request->tgl_perkembangan != $perkembangan->tgl_perkembangan){
            $extension = explode('.', $perkembangan->foto)[1];
            $name_img = 'images/perkembangan/' . $request->necktag . '_' . $date . '_' . time(). '.' . $extension;
                
            rename(public_path($perkembangan->foto), public_path($name_img));
        }
        else{
            if($perkembangan->foto != null){
                $name_img = $perkembangan->foto;
            }
            else{
                $name_img = null;
            }
        }

        $form_data = array(
            'necktag' => $request->necktag,
            'tgl_perkembangan' => $request->tgl_perkembangan,
            'berat_badan' => $request->berat_badan,
            'panjang_badan' => $request->panjang_badan,
            'lingkar_dada' => $request->lingkar_dada,
            'tinggi_pundak' => $request->tinggi_pundak,
            'lingkar_skrotum' => $request->lingkar_skrotum,
            'foto' => $name_img,
            'keterangan' => $request->keterangan,
            'updated_at' => Carbon::now()
        );

        $perkembangan->update($form_data);
        
        return response()->json([
            'status' => 'success',
            'perkembangan'  => $perkembangan,
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
        $data = Perkembangan::findOrFail($id);
        if($data->foto){
            unlink($data->foto);
        }
        $data->delete();

        return response()->json([
            'status' => 'success',
            'message'  => "Data perkembangan id ". $id ." telah berhasil dihapus.",
        ], 200);
    }
}
