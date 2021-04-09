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
    	return Perkawinan::query()->whereBetween('tgl', [$this->start, $this->end]);
    }

    public function headings(): array
    {
        return ["id", "necktag", "necktag_psg", "tgl", "created_at", "updated_at"];
    }

    public function title(): string
    {
        return 'ternak_kawin';
    }
}
