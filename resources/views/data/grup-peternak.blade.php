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
                        <label class="control-label">Nama Grup Peternak</label>
                        <div class="form-line col-md-8">
                            <input type="text" name="nama_grup" id="nama_grup" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Alamat</label>
                        <div class="form-line col-md-8">
                            <input type="text" name="alamat" id="alamat" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Provinsi</label>
                        <div class="form-line col-md-8">
                            <input type="text" name="provinsi" id="provinsi" class="form-control">
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="control-label">Provinsi</label>
                        <div class="form-line col-md-8">
                            <select id="provinsi" class="form-control" name="provinsi"></select>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label class="control-label">Kabupaten/Kota</label>
                        <div class="form-line col-md-8">
                            <input type="text" name="kab_kota" id="kab_kota" class="form-control">
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="control-label">Kabupaten/Kota</label>
                        <div class="form-line col-md-8">
                            <select name="kab_kota" id="kab_kota" class="form-control"></select>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label class="control-label">Kecamatan</label>
                        <div class="form-line col-md-8">
                            <input type="text" name="kecamatan" id="kecamatan" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Keterangan</label>
                        <div class="form-line col-md-8">
                            <input type="text" name="keterangan" id="keterangan" class="form-control">
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
<!-- <script src="http://api.iksgroup.co.id/apijs/lokasiapi.js"></script> -->
            <!-- <script>
            var render=createwidgetlokasi("provinsi","kabupaten","kecamatan","kelurahan");
            </script> -->
<!-- <script src="{{ asset('/js/data/dataProvinsi.js') }}"></script> -->
{{ $dataTable->scripts() }}
<script src="{{ asset('/js/data/datagrupPeternak.js') }}"></script>
@endpush