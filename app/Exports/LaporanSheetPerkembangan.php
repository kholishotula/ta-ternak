<?php

namespace App\Exports;

use App\Perkembangan;
use App\User;
use App\Ternak;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanSheetPerkembangan implements FromQuery, WithHeadings, WithTitle
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
            $necktag_ternaks = Ternak::whereIn('user_id', $user_ids)->pluck('necktag')->toArray();

            return Perkembangan::query()->select("necktag", "tgl_perkembangan", "berat_badan", "panjang_badan", "lingkar_dada", "tinggi_pundak", "lingkar_skrotum", "keterangan", "created_at", "updated_at")
                            ->whereIn('necktag', $necktag_ternaks)
                            ->whereBetween('tgl_perkembangan', [$this->start, $this->end]);
        }
        elseif($this->peternak_id != null){
            $necktag_ternaks = Ternak::where('user_id', $this->peternak_id)->pluck('necktag')->toArray();

            return Perkembangan::query()->select("necktag", "tgl_perkembangan", "berat_badan", "panjang_badan", "lingkar_dada", "tinggi_pundak", "lingkar_skrotum", "keterangan", "created_at", "updated_at")
                            ->whereIn('necktag', $necktag_ternaks)
                            ->whereBetween('tgl_perkembangan', [$this->start, $this->end]);

        }
        else{
            return Perkembangan::query()->select("necktag", "tgl_perkembangan", "berat_badan", "panjang_badan", "lingkar_dada", "tinggi_pundak", "lingkar_skrotum", "keterangan", "created_at", "updated_at")
                            ->whereBetween('tgl_perkembangan', [$this->start, $this->end]);
        }
    }

    public function headings(): array
    {
        return ["necktag", "tgl_perkembangan", "berat_badan", "panjang_badan", "lingkar_dada", "tinggi_pundak", "lingkar_skrotum", "keterangan", "created_at", "updated_at"];
    }

    public function title(): string
    {
        return 'ternak_perkembangan';
    }
}
