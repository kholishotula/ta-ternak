@extends('data.grup-saya.index')

@section('table-content')
<!-- tabel -->
<div class="table-responsive">
    <table id="peternak-table" class="table table-bordered table-condensed table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Grup Peternak</th>
                <th>No KTP</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Password First</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Status Verifikasi</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('script2')
<script src="{{ asset('/js/data/grup-saya/datapeternak.js') }}"></script>
@endpush