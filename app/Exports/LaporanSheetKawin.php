<?php

namespace App\Exports;

use App\Perkawinan;
use App\User;
use App\Ternak;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanSheetKawin implements FromQuery, WithHeadings, WithTitle
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

            return Perkawinan::query()->select("necktag", "necktag_psg", "tgl_kawin", "created_at", "updated_at")
                        ->whereIn('necktag', $necktag_ternaks)
                        ->whereBetween('tgl_kawin', [$this->start, $this->end]);
        }
        elseif($this->peternak_id != null){
            $necktag_ternaks = Ternak::where('user_id', $this->peternak_id)->pluck('necktag')->toArray();

            return Perkawinan::query()->select("necktag", "necktag_psg", "tgl_kawin", "created_at", "updated_at")
                        ->whereIn('necktag', $necktag_ternaks)
                        ->whereBetween('tgl_kawin', [$this->start, $this->end]);
        }
        else{
    	    return Perkawinan::query()->select("necktag", "necktag_psg", "tgl_kawin", "created_at", "updated_at")
                        ->whereBetween('tgl_kawin', [$this->start, $this->end]);
        }
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
