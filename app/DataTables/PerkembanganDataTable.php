<?php

namespace App\DataTables;

use App\Ternak;
use App\Perkembangan;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\URL;

class PerkembanganDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable()
    {
        $current_url = URL::current();

        return datatables()
            ->eloquent($this->query())
            ->addColumn('action', function($row){
                $btn = '<button type="button" name="view" id="'.$row->id.'" class="view btn btn-info btn-sm" ><i class="material-icons">remove_red_eye</i></button>';
                $btn .= '<button type="button" name="edit" id="'.$row->id.'" class="edit btn btn-warning btn-sm" ><i class="material-icons">mode_edit</i></button>';
                $btn .= '<button type="button" name="delete" id="'.$row->id.'" class="delete btn btn-danger btn-sm" ><i class="material-icons">delete</i></button>';
                return $btn;
            })
            ->addColumn('foto', function($row){
                $img = URL::to('/').'/'.$row->foto;
                return $img;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Perkembangan $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        if($this->peternak_id != null){
            $necktag_ternaks = Ternak::where('user_id', $this->peternak_id)
                                    ->pluck('necktag')->toArray();
            return Perkembangan::whereIn('perkembangans.necktag', $necktag_ternaks)
                                ->join('ternaks', 'ternaks.necktag', '=', 'perkembangans.necktag')
                                ->selectRaw('perkembangans.*, ternaks.jenis_kelamin as jenis_kelamin')
                                ->orderBy('necktag', 'asc');
        }
        else{
            return Perkembangan::join('ternaks', 'ternaks.necktag', '=', 'perkembangans.necktag')
                    ->selectRaw('perkembangans.*, ternaks.jenis_kelamin as jenis_kelamin')
                    ->orderBy('necktag', 'asc');;
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
                    ->setTableId('perkembangan-table')
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
            Column::make('necktag')
                ->title('Necktag'),
            Column::make('jenis_kelamin')
                ->title('Jenis Kelamin'),
            Column::make('tgl_perkembangan')
                ->title('Tgl Perkembangan'),
            Column::make('berat_badan')
                ->title('Berat Badan')
                ->addClass('d-none'),
            Column::make('panjang_badan')
                ->title('Panjang Badan')
                ->addClass('d-none'),
            Column::make('lingkar_dada')
                ->title('Lingkar Dada')
                ->addClass('d-none'),
            Column::make('tinggi_pundak')
                ->title('Tinggi Pundak')
                ->addClass('d-none'),
            Column::make('lingkar_skrotum')
                ->title('Lingkar Skrotum')
                ->addClass('d-none'),
            Column::make('foto')
                ->title('Foto')
                ->addClass('d-none'),
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
        return 'SITERNAK_Perkembangan_' . date('YmdHis');
    }
}
