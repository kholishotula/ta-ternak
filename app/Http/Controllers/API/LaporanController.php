<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Ternak;
use App\Kematian;
use App\Penjualan;
use App\RiwayatPenyakit;
use App\Perkembangan;
use App\Perkawinan;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->role == 'peternak'){
            $lahir = Ternak::whereBetween('tgl_lahir', [$request->datefrom, $request->dateto])
                        ->where('user_id', Auth::id())->get();

            $mati = Kematian::select('ternaks.necktag', 'ternaks.kematian_id', 'kematians.tgl_kematian', 'kematians.waktu_kematian', 'kematians.penyebab', 'kematians.kondisi', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                        ->join('public.ternaks', 'kematians.id', '=', 'ternaks.kematian_id')
                        ->whereBetween('kematians.tgl_kematian', [$request->datefrom, $request->dateto])
                        ->where('ternaks.user_id', Auth::id())->get();
            
            $jual = Penjualan::select('ternaks.necktag', 'ternaks.penjualan_id', 'penjualans.tgl_terjual', 'penjualans.ket_pembeli', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                        ->join('public.ternaks', 'penjualans.id', '=', 'ternaks.penjualan_id')
                        ->whereBetween('penjualans.tgl_terjual', [$request->datefrom, $request->dateto])
                        ->where('ternaks.user_id', Auth::id())->get();

            $kawin = Perkawinan::select('perkawinans.*')
                        ->join('public.ternaks', 'perkawinans.necktag', '=', 'ternaks.necktag')
                        ->whereBetween('tgl_kawin', [$request->datefrom, $request->dateto])
                        ->where('ternaks.user_id', Auth::id())->get();

            $sakit = RiwayatPenyakit::select('riwayat_penyakits.*')
                        ->join('public.ternaks', 'riwayat_penyakits.necktag', '=', 'ternaks.necktag')
                        ->whereBetween('tgl_sakit', [$request->datefrom, $request->dateto])
                        ->where('ternaks.user_id', Auth::id())->get();

            $perkembangan = Perkembangan::select('perkembangans.*', 'ternaks.jenis_kelamin')
                                ->join('public.ternaks', 'perkembangans.necktag', '=', 'ternaks.necktag')
                                ->whereBetween('perkembangans.tgl_perkembangan', [$request->datefrom, $request->dateto])
                                ->where('ternaks.user_id', Auth::id())->get();

            $exists_union = Ternak::join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                                ->where('tgl_kematian', '>', $request->dateto)
                                ->where('tgl_lahir', '<', $request->dateto)
                                ->selectRaw('ternaks.*');
            $exists = Ternak::where('status_ada', true)
                        ->where('tgl_lahir', '<', $request->dateto)
                        ->union($exists_union)
                        ->where('ternaks.user_id', Auth::id())->get();
        }
        else{
            if($request->grup_id != null){
                $user_ids = User::where('grup_id', $request->grup_id)
                                ->pluck('id')->toArray();

                if($user_ids != null){
                    $lahir = Ternak::whereBetween('tgl_lahir', [$request->datefrom, $request->dateto])
                                ->whereIn('user_id', $user_ids)
                                ->get();

                    $mati = Kematian::select('ternaks.necktag', 'ternaks.kematian_id', 'kematians.tgl_kematian', 'kematians.waktu_kematian', 'kematians.penyebab', 'kematians.kondisi', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                        ->join('public.ternaks', 'kematians.id', '=', 'ternaks.kematian_id')
                        ->whereBetween('kematians.tgl_kematian', [$request->datefrom, $request->dateto])
                        ->whereIn('ternaks.user_id', $user_ids)
                        ->get();

                    $jual = Penjualan::select('ternaks.necktag', 'ternaks.penjualan_id', 'penjualans.tgl_terjual', 'penjualans.ket_pembeli', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                        ->join('public.ternaks', 'penjualans.id', '=', 'ternaks.penjualan_id')
                        ->whereBetween('penjualans.tgl_terjual', [$request->datefrom, $request->dateto])
                        ->whereIn('ternaks.user_id', $user_ids)
                        ->get();

                    $kawin = Perkawinan::select('perkawinans.*')
                        ->join('public.ternaks', 'perkawinans.necktag', '=', 'ternaks.necktag')
                        ->whereIn('ternaks.user_id', $user_ids)
                        ->whereBetween('tgl_kawin', [$request->datefrom, $request->dateto])
                        ->get();
                    
                    $sakit = RiwayatPenyakit::select('riwayat_penyakits.*')
                                ->join('public.ternaks', 'riwayat_penyakits.necktag', '=', 'ternaks.necktag')
                                ->whereIn('ternaks.user_id', $user_ids)
                                ->whereBetween('tgl_sakit', [$request->datefrom, $request->dateto])
                                ->get();
                    
                    $perkembangan = Perkembangan::select('perkembangans.*', 'ternaks.jenis_kelamin')
                                        ->join('public.ternaks', 'perkembangans.necktag', '=', 'ternaks.necktag')
                                        ->whereBetween('perkembangans.tgl_perkembangan', [$request->datefrom, $request->dateto])
                                        ->whereIn('ternaks.user_id', $user_ids)
                                        ->get();
                    
                    $exists_union = Ternak::join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                                    ->where('tgl_kematian', '>', $request->dateto)
                                    ->where('tgl_lahir', '<', $request->dateto)
                                    ->selectRaw('ternaks.*');
                    $exists = Ternak::where('status_ada', true)
                                ->where('tgl_lahir', '<', $request->dateto)
                                ->whereIn('user_id', $user_ids)
                                ->union($exists_union)
                                ->get();
                }
                else{
                    $lahir = [];
                    $mati = [];
                    $jual = [];
                    $kawin = [];
                    $sakit = [];
                    $perkembangan = [];
                    $exists = [];
                }
            }
            else{
                $lahir = Ternak::whereBetween('tgl_lahir', [$request->datefrom, $request->dateto])
                            ->get();

                $mati = Kematian::select('ternaks.necktag', 'ternaks.kematian_id', 'kematians.tgl_kematian', 'kematians.waktu_kematian', 'kematians.penyebab', 'kematians.kondisi', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                            ->join('public.ternaks', 'kematians.id', '=', 'ternaks.kematian_id')
                            ->whereBetween('kematians.tgl_kematian', [$request->datefrom, $request->dateto])
                            ->get();
                
                $jual = Penjualan::select('ternaks.necktag', 'ternaks.penjualan_id', 'penjualans.tgl_terjual', 'penjualans.ket_pembeli', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                            ->join('public.ternaks', 'penjualans.id', '=', 'ternaks.penjualan_id')
                            ->whereBetween('penjualans.tgl_terjual', [$request->datefrom, $request->dateto])
                            ->get();

                $kawin = Perkawinan::whereBetween('tgl_kawin', [$request->datefrom, $request->dateto])
                            ->get();

                $sakit = RiwayatPenyakit::whereBetween('tgl_sakit', [$request->datefrom, $request->dateto])
                            ->get();

                $perkembangan = Perkembangan::select('perkembangans.*', 'ternaks.jenis_kelamin')
                                    ->join('public.ternaks', 'perkembangans.necktag', '=', 'ternaks.necktag')
                                    ->whereBetween('perkembangans.tgl_perkembangan', [$request->datefrom, $request->dateto])
                                    ->get();

                $exists_union = Ternak::join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                                    ->where('tgl_kematian', '>', $request->dateto)
                                    ->where('tgl_lahir', '<', $request->dateto)
                                    ->selectRaw('ternaks.*');
                $exists = Ternak::where('status_ada', true)
                            ->where('tgl_lahir', '<', $request->dateto)
                            ->union($exists_union)
                            ->get();
            }
        }

        return response()->json([
            'status' => 'success',
            'lahir' => $lahir,
            'mati' => $mati,
            'jual' => $jual,
            'kawin' => $kawin,
            'sakit' => $sakit,
            'perkembangan' => $perkembangan,
            'ada' => $exists,
        ], 200);

    }
}
