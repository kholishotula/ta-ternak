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
                        <label class="control-label">Nama</label>
                        <div class="form-line col-md-8">
                            <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">KTP</label>
                        <div class="form-line col-md-8">
                            <input type="text" name="ktp" id="ktp" class="form-control">
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

<!-- modal view -->
<div id="viewModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tambah Data - {{ $page }}</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label">Nama</label>
                    <div class="form-line col-md-8">
                        <input type="text" name="nama_pemilik" id="vnama_pemilik" class="form-control" readonly="true">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">KTP</label>
                    <div class="form-line col-md-8">
                        <input type="text" name="ktp" id="vktp" class="form-control" readonly="true">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Created At</label>
                    <div class="form-line col-md-8">
                        <input type="text" name="created_at" id="vcreated_at" class="form-control" readonly="true">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Updated At</label>
                    <div class="form-line col-md-8">
                        <input type="text" name="updated_at" id="vupdated_at" class="form-control" readonly="true">
                    </div>
                </div>

                <!-- riwayat -->
                <div>
                    <label class="control-label">Ternak</label>
                    <div>
                        <span id="span-rp"></span>
                        <table id="ternak-pemilik" class="table">
                            
                        </table>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script2')
{{ $dataTable->scripts() }}
<script src="{{ asset('/js/data/datapemilik.js') }}"></script>
@endpush