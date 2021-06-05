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
                        <label class="control-label">Grup Peternak<span class="text-danger">*</span></label>
                        <div>
            				<select class="form-control js-select-search" name="grup_peternak" id="grup_peternak">
            					<option></option>
            				  	@foreach ($grupPeternak as $grupId)
        						    <option value="{{ $grupId->id }}">{{ $grupId->id }} - {{ $grupId->nama_grup }}</option>
    							@endforeach
            				</select>
        				</div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Nama Peternak<span class="text-danger">*</span></label>
                        <div class="form-line col-md-8">
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Jadikan sebagai Ketua Grup<span class="text-danger">*</span></label>
                        <!-- <div class="input-group input-group-lg">
                            <div class="col-md-4">
                                <input type="radio" name="role" id="role_ketua" value="ketua grup">
                                <label for="role">Ya</label>
                            </div>
                            <div class="col-md-4">
                                <input type="radio" name="role" id="role_peternak" value="peternak">
                                <label for="role">Tidak</label>
                            </div>
                        </div> -->
                        <div>
            				<select class="form-control js-select-search" name="role" id="role">
            					<option></option>
            				  	<option value="ketua grup">Ya</option>
                                <option value="peternak">Tidak</option>
            				</select>
        				</div>
                    </div>

                    <div id="register">
	                    <div>
	                    	<h5 align="center">REGISTRASI AKUN PETERNAK</h5>
	                    </div>
                        <div class="form-group">
	                        <label class="control-label">No KTP<span class="text-danger">*</span></label>
	                        <div class="form-line col-md-8">
	                            <input type="text" name="ktp_user" id="ktp_user" class="form-control">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="control-label">Username<span class="text-danger">*</span></label>
	                        <div class="form-line col-md-8">
	                            <input type="text" name="username" id="username" class="form-control">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="control-label">Email<span class="text-danger">*</span></label>
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
