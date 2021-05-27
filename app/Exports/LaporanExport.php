<?php

namespace App\Exports;

use App\Ternak;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class LaporanExport implements WithMultipleSheets
{
	use Exportable;

    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new LaporanSheetLahir($this->start, $this->end);
        $sheets[] = new LaporanSheetMati($this->start, $this->end);
        $sheets[] = new LaporanSheetJual($this->start, $this->end);
        $sheets[] = new LaporanSheetKawin($this->start, $this->end);
        $sheets[] = new LaporanSheetSakit($this->start, $this->end);
        $sheets[] = new LaporanSheetAda($this->start, $this->end);

        return $sheets;
    }
}
