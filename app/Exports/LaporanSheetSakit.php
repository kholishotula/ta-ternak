<?php

namespace App\Exports;

use App\RiwayatPenyakit;
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
        return RiwayatPenyakit::query()->select("necktag", "tgl_sakit", "nama_penyakit", "obat", "lama_sakit", "keterangan", "created_at", "updated_at")
                        ->whereBetween('tgl_sakit', [$this->start, $this->end]);
    }

    public function headings(): array
    {
        return ["necktag", "tgl_sakit", "nama_penyakit", "obat", "lama_sakit", "keterangan", "created_at", "updated_at"];
    }

    public function title(): string
    {
        return 'ternak_sakit';
    }
}
