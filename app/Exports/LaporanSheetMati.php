<?php

namespace App\Exports;

use App\Ternak;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanSheetMati implements FromQuery, WithHeadings, WithTitle
{
    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function query()
    {
    	return Ternak::query()->select('ternaks.necktag', 'ternaks.kematian_id', 'kematians.tgl_kematian', 'kematians.waktu_kematian', 'kematians.penyebab', 'kematians.kondisi', 'ternaks.pemilik_id', 'ternaks.peternakan_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.bobot_lahir', 'ternaks.pukul_lahir', 'ternaks.lama_dikandungan', 'ternaks.lama_laktasi', 'ternaks.tgl_lepas_sapih', 'ternaks.blood', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.bobot_tubuh', 'ternaks.panjang_tubuh', 'ternaks.tinggi_tubuh', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                        ->join('public.kematians', 'kematians.id', '=', 'ternaks.kematian_id')
                        ->whereBetween('kematians.tgl_kematian', [$this->start, $this->end]);
    }

    public function headings(): array
    {
        return ["necktag", "kematian_id", "tgl_kematian", "waktu_kematian", "penyebab", "kondisi", "pemilik_id", "peternakan_id", "ras_id", "jenis_kelamin", "tgl_lahir", "bobot_lahir", "pukul_lahir", "lama_dikandungan", "lama_laktasi", "tgl_lepas_sapih", "blood", "necktag_ayah", "necktag_ibu", "bobot_tubuh", "panjang_tubuh", "tinggi_tubuh", "cacat_fisik", "ciri_lain", "status_ada", "created_at", "updated_at"];
    }

    public function title(): string
    {
        return 'ternak_mati';
    }
}
