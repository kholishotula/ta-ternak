@extends('data.grup-saya.index')

@section('table-content')
<!-- tabel -->
<div class="table-responsive">
    <table id="ternak-table" class="table table-bordered table-condensed table-striped">
        <thead>
            <tr>
                <th>No.</th>
                <th>Necktag</th>
                <th>ID Ras</th>
                <th>ID Peternak</th>
                <th>ID Pemilik</th>
                <th>ID Kematian</th>
                <th>ID Penjualan</th>
                <th>Jenis Kelamin</th>
                <th>Tgl Lahir</th>
                <th>Bobot Lahir</th>
                <th>Pukul Lahir</th>
                <th>Lama di Kandungan</th>
                <th>Lama Laktasi</th>
                <th>Tgl Lepas Sapih</th>
                <th>Necktag Ayah</th>
                <th>Necktag Ibu</th>
                <th>Cacat Fisik</th>
                <th>Ciri Lain</th>
                <th>Status Ada</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('script2')
<script src="{{ asset('/js/data/grup-saya/dataternak.js') }}"></script>
@endpush