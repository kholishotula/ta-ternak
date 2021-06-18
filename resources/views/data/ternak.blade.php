@extends('data.index')

@push('link2')
<style>
	.d-none{
		display: none;
	}
</style>
@endpush

@section('table-content')
<div align="left">
    <button type="button" name="tambah_data" id="tambah_data" class="btn btn-success btn-sm">
		<i class="material-icons">add</i><span class="icon-name">Tambah Data Ternak</span>
	</button>
</div>

<br>

<ul class="nav nav-tabs tab-nav-right" role="tablist">
	<li role="presentation" class="active"><a href="#data-ternak" data-toggle="tab"><i class="material-icons">dns</i><span class="icon-name">DATA TERNAK</span></a></li>
	<li role="presentation"><a href="#tongsampah" data-toggle="tab" onclick="tongsampahDT();"><i class="material-icons">delete</i><span class="icon-name">TONG SAMPAH</span></a></li>
</ul>

<div class="tab-content">
    <div role="tabpanel" class="tab-pane fade in active" id="data-ternak">
    	<!-- tabel data ternak -->
		<div class="table-responsive">
			{{ $dataTable->table(['class' => 'table table-bordered table-condensed table-striped']) }}
		</div>
	</div>

	<div role="tabpanel" class="tab-pane fade" id="tongsampah">
		<div align="left">
		    <button type="button" name="btn-restore-all" id="btn-restore-all" class="btn btn-warning btn-sm">
				<i class="material-icons">restore</i><span class="icon-name">Kembalikan Semua</span>
			</button>
			<button type="button" name="btn-delete-all" id="btn-delete-all" class="btn btn-danger btn-sm">
				<i class="material-icons">delete_forever</i><span class="icon-name">Hapus Permanen Semua</span>
			</button>
		</div>
		<br>
		<div class="table-responsive">
			<table id="tongsampah-table" class="table table-bordered table-condensed table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Necktag</th>
                        <th>ID Pemilik</th>
                        <th>ID Peternak</th>
                        <th>ID Ras</th>
                        <th>Jenis Kelamin</th>
                        <th>Ayah</th>
                        <th>Ibu</th>
                        <th>Status Ada</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Deleted At</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
               
            </table>
		</div>
	</div>

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
						<label class="control-label">Pemilik<span class="text-danger">*</span></label>
						<div>
							<select class="form-control js-select-search" name="pemilik_id" id="pemilik_id">
								<option></option>
							  	@foreach ($pemilik as $pid)
							    <option value="{{ $pid->id }}">{{ $pid->nama_pemilik }}</option>
								@endforeach    
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Peternak<span class="text-danger">*</span></label>
						<div>
							<select class="form-control js-select-search" name="peternak_id" id="peternak_id">
								<option></option>
							  	@foreach ($peternak as $pid)
							    <option value="{{ $pid->id }}">{{ $pid->id }} - {{ $pid->name }}</option>
								@endforeach    
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Ras<span class="text-danger">*</span></label>
						<div>
							<select class="form-control js-select-search" name="ras_id" id="ras_id">
								<option></option>
							  	@foreach ($ras as $rid)
							    <option value="{{ $rid->id }}">{{ $rid->jenis_ras }}</option>
								@endforeach    
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Jenis Kelamin<span class="text-danger">*</span></label>
						<div class="form-line col-md-8">
							<select class="form-control" name="jenis_kelamin" id="jenis_kelamin" class="form-control">
								<option value="Jantan">Jantan</option>
							    <option value="Betina">Betina</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Tanggal Lahir</label>
						<div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">date_range</i>
                            </span>
                            <div class="form-line">
                                <input type="text" name="tgl_lahir" id="tgl_lahir" class="datepicker form-control" placeholder="Pilih tanggal...">
                            </div>
                        </div>
					</div>
					<div class="form-group">
						<label class="control-label">Bobot Lahir</label>
						<div class="form-line col-md-8">
							<input type="text" name="bobot_lahir" id="bobot_lahir" class="form-control" placeholder="dalam kilogram">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Pukul Lahir</label>
						<div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">access_time</i>
                            </span>
                            <div class="form-line">
                                <input type="text" name="pukul_lahir" id="pukul_lahir" class="timepicker form-control" placeholder="Pilih waktu...">
                            </div>
                        </div>
					</div>
					<div class="form-group">
						<label class="control-label">Lama di Kandungan</label>
						<div class="form-line col-md-8">
							<input type="text" name="lama_dikandungan" id="lama_dikandungan" class="form-control" placeholder="dalam hari">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Lama Laktasi</label>
						<div class="form-line col-md-8">
							<input type="text" name="lama_laktasi" id="lama_laktasi" class="form-control" placeholder="dalam hari">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Tanggal Lepas Sapih</label>
						<div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">date_range</i>
                            </span>
                            <div class="form-line">
                                <input type="text" name="tgl_lepas_sapih" id="tgl_lepas_sapih" class="datepicker form-control" placeholder="Pilih tanggal...">
                            </div>
                        </div>
					</div>
					<div class="form-group">
						<label class="control-label">Ayah</label>
						<div>
							<select class="form-control js-select-search" name="necktag_ayah" id="necktag_ayah">
								<option></option>
							  	@foreach ($data as $tay)
								  	@if ($tay->jenis_kelamin == 'Jantan')
									    <option value="{{ $tay->necktag }}">{{ $tay->necktag }} - Ras {{ $tay->jenis_ras }}</option>
								    @endif
								@endforeach    
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Ibu</label>
						<div>
							<select class="form-control js-select-search" name="necktag_ibu" id="necktag_ibu">
								<option></option>
							  	@foreach ($data as $tib)
								    @if ($tib->jenis_kelamin == 'Betina')
									    <option value="{{ $tib->necktag }}">{{ $tib->necktag }} - Ras {{ $tib->jenis_ras }}</option>
								    @endif
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Cacat Fisik</label>
						<div class="form-line col-md-8">
							<input type="text" name="cacat_fisik" id="cacat_fisik" class="form-control" placeholder="kosongkan bila tidak ada">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Ciri Lain</label>
						<div class="form-line col-md-8">
							<input type="text" name="ciri_lain" id="ciri_lain" class="form-control" placeholder="kosongkan bila tidak ada">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Status Kambing<span class="text-danger">*</span></label>
						<div class="form-line col-md-8">
							<select class="form-control" name="status_ada" id="status_ada">
								<option value="true">Ada</option>
							    <option value="false">Tidak ada</option>
							</select>
						</div>
					</div>
					<br>
					<div class="form-group" align="center">
						<input type="hidden" name="action" id="action" value="Add">
						<input type="hidden" name="hidden_id" id="hidden_id">
						<input type="submit" name="action_button" id="action_button" class="btn" value="Add">
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
					<label class="control-label">Pemilik</label>
					<div class="form-line col-md-8">
						<input type="text" name="pemilik_id" id="vpemilik_id" class="form-control" readonly="true">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Peternak</label>
					<div class="form-line col-md-8">
						<input type="text" name="peternak_id" id="vpeternak_id" class="form-control" readonly="true">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Ras</label>
					<div class="form-line col-md-8">
						<input type="text" name="ras_id" id="vras_id" class="form-control" readonly="true">
					</div>
				</div>
				<div class="form-group" id="kematian_form">
					<label class="control-label">Kematian</label>
					<div class="form-line col-md-8">
						<input type="text" name="kematian_id" id="vkematian_id" class="form-control" readonly="true">
					</div>
				</div>
				<div class="form-group" id="penjualan_form">
					<label class="control-label">Penjualan</label>
					<div class="form-line col-md-8">
						<input type="text" name="penjualan_id" id="vpenjualan_id" class="form-control" readonly="true">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Jenis Kelamin</label>
					<div class="form-line col-md-8">
						<input type="text" name="jenis_kelamin" id="vjenis_kelamin" class="form-control" readonly="true">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Tanggal Lahir</label>
					<div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">date_range</i>
                        </span>
                        <div class="form-line">
                            <input type="text" name="tgl_lahir" id="vtgl_lahir" class="form-control" readonly="true">
                        </div>
                    </div>
				</div>
				<div class="form-group">
					<label class="control-label">Bobot Lahir</label>
					<div class="form-line col-md-8">
						<input type="text" name="bobot_lahir" id="vbobot_lahir" class="form-control" readonly="true">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Pukul Lahir</label>
					<div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">access_time</i>
                        </span>
                        <div class="form-line">
                            <input type="text" name="pukul_lahir" id="vpukul_lahir" class="form-control" readonly="true">
                        </div>
                    </div>
				</div>
				<div class="form-group">
					<label class="control-label">Lama di Kandungan</label>
					<div class="form-line col-md-8">
						<input type="text" name="lama_dikandungan" id="vlama_dikandungan" class="form-control" readonly="true">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Lama Laktasi</label>
					<div class="form-line col-md-8">
						<input type="text" name="lama_laktasi" id="vlama_laktasi" class="form-control" readonly="true">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Tanggal Lepas Sapih</label>
					<div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">date_range</i>
                        </span>
                        <div class="form-line">
                            <input type="text" name="tgl_lepas_sapih" id="vtgl_lepas_sapih" class="form-control" readonly="true">
                        </div>
                    </div>
				</div>
				<div class="form-group">
					<label class="control-label">Ayah</label>
					<div class="form-line col-md-8">
						<input type="text" name="necktag_ayah" id="vnecktag_ayah" class="form-control" readonly="true">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Ibu</label>
					<div class="form-line col-md-8">
						<input type="text" name="necktag_ibu" id="vnecktag_ibu" class="form-control" readonly="true">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Cacat Fisik</label>
					<div class="form-line col-md-8">
						<input type="text" name="cacat_fisik" id="vcacat_fisik" class="form-control" readonly="true">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Ciri Lain</label>
					<div class="form-line col-md-8">
						<input type="text" name="ciri_lain" id="vciri_lain" class="form-control" readonly="true">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Status Kambing</label>
					<div class="form-line col-md-8">
						<input type="text" name="status_ada" id="vstatus_ada" class="form-control" readonly="true">
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
					<label class="control-label">Riwayat Penyakit</label>
					<div>
						<span id="span-rp"></span>
						<table id="riwayat-penyakit" class="table">
							
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
<script src="{{ asset('/js/data/dataternak.js') }}"></script>
@endpush