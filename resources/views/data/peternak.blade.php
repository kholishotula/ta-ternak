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
                        <label class="control-label">Peternakan</label>
                        <div>
            							<select class="form-control js-select-search" name="peternakan_id" id="peternakan_id">
            								<option></option>
            							  	@foreach ($peternakan as $pid)
            								    <option value="{{ $pid->id }}">{{ $pid->id }} - {{ $pid->nama_peternakan }}</option>
            								  @endforeach
            							</select>
            						</div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Nama Peternak</label>
                        <div class="form-line col-md-8">
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                    </div>

                    <!-- add edit authorization -->
                    <!-- <div class="form-group reg-admin">
                        <label class="control-label">Admin Register</label>
                        <div>
            							<select class="form-control js-select-search" name="register_from_admin" id="register_from_admin">
                            <option value="true">Ya</option>
            								<option value="false">Tidak</option>
            							</select>
            						</div>
                    </div> -->

                    <div id="register">
	                    <div>
	                    	<h5 align="center">REGISTRASI AKUN PETERNAK</h5>
	                    </div>
	                    <div class="form-group">
	                        <label class="control-label">Username</label>
	                        <div class="form-line col-md-8">
	                            <input type="text" name="username" id="username" class="form-control">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="control-label">Email</label>
	                        <div class="form-line col-md-8">
	                            <input type="text" name="email" id="email" class="form-control">
	                        </div>
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
<script src="{{ asset('/js/data/datapeternak.js') }}"></script>
@endpush
