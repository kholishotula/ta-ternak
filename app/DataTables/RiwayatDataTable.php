<?php

namespace App\DataTables;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RiwayatDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->queryBuilder($query)
            ->addColumn('action', function($row){
                $btn = '<button type="button" name="edit" id="'.$row->id.'" class="edit btn btn-warning btn-sm" ><i class="material-icons">mode_edit</i></button>';
                $btn .= '<button type="button" name="delete" id="'.$row->id.'" class="delete btn btn-danger btn-sm" ><i class="material-icons">delete</i></button>';
                return $btn;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\RiwayatDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
         $data = DB::table('riwayat_penyakits')->join('public.penyakits', 'penyakits.id', '=', 'riwayat_penyakits.penyakit_id')
                ->select('riwayat_penyakits.id', 'penyakits.nama_penyakit as penyakit_id', 'riwayat_penyakits.necktag', 'riwayat_penyakits.tgl_sakit', 'riwayat_penyakits.obat', 'riwayat_penyakits.lama_sakit', 'riwayat_penyakits.keterangan', 'riwayat_penyakits.created_at', 'riwayat_penyakits.updated_at');
                // ->get();

        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('riwayat-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->buttons(
                        // Button::make('export'),
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('print'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')
                ->title('ID'),
            Column::make('penyakit_id')
                ->title('Penyakit'),
            Column::make('necktag')
                ->title('Necktag'),
            Column::make('tgl_sakit')
                ->title('Tanggal Sakit'),
            Column::make('obat')
                ->title('Obat'),
            Column::make('lama_sakit')
                ->title('Lama Sakit'),
            Column::make('keterangan')
                ->title('Keterangan'),
            Column::make('created_at')
                ->title('Created At'),
            Column::make('updated_at')
                ->title('Updated At'),
            Column::computed('action')
                ->title('Action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'SITERNAK_Riwayat_' . date('YmdHis');
    }
}
