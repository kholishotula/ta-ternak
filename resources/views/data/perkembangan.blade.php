@extends('data.index')

@push('link2')
<!-- Dropzone Css -->
<link href="{{ asset('adminbsb/plugins/dropzone/dropzone.css') }}" rel="stylesheet">
@endpush

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
				<form method="post" id="tambah_edit_data_form" enctype="multipart/form-data">
					@csrf
                    <input type="hidden" name="hidden_id" id="hidden_id">
					<div class="form-group">
						<label class="control-label">Necktag</label>
						<div>
							<select class="form-control js-select-search" name="necktag" id="necktag">
								<option></option>
							  	@foreach ($ternaks as $ternaks)
							    <option value="{{ $ternaks->necktag }}">{{ $ternaks->necktag }}</option>
								@endforeach    
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Tanggal Perkembangan</label>
						<div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">date_range</i>
                            </span>
                            <div class="form-line">
                                <input type="text" name="tgl_perkembangan" id="tgl_perkembangan" class="datepicker form-control" placeholder="Pilih tanggal...">
                            </div>
                        </div>
					</div>
					<div class="form-group">
						<label class="control-label">Berat Badan (dalam kg)</label>
						<div class="form-line col-md-8">
							<input type="number" step="any" name="berat_badan" id="berat_badan" class="form-control">
						</div>
					</div>
                    <div class="form-group">
						<label class="control-label">Panjang Badan (dalam cm)</label>
						<div class="form-line col-md-8">
							<input type="number" step="any" name="panjang_badan" id="panjang_badan" class="form-control">
						</div>
					</div>
                    <div class="form-group">
						<label class="control-label">Lingkar Dada (dalam cm)</label>
						<div class="form-line col-md-8">
							<input type="number" step="any" name="lingkar_dada" id="lingkar_dada" class="form-control">
						</div>
					</div>
                    <div class="form-group">
						<label class="control-label">Tinggi Pundak (dalam cm)</label>
						<div class="form-line col-md-8">
							<input type="number" step="any" name="tinggi_pundak" id="tinggi_pundak" class="form-control">
						</div>
					</div>
                    <div class="form-group">
						<label class="control-label">Lingkar Skrotum (dalam cm)</label>
						<div class="form-line col-md-8">
							<input type="number" step="any" name="lingkar_skrotum" id="lingkar_skrotum" class="form-control">
						</div>
					</div>
                    <!-- <div class="form-group" id="eform-file">
                        <label class="control-label">Foto</label>
                        <div class="form-line col-md-8">
                            <img id="eimage">
                        </div>
                        <br>
                        <b>Ubah foto?</b>
						<div>
							<select class="form-control js-select-search" name="ubah-foto" id="ubah-foto">
								<option value="tidak">Tidak</option>
                                <option value="ya">Ya</option>
							</select>
						</div>
                    </div>
                    <div class="form-group" id="form-file">
						<label class="control-label">Upload Gambar</label>
						<div class="form-line col-md-8">
                            <input type="file" name="image" id="image" class="form-control">
						</div>
					</div> -->
                    <div class="form-group">
                        <label class="control-label">Foto</label>
                        <div class="form-line col-md-6">
                            <input type="file" class="form-control" id="foto" name="foto">
                        </div>               
                        <div class="form-line col-md-6">
                            <img id="preview-image" src="https://www.riobeauty.co.uk/images/product_image_not_found.gif"
                                alt="preview image" style="max-height: 250px;">
                        </div>
                    </div>
					<div class="form-group">
						<label class="control-label">Keterangan</label>
						<div class="form-line col-md-8">
							<input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="kosongkan jika tidak ada">
						</div>
					</div>
					<br>
					<div class="form-group" align="center">
                        <button type="submit" class="btn btn-success" id="btn-save" value="tambah_data"></button>
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
                    <label class="control-label">Necktag</label>
                    <div class="form-line col-md-8">
                        <input type="text" name="necktag" id="vnecktag" class="form-control" readonly="true">
                    </div>
                </div>
                <div class="form-group">
					<label class="control-label">Tanggal Perkembangan</label>
					<div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">date_range</i>
                        </span>
                        <div class="form-line">
                            <input type="text" name="tgl_perkembangan" id="vtgl_perkembangan" class="form-control" readonly="true">
                        </div>
                    </div>
				</div>
				<div class="form-group">
                    <label class="control-label">Berat Badan (kg)</label>
                    <div class="form-line col-md-8">
                        <input type="text" name="berat_badan" id="vberat_badan" class="form-control" readonly="true">
                    </div>
                </div>
				<div class="form-group">
                    <label class="control-label">Panjang Badan (cm)</label>
                    <div class="form-line col-md-8">
                        <input type="text" name="panjang_badan" id="vpanjang_badan" class="form-control" readonly="true">
                    </div>
                </div>
				<div class="form-group">
                    <label class="control-label">Lingkar Dada (cm)</label>
                    <div class="form-line col-md-8">
                        <input type="text" name="lingkar_dada" id="vlingkar_dada" class="form-control" readonly="true">
                    </div>
                </div>
				<div class="form-group">
                    <label class="control-label">Tinggi Pundak (cm)</label>
                    <div class="form-line col-md-8">
                        <input type="text" name="tinggi_pundak" id="vtinggi_pundak" class="form-control" readonly="true">
                    </div>
                </div>
				<div class="form-group">
                    <label class="control-label">Lingkar Skrotum (cm)</label>
                    <div class="form-line col-md-8">
                        <input type="text" name="lingkar_skrotum" id="vlingkar_skrotum" class="form-control" readonly="true">
                    </div>
                </div>
				<div class="form-group" id="vform-file">
                    <label class="control-label">Foto</label>
                    <div class="form-line col-md-8">
                        <img id="vimage">
                    </div>
                </div>
				<div class="form-group">
                    <label class="control-label">Keterangan</label>
                    <div class="form-line col-md-8">
                        <input type="text" name="keterangan" id="vketerangan" class="form-control" readonly="true">
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
				<br>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script2')
{{ $dataTable->scripts() }}
<script src="{{ asset('/js/data/dataperkembangan.js') }}"></script>

<!-- Dropzone Plugin Js -->
<script src="{{ asset('adminbsb/plugins/dropzone/dropzone.js') }}"></script>
@endpush