@extends('layouts.part')

@push('link')
<!-- <link href="{{ asset('/vendor/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet"> -->
<!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet"> -->
<link href="{{ asset('/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('/datatable/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('/adminbsb/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet" />
<link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('/adminbsb/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
@stack('link2')
@endpush

@section('title')
<h2>DATA - {{ $title ?? '' }}</h2>
@endsection

@section('breadcrumb')
<li>
    @can('isAdmin')
    <a href="{{ route('admin') }}">
    @elsecan('isPeternak')
    <a href="{{ route('peternak') }}">
    @elsecan('isKetua')
    <a href="{{ route('ketua-grup') }}">
    @endcan
        <i class="material-icons">home</i> Home
    </a>
</li>
<li><i class="material-icons">groups</i> Grup Saya </li>
<li class="active"><i class="material-icons">attachment</i> {{ $page ?? '' }} </li>
@endsection

@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    {{ $title ?? '' }}
                </h2>
            </div>
            <div class="body">

                @yield('table-content')
                
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('/adminbsb/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
<script src="{{ asset('/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('/js/select2.min.js') }}"></script>
<script>
    $(function () {
        $('.js-select-search').select2({ width: '100%' });

        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY',
            formatSubmit: 'YYYY-MM-DD',
            clearButton: true,
            weekStart: 1,
            time: false
        }, moment());

        $('.timepicker').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            clearButton: true,
            date: false
        }, moment());
    });
</script>
<script src="{{ asset('/adminbsb/plugins/sweetalert/sweetalert.min.js') }}"></script>

<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.colVis.js"></script>

<script src="{{ asset('/vendor/datatables/buttons.server-side.js') }}"></script>
@stack('script2')
@endpush






