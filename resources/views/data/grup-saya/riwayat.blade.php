@extends('data.grup-saya.index')

@section('table-content')
<!-- tabel -->
<div class="table-responsive">
    <table id="riwayat-table" class="table table-bordered table-condensed table-striped">
        <thead>
            <tr>
                <th>No.</th>
                <th>Necktag</th>
                <th>Nama Penyakit</th>
                <th>Tgl Sakit</th>
                <th>Lama Sakit</th>
                <th>Obat</th>
                <th>Keterangan</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('script2')
<script src="{{ asset('/js/data/grup-saya/datariwayat.js') }}"></script>
@endpush