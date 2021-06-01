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
    protected $grup_id;
    protected $peternak_id;

    public function __construct($start, $end, $grup_id = null, $peternak_id = null)
    {
        $this->start = $start;
        $this->end = $end;
        $this->grup_id = $grup_id;
        $this->peternak_id = $peternak_id;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new LaporanSheetLahir($this->start, $this->end, $this->grup_id, $this->peternak_id);
        $sheets[] = new LaporanSheetMati($this->start, $this->end, $this->grup_id, $this->peternak_id);
        $sheets[] = new LaporanSheetJual($this->start, $this->end, $this->grup_id, $this->peternak_id);
        $sheets[] = new LaporanSheetKawin($this->start, $this->end, $this->grup_id, $this->peternak_id);
        $sheets[] = new LaporanSheetSakit($this->start, $this->end, $this->grup_id, $this->peternak_id);
        $sheets[] = new LaporanSheetAda($this->start, $this->end, $this->grup_id, $this->peternak_id);

        return $sheets;
    }
}
