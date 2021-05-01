@extends('data.index')

@section('table-content')
<div align="left">
    <button type="button" name="tambah_data" id="tambah_data" class="btn btn-success btn-sm">
        <i class="material-icons">add</i><span class="icon-name">Tambah Data</span>
    </button>
</div>
<br>
<!-- tabel -->
<div class="table-responsive">
    {{ $dataTable->table(['class' => 'table table-bordered table-condensed table-striped']) }}
</div>

<!-- form modal -->
<div id="formModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tambah Data - {{ $page }}</h4>
            </div>
            <div class="modal-body">
                <span id="form_result"></span>
                <form method="post" id="tambah_data_form">
                    @csrf

                    <div class="form-group">
                        <label class="control-label">Necktag</label>
                        <div class="form-line">
                            <select class="form-control js-select-search" name="necktag" id="necktag">
                                <option></option>
                                @foreach ($ternaks as $ternak)
                                <option value="{{ $ternak->necktag }}">{{ $ternak->necktag }}</option>
                                @endforeach  
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Tanggal Kematian</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">date_range</i>
                            </span>
                            <div class="form-line">
                                <input type="text" name="tgl_kematian" id="tgl_kematian" class="datepicker form-control" placeholder="Pilih tanggal...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Waktu Kematian</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">access_time</i>
                            </span>
                            <div class="form-line">
                                <input type="text" name="waktu_kematian" id="waktu_kematian" class="timepicker form-control" placeholder="Pilih waktu...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Penyebab</label>
                        <div class="form-line col-md-8">
                            <input type="text" name="penyebab" id="penyebab" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Kondisi</label>
                        <div class="form-line col-md-8">
                            <input type="text" name="kondisi" id="kondisi" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="form-group" align="center">
                        <input type="hidden" name="action" id="action" value="Add">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <input type="submit" name="action_button" id="action_button" class="btn btn-warning" value="Add">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script2')
{{ $dataTable->scripts() }}
<script src="{{ asset('/js/data/datakematian.js') }}"></script>
@endpush