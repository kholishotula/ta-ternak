<?php

namespace App\Http\Controllers\Peternak;

use App\Ternak;
use App\Perkembangan;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\DataTables\PerkembanganDataTable;
use Yajra\Datatables\Datatables;
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
    public function index(PerkembanganDataTable $dataTable)
    {
        $title = 'PENCATATAN PERKEMBANGAN';
        $page = 'Pencatatan Perkembangan';
        $ternaks = Ternak::where('user_id', Auth::id())->get();
        
        return $dataTable->with('peternak_id', Auth::id())->render('data.perkembangan', [
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
        $rules = array(
            'necktag' => 'required',
            'tgl_perkembangan' => 'required',
            'image' => 'image|mimes:jpeg,jpg,bmp,png,gif,svg|max:2048',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if($file = $request->file('image')){
            $date = date('Y-m-d', strtotime($request->tgl_perkembangan));
            // $destinationPath = public_path('images/perkembangan');
            $name_img = 'images/perkembangan/' . $request->necktag . '_' . $date . '_' . time(). '.' . $file->getClientOriginalExtension();

            $img = Image::make($file->path());
            $img->resize(1024, 1024, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($name_img);

            // $file->move('images/perkembangan', $name_img);
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
            'keterangan' => $request->keterangan,
            'foto' => $name_img,
        );

        Perkembangan::create($form_data);

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
            $data = Perkembangan::findOrFail($id);
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
            $data = Perkembangan::find($id);
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
            'necktag' => 'required',
            'tgl_perkembangan' => 'required',
            'image' => 'image|mimes:jpeg,jpg,bmp,png,gif,svg|max:2048',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $perkembangan = Perkembangan::whereId($id);

        $form_data = array(
            'necktag' => $request->necktag,
            'tgl_perkembangan' => $request->tgl_perkembangan,
            'berat_badan' => $request->berat_badan,
            'panjang_badan' => $request->panjang_badan,
            'lingkar_dada' => $request->lingkar_dada,
            'tinggi_pundak' => $request->tinggi_pundak,
            'lingkar_skrotum' => $request->lingkar_skrotum,
            'keterangan' => $request->keterangan,
            'updated_at' => Carbon::now()
        );

        if($file = $request->file('image')){
            if($perkembangan->foto){
                unlink($perkembangan->foto);
            }
            $date = date('Y-m-d', strtotime($request->tgl_perkembangan));
            $name_img = 'images/perkembangan/' . $request->necktag . '-' . $date . '.' . $file->getClientOriginalExtension();
            $file->move('images/perkembangan', $name_img);
            $form_data['foto'] = $name_img;
        }

        $perkembangan->update($form_data);

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
        $data = Perkembangan::findOrFail($id);
        if($data->foto){
            unlink($data->foto);
        }
        $data->delete();
    }
}
