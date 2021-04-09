<?php

namespace App\Exports;

use App\Ternak;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanSheetSakit implements FromQuery, WithHeadings, WithTitle
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
    	return DB::table('riwayat_penyakits')->join('public.penyakits', 'penyakits.id', '=', 'riwayat_penyakits.penyakit_id')
                    ->select('riwayat_penyakits.id', 'penyakits.nama_penyakit as penyakit_id', 'riwayat_penyakits.necktag', 'riwayat_penyakits.tgl_sakit', 'riwayat_penyakits.obat', 'riwayat_penyakits.lama_sakit', 'riwayat_penyakits.keterangan', 'riwayat_penyakits.created_at', 'riwayat_penyakits.updated_at')
                    ->whereBetween('riwayat_penyakits.tgl_sakit', [$this->start, $this->end])
                    ->orderBy('riwayat_penyakits.id');
    }

    public function headings(): array
    {
        return ["id", "nama_penyakit", "necktag", "tgl_sakit", "obat", "lama_sakit", "keterangan", "created_at", "updated_at"];
    }

    public function title(): string
    {
        return 'ternak_sakit';
    }
}
