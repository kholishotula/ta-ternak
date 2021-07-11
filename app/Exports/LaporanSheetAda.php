<?php

namespace App\Exports;

use App\Ternak;
use App\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanSheetAda implements FromQuery, WithHeadings, WithTitle
{
    protected $start;
    protected $end;
    protected $grup_id;
    protected $peternak_id;

    public function __construct($start, $end, $grup_id, $peternak_id)
    {
        $this->start = $start;
        $this->end = $end;
        $this->grup_id = $grup_id;
        $this->peternak_id = $peternak_id;
    }

    public function query()
    {
        if($this->grup_id != null){
            $user_ids = User::where('grup_id', $this->grup_id)->pluck('id')->toArray();

            $existFromDead = DB::table('ternaks')
                                ->join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                                ->where('kematians.tgl_kematian', '>', $this->end)
                                ->where('ternaks.tgl_lahir', '<=', $this->end)
                                ->whereIn('ternaks.user_id', $user_ids)
                                ->select("ternaks.necktag", "ternaks.pemilik_id", "ternaks.user_id", "ternaks.ras_id", "ternaks.kematian_id", "ternaks.penjualan_id", "ternaks.jenis_kelamin", "ternaks.tgl_lahir", "ternaks.bobot_lahir", "ternaks.pukul_lahir", "ternaks.lama_dikandungan", "ternaks.lama_laktasi", "ternaks.tgl_lepas_sapih", "ternaks.necktag_ayah", "ternaks.necktag_ibu", "ternaks.cacat_fisik", "ternaks.ciri_lain", "ternaks.status_ada", "ternaks.created_at", "ternaks.updated_at");

            $existFromSold = DB::table('ternaks')
                                ->join('penjualans', 'penjualans.id', '=', 'ternaks.penjualan_id')
                                ->where('penjualans.tgl_terjual', '>', $this->end)
                                ->where('ternaks.tgl_lahir', '<=', $this->end)
                                ->whereIn('ternaks.user_id', $user_ids)
                                ->select("ternaks.necktag", "ternaks.pemilik_id", "ternaks.user_id", "ternaks.ras_id", "ternaks.kematian_id", "ternaks.penjualan_id", "ternaks.jenis_kelamin", "ternaks.tgl_lahir", "ternaks.bobot_lahir", "ternaks.pukul_lahir", "ternaks.lama_dikandungan", "ternaks.lama_laktasi", "ternaks.tgl_lepas_sapih", "ternaks.necktag_ayah", "ternaks.necktag_ibu", "ternaks.cacat_fisik", "ternaks.ciri_lain", "ternaks.status_ada", "ternaks.created_at", "ternaks.updated_at");

            return DB::table('ternaks')->select("necktag", "pemilik_id", "user_id", "ras_id", "kematian_id", "penjualan_id", "jenis_kelamin", "tgl_lahir", "bobot_lahir", "pukul_lahir", "lama_dikandungan", "lama_laktasi", "tgl_lepas_sapih", "necktag_ayah", "necktag_ibu", "cacat_fisik", "ciri_lain", "status_ada", "created_at", "updated_at")
                        ->union($existFromDead)
                        ->union($existFromSold)
                        ->where('status_ada', true)
                        ->where('tgl_lahir', '<=', $this->end)
                        ->whereIn('user_id', $user_ids)
                        ->where('deleted_at', null)
                        ->orderBy('created_at');
        }
        elseif($this->peternak_id != null){
            $existFromDead = DB::table('ternaks')
                                ->join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                                ->where('kematians.tgl_kematian', '>', $this->end)
                                ->where('ternaks.tgl_lahir', '<=', $this->end)
                                ->where('ternaks.user_id', $this->peternak_id)
                                ->select("ternaks.necktag", "ternaks.pemilik_id", "ternaks.user_id", "ternaks.ras_id", "ternaks.kematian_id", "ternaks.penjualan_id", "ternaks.jenis_kelamin", "ternaks.tgl_lahir", "ternaks.bobot_lahir", "ternaks.pukul_lahir", "ternaks.lama_dikandungan", "ternaks.lama_laktasi", "ternaks.tgl_lepas_sapih", "ternaks.necktag_ayah", "ternaks.necktag_ibu", "ternaks.cacat_fisik", "ternaks.ciri_lain", "ternaks.status_ada", "ternaks.created_at", "ternaks.updated_at");

            $existFromSold = DB::table('ternaks')
                                ->join('penjualans', 'penjualans.id', '=', 'ternaks.penjualan_id')
                                ->where('penjualans.tgl_terjual', '>', $this->end)
                                ->where('ternaks.tgl_lahir', '<=', $this->end)
                                ->where('ternaks.user_id', $this->peternak_id)
                                ->select("ternaks.necktag", "ternaks.pemilik_id", "ternaks.user_id", "ternaks.ras_id", "ternaks.kematian_id", "ternaks.penjualan_id", "ternaks.jenis_kelamin", "ternaks.tgl_lahir", "ternaks.bobot_lahir", "ternaks.pukul_lahir", "ternaks.lama_dikandungan", "ternaks.lama_laktasi", "ternaks.tgl_lepas_sapih", "ternaks.necktag_ayah", "ternaks.necktag_ibu", "ternaks.cacat_fisik", "ternaks.ciri_lain", "ternaks.status_ada", "ternaks.created_at", "ternaks.updated_at");

            return DB::table('ternaks')->select("necktag", "pemilik_id", "user_id", "ras_id", "kematian_id", "penjualan_id", "jenis_kelamin", "tgl_lahir", "bobot_lahir", "pukul_lahir", "lama_dikandungan", "lama_laktasi", "tgl_lepas_sapih", "necktag_ayah", "necktag_ibu", "cacat_fisik", "ciri_lain", "status_ada", "created_at", "updated_at")
                        ->union($existFromDead)
                        ->union($existFromSold)
                        ->where('status_ada', true)
                        ->where('tgl_lahir', '<=', $this->end)
                        ->where('user_id', $this->peternak_id)
                        ->where('deleted_at', null)
                        ->orderBy('created_at');
        }
        else{
            $existFromDead = DB::table('ternaks')
                                ->join('kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                                ->where('kematians.tgl_kematian', '>', $this->end)
                                ->where('ternaks.tgl_lahir', '<=', $this->end)
                                ->select("ternaks.necktag", "ternaks.pemilik_id", "ternaks.user_id", "ternaks.ras_id", "ternaks.kematian_id", "ternaks.penjualan_id", "ternaks.jenis_kelamin", "ternaks.tgl_lahir", "ternaks.bobot_lahir", "ternaks.pukul_lahir", "ternaks.lama_dikandungan", "ternaks.lama_laktasi", "ternaks.tgl_lepas_sapih", "ternaks.necktag_ayah", "ternaks.necktag_ibu", "ternaks.cacat_fisik", "ternaks.ciri_lain", "ternaks.status_ada", "ternaks.created_at", "ternaks.updated_at");

            $existFromSold = DB::table('ternaks')
                                ->join('penjualans', 'penjualans.id', '=', 'ternaks.penjualan_id')
                                ->where('penjualans.tgl_terjual', '>', $this->end)
                                ->where('ternaks.tgl_lahir', '<=', $this->end)
                                ->select("ternaks.necktag", "ternaks.pemilik_id", "ternaks.user_id", "ternaks.ras_id", "ternaks.kematian_id", "ternaks.penjualan_id", "ternaks.jenis_kelamin", "ternaks.tgl_lahir", "ternaks.bobot_lahir", "ternaks.pukul_lahir", "ternaks.lama_dikandungan", "ternaks.lama_laktasi", "ternaks.tgl_lepas_sapih", "ternaks.necktag_ayah", "ternaks.necktag_ibu", "ternaks.cacat_fisik", "ternaks.ciri_lain", "ternaks.status_ada", "ternaks.created_at", "ternaks.updated_at");
                                
            return DB::table('ternaks')->select("necktag", "pemilik_id", "user_id", "ras_id", "kematian_id", "penjualan_id", "jenis_kelamin", "tgl_lahir", "bobot_lahir", "pukul_lahir", "lama_dikandungan", "lama_laktasi", "tgl_lepas_sapih", "necktag_ayah", "necktag_ibu", "cacat_fisik", "ciri_lain", "status_ada", "created_at", "updated_at")
                        ->union($existFromDead)
                        ->union($existFromSold)
                        ->where('status_ada', true)
                        ->where('tgl_lahir', '<=', $this->end)
                        ->where('deleted_at', null)
                        ->orderBy('created_at');
        }
    }

    public function headings(): array
    {
        return ["necktag", "pemilik_id", "peternak_id", "ras_id", "kematian_id", "penjualan_id", "jenis_kelamin", "tgl_lahir", "bobot_lahir", "pukul_lahir", "lama_dikandungan", "lama_laktasi", "tgl_lepas_sapih", "necktag_ayah", "necktag_ibu", "cacat_fisik", "ciri_lain", "status_ada", "created_at", "updated_at"];
    }

    public function title(): string
    {
        return 'ternak_ada';
    }
}
