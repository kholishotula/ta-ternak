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
				<h4 class="modal-title">Tambah Data - {{ $page ?? '' }}</h4>
			</div>
			<div class="modal-body">
				<span id="form_result"></span>
				<form method="post" id="tambah_data_form">
					@csrf

					<div class="form-group">
						<label class="control-label">Jenis Ras<span class="text-danger">*</span></label>
						<div class="form-line col-md-8">
							<input type="text" name="jenis_ras" id="jenis_ras" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Keterangan<span class="text-danger">*</span></label>
						<div class="form-line col-md-8">
							<input type="text" name="ket_ras" id="ket_ras" class="form-control">
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
<!-- <script>
 //        // initComplete: function () {
 //        //     this.api().columns().every(function () {
 //        //         var column = this;
 //        //         var input = document.createElement("input");
 //        //         $(input).appendTo($(column.footer()).empty())
 //        //         .on('change', function () {
 //        //             column.search($(this).val(), false, false, true).draw();
 //        //         });
 //        //     });
 //        // };
</script> -->

<script src="{{ asset('/js/data/dataras.js') }}"></script>
@endpush