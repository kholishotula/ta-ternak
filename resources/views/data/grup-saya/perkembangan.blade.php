@extends('data.grup-saya.index')

@section('table-content')
<!-- tabel -->
<div class="table-responsive">
    <table id="perkembangan-table" class="table table-bordered table-condensed table-striped">
        <thead>
            <tr>
                <th>No.</th>
                <th>Necktag</th>
                <th>Tgl Perkembangan</th>
                <th>Berat Badan (kg)</th>
                <th>Panjang Badan (cm)</th>
                <th>Lingkar Dada (cm)</th>
                <th>Tinggi Pundak (cm)</th>
                <th>Lingkar Skrotum (cm)</th>
                <th>Keterangan</th>
                <th>Foto</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('script2')
<script src="{{ asset('/js/data/grup-saya/dataperkembangan.js') }}"></script>
@endpush