@extends('data.index')

@section('table-content')
<!-- tabel -->
<div class="table-responsive">
    <table id="verif-table" class="table table-bordered table-condensed table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Grup Peternak</th>
                <th>No KTP</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Status Verifikasi</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('script2')
<script src="{{ asset('/js/data/dataVerifikasi.js') }}"></script>
@endpush