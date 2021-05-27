<?php

namespace App\Exports;

use App\Perkawinan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanSheetKawin implements FromQuery, WithHeadings, WithTitle
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
    	return Perkawinan::query()->select("necktag", "necktag_psg", "tgl_kawin", "created_at", "updated_at")
                        ->whereBetween('tgl_kawin', [$this->start, $this->end]);
    }

    public function headings(): array
    {
        return ["necktag", "necktag_psg", "tgl_kawin", "created_at", "updated_at"];
    }

    public function title(): string
    {
        return 'ternak_kawin';
    }
}
