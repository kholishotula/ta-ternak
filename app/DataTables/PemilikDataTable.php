<?php

namespace App\DataTables;

use App\Ternak;
use App\Pemilik;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PemilikDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable()
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('action', function($row){
                // $btn = '<button type="button" name="view" id="'.$row->id.'" class="view btn btn-info btn-sm" ><i class="material-icons">remove_red_eye</i></button>';
                $btn = '<button type="button" name="edit" id="'.$row->id.'" class="edit btn btn-warning btn-sm" style="margin: 2px;"><i class="material-icons">mode_edit</i></button>';
                $btn .= '<button type="button" name="delete" id="'.$row->id.'" class="delete btn btn-danger btn-sm"><i class="material-icons">delete</i></button>';
                return $btn;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\PemilikDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        if($this->peternak_id != null){
            $pemilik_ids = Ternak::where('user_id', $this->peternak_id)->distinct()->pluck('pemilik_id')->toArray();
            return Pemilik::whereIn('id', $pemilik_ids)->select('*');
        }
        else {
            return Pemilik::select('*');
        }
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('pemilik-table')
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
            Column::make('nama_pemilik')
                ->title('Nama'),
            Column::make('ktp_pemilik')
                ->title('No KTP'),
            Column::make('created_at')
                ->title('Created At'),
            Column::make('updated_at')
                ->title('Updated At'),
            Column::computed('action')
                ->title('Action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
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
        return 'SITERNAK_Pemilik_' . date('YmdHis');
    }
}
