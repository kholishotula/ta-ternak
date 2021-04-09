@extends('layouts.part')

@push('link')
<link href="{{ asset('/adminbsb/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
<!-- Dropzone Css -->
<link href="{{ asset('/adminbsb/plugins/dropzone/dropzone.css') }}" rel="stylesheet">
<!-- Multi Select Css -->
<link href="{{ asset('/adminbsb/plugins/multi-select/css/multi-select.css') }}" rel="stylesheet">
<!-- Bootstrap Spinner Css -->
<link href="{{ asset('/adminbsb/plugins/jquery-spinner/css/bootstrap-spinner.css') }}" rel="stylesheet">
<!-- Bootstrap Tagsinput Css -->
<link href="{{ asset('/adminbsb/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">
<!-- Bootstrap Select Css -->
<link href="{{ asset('/adminbsb/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />

<link href="{{ asset('/adminbsb/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" />

<link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('title')
<h2>PERKAWINAN - KECOCOKAN TERNAK</h2>
@endsection

@section('breadcrumb')
<li>
    @can('isAdmin')
    <a href="{{ route('admin') }}">
    @else
    <a href="{{ route('peternak') }}">
    @endcan
        <i class="material-icons">home</i> Home
    </a>
</li>
<li class="active"><i class="material-icons">compare</i> Perkawinan</li>
@endsection

@section('content')
 <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Pilih Kode Ternak (Necktag) yang Ingin Dipasangkan</h2>
            </div>
            <div class="body">
                <form method="post" id="match_form">
                @csrf
                
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <p><b>Jantan</b></p>
                            <div class="form-group">
                                <div>
                                    <select class="form-control js-select-search" name="necktag_jt" id="necktag_jt">
                                        <option></option>
                                        @foreach ($ternak as $tay)
                                            @if ($tay->jenis_kelamin == 'Jantan')
                                                <option value="{{ $tay->necktag }}">{{ $tay->necktag }} - Ras {{ $tay->jenis_ras }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <p><b>Betina</b></p>
                            <div class="form-group">
                                <div>
                                    <select class="form-control js-select-search" name="necktag_bt" id="necktag_bt">
                                        <option></option>
                                        @foreach ($ternak as $tib)
                                            @if ($tib->jenis_kelamin == 'Betina')
                                                <option value="{{ $tib->necktag }}">{{ $tib->necktag }} - Ras {{ $tib->jenis_ras }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="form-group" align="center">
                            <input type="hidden" name="action" id="action" value="Add">
                            <input type="hidden" name="hidden_id" id="hidden_id">
                            <input type="submit" name="action_button" id="action_button" class="btn btn-primary waves-effect" value="Lihat Kecocokan">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('/js/select2.min.js') }}"></script>
<script src="{{ asset('/js/match.js') }}"></script>
<script src="{{ asset('/adminbsb/plugins/sweetalert/sweetalert.min.js') }}"></script>
@endpush