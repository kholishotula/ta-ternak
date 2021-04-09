@extends('layouts.part')

@push('link')
<!-- <link href="{{ asset('/adminbsb/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet"> -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"> -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"> -->
@endpush

@section('title')
<h2>GENERATE BARCODE - NECKTAG</h2>
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
<li class="active"><i class="material-icons">view_week</i> Barcode </li>
@endsection

@section('content')
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header row">
                <h2 class="col-md-9">
                    BARCODE NECKTAG 
                    <small>Barcode necktag pada ternak</small>
                </h2>
                <div class="col-md-3" align="right">
                    @can('isAdmin')
                	<a href="{{ route('admin.barcode.pdf') }}">
                    @else
                    <a href="{{ route('peternak.barcode.pdf') }}">
                    @endcan
	                    <button id="bar-dwd-btn" class="btn">
	                        <i class="material-icons">file_download</i>
	                        <span class="icon-name">Download Barcode Necktag</span>
	                    </button>
                    </a>
                </div>
            </div>
            <div class="body table-responsive">

                <table width="100%" id="barcode-table" class="table table-bordered"> 
                	<tr>
                	@foreach($ternak as $data)
                		<td>{{ $no }}</td>
			       		<td align="center" style="border: lpx solid #ccc;">{{ $data->necktag }}<br>
			       			<img src="data:image/png;base64,{{DNS1D::getBarcodePNG($data->necktag, 'C128')}}" height="60" width="180">
			      			<br>{{ $data->necktag }}
			      		</td>
				    	@if($no++ %3 == 0)
				    		</tr>
                            <tr>
				    	@endif
			    	@endforeach
			    	</tr>
                </table>
                
                <div align="right">
                    {{ $ternak->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<!-- <script src="{{ asset('/js/barcode.js') }}"></script> -->

<!-- <script src="{{ asset('/adminbsb/plugins/jquery-datatable/jquery.dataTables.js') }}"></script> -->
<!-- <script src="{{ asset('/adminbsb/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script> -->
<!-- <script src="{{ asset('/adminbsb/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script> -->
<!-- <script src="{{ asset('/adminbsb/plugins/jquery-datatable/extensions/export/buttons.flash.min.js') }}"></script> -->
<!-- <script src="{{ asset('/adminbsb/plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script> -->
<!-- <script src="{{ asset('/adminbsb/plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script> -->
<!-- <script src="{{ asset('/adminbsb/plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script> -->
<!-- <script src="{{ asset('/adminbsb/plugins/jquery-datatable/extensions/export/buttons.html5.min.js') }}"></script> -->
<!-- <script src="{{ asset('/adminbsb/plugins/jquery-datatable/extensions/export/buttons.print.min.js') }}"></script> -->
<!-- <script src="{{ asset('/adminbsb/js/pages/tables/jquery-datatable.js') }}"></script> -->


<!-- <script type="text/javascript">
    $('#barcode-table').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy',
            'excel',
            'pdf'
        ]
    });
</script> -->
@endpush