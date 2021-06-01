<?php

namespace App\Exports;

use App\Penjualan;
use App\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanSheetJual implements FromQuery, WithHeadings, WithTitle
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

            return Penjualan::query()->select('ternaks.necktag', 'ternaks.penjualan_id', 'penjualans.tgl_terjual', 'penjualans.ket_pembeli', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                            ->join('public.ternaks', 'penjualans.id', '=', 'ternaks.penjualan_id')
                            ->whereBetween('penjualans.tgl_terjual', [$this->start, $this->end])
                            ->whereIn('ternaks.user_id', $user_ids);
        }
        elseif($this->peternak_id != null){
            return Penjualan::query()->select('ternaks.necktag', 'ternaks.penjualan_id', 'penjualans.tgl_terjual', 'penjualans.ket_pembeli', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                            ->join('public.ternaks', 'penjualans.id', '=', 'ternaks.penjualan_id')
                            ->whereBetween('penjualans.tgl_terjual', [$this->start, $this->end])
                            ->where('ternaks.user_id', $this->peternak_id);
        }
        else{
            return Penjualan::query()->select('ternaks.necktag', 'ternaks.penjualan_id', 'penjualans.tgl_terjual', 'penjualans.ket_pembeli', 'ternaks.pemilik_id', 'ternaks.user_id', 'ternaks.ras_id', 'ternaks.jenis_kelamin', 'ternaks.tgl_lahir', 'ternaks.necktag_ayah', 'ternaks.necktag_ibu', 'ternaks.cacat_fisik', 'ternaks.ciri_lain', 'ternaks.status_ada', 'ternaks.created_at', 'ternaks.updated_at')
                            ->join('public.ternaks', 'penjualans.id', '=', 'ternaks.penjualan_id')
                            ->whereBetween('penjualans.tgl_terjual', [$this->start, $this->end]);
        }
    }

    public function headings(): array
    {
        return ["necktag", "penjualan_id", "tgl_terjual", "ket_pembeli", "pemilik_id", "peternak_id", "ras_id", "jenis_kelamin", "tgl_lahir", "necktag_ayah", "necktag_ibu", "cacat_fisik", "ciri_lain", "status_ada", "created_at", "updated_at"];
    }

    public function title(): string
    {
        return 'ternak_jual';
    }
}
