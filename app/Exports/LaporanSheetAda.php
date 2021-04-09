<?php

namespace App\Exports;

use App\Ternak;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanSheetAda implements FromQuery, WithHeadings, WithTitle
{
    public function query()
    {
    	return Ternak::query()->where('status_ada', true);
    }

    public function headings(): array
    {
        return ["necktag", "pemilik_id", "peternakan_id", "ras_id", "kematian_id", "jenis_kelamin", "tgl_lahir", "bobot_lahir", "pukul_lahir", "lama_dikandungan", "lama_laktasi", "tgl_lepas_sapih", "blood", "necktag_ayah", "necktag_ibu", "bobot_tubuh", "panjang_tubuh", "tinggi_tubuh", "cacat_fisik", "ciri_lain", "status_ada", "created_at", "updated_at"];
    }

    public function title(): string
    {
        return 'ternak_ada';
    }
}
