<?php

namespace App\DataTables;

use App\GrupPeternak;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class GrupPeternakDataTable extends DataTable
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
                $btn = '<button type="button" name="edit" id="'.$row->id.'" class="edit btn btn-warning btn-sm" style="margin: 2px;"><i class="material-icons">mode_edit</i></button>';
                $btn .= '<button type="button" name="delete" id="'.$row->id.'" class="delete btn btn-danger btn-sm"><i class="material-icons">delete</i></button>';
                return $btn;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\GrupPeternakDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $model = GrupPeternak::join('users', 'users.grup_id', '=', 'grup_peternaks.id')
            ->selectRaw('grup_peternaks.*, coalesce(count(users.id), 0) as jumlah')
            ->groupBy('grup_peternaks.id')
            ->orderBy('grup_peternaks.id');
        return $model;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('grup-peternak-table')
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
            Column::make('nama_grup')
                ->title('Nama Grup Peternak'),
            Column::make('alamat')
                ->title('Alamat'),
            Column::make('provinsi')
                ->title('Provinsi'),
            Column::make('kab_kota')
                ->title('Kabupaten/Kota'),
            Column::make('kecamatan')
                ->title('Kecamatan'),
            Column::make('keterangan')
                ->title('Keterangan'),
            Column::make('jumlah')
                ->title('Jumlah Peternak'),
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
        return 'SITERNAK_Grup-Peternak_' . date('YmdHis');
    }
}
