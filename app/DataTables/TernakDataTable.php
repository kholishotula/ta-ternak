<?php

namespace App\DataTables;

use App\Ternak;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TernakDataTable extends DataTable
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
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<button type="button" name="view" id="'.$row->necktag.'" class="view btn btn-info btn-sm" ><i class="material-icons">remove_red_eye</i></button>';
                $btn .= '<button type="button" name="edit" id="'.$row->necktag.'" class="edit btn btn-warning btn-sm" ><i class="material-icons">mode_edit</i></button>';
                $btn .= '<button type="button" name="delete" id="'.$row->necktag.'" class="delete btn btn-danger btn-sm" ><i class="material-icons">delete</i></button>';
                return $btn;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\TernakDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Ternak $model)
    {
        $query = $model->newQuery()->select('*');

        // if($query->status_ada){
        //     $query->status_ada = 'Ada';
        // }else{
        //     $query->status_ada = 'Tidak Ada';
        // }

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('ternak-table')
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
            Column::computed('DT_RowIndex')
                ->title('No.'),
            Column::make('necktag')
                ->title('Necktag'),
            Column::computed('action')
                ->title('Action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),
            Column::make('pemilik_id')
                ->title('ID Pemilik'),
            Column::make('peternakan_id')
                ->title('ID Peternakan'),
            Column::make('ras_id')
                ->title('ID Ras'),
            Column::make('kematian_id')
                ->title('ID Kematian'),
            Column::make('jenis_kelamin')
                ->title('Jenis Kelamin'),
            Column::make('tgl_lahir')
                ->title('Tanggal Lahir'),
            Column::make('bobot_lahir')
                ->title('Bobot Lahir'),
            Column::make('pukul_lahir')
                ->title('Pukul Lahir'),
            Column::make('lama_dikandungan')
                ->title('Lama diKandungan'),
            Column::make('lama_laktasi')
                ->title('Lama Laktasi'),
            Column::make('tgl_lepas_sapih')
                ->title('Tanggal Lepas Sapih'),
            Column::make('blood')
                ->title('Blood'),
            Column::make('necktag_ayah')
                ->title('Ayah'),
            Column::make('necktag_ibu')
                ->title('Ibu'),
            Column::make('bobot_tubuh')
                ->title('Bobot Tubuh'),
            Column::make('panjang_tubuh')
                ->title('Panjang Tubuh'),
            Column::make('tinggi_tubuh')
                ->title('Tinggi Tubuh'),
            Column::make('cacat_fisik')
                ->title('Cacat Fisik'),
            Column::make('ciri_lain')
                ->title('Ciri Lain'),
            Column::make('status_ada')
                ->title('Status'),
            Column::make('created_at')
                ->title('Created At'),
            Column::make('updated_at')
                ->title('Updated At'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'SITERNAK_Ternak_' . date('YmdHis');
    }
}
