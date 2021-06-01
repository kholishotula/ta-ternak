<?php

namespace App\Exports;

use App\Ternak;
use App\User;
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

            return Ternak::query()->select("necktag", "pemilik_id", "user_id", "ras_id", "kematian_id", "penjualan_id", "jenis_kelamin", "tgl_lahir", "bobot_lahir", "pukul_lahir", "lama_dikandungan", "lama_laktasi", "tgl_lepas_sapih", "necktag_ayah", "necktag_ibu", "cacat_fisik", "ciri_lain", "status_ada", "created_at", "updated_at")
                        ->whereIn('user_id', $user_ids)
                        ->where('status_ada', true)
                        ->where('tgl_lahir', '<', $this->end);
        }
        elseif($this->peternak_id != null){
            return Ternak::query()->select("necktag", "pemilik_id", "user_id", "ras_id", "kematian_id", "penjualan_id", "jenis_kelamin", "tgl_lahir", "bobot_lahir", "pukul_lahir", "lama_dikandungan", "lama_laktasi", "tgl_lepas_sapih", "necktag_ayah", "necktag_ibu", "cacat_fisik", "ciri_lain", "status_ada", "created_at", "updated_at")
                        ->where('user_id', $this->peternak_id)
                        ->where('status_ada', true)
                        ->where('tgl_lahir', '<', $this->end);
        }
        else{
            return Ternak::query()->select("necktag", "pemilik_id", "user_id", "ras_id", "kematian_id", "penjualan_id", "jenis_kelamin", "tgl_lahir", "bobot_lahir", "pukul_lahir", "lama_dikandungan", "lama_laktasi", "tgl_lepas_sapih", "necktag_ayah", "necktag_ibu", "cacat_fisik", "ciri_lain", "status_ada", "created_at", "updated_at")
                        ->where('status_ada', true)
                        ->where('tgl_lahir', '<', $this->end);
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
