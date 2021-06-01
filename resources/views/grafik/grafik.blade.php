@extends('layouts.part')

@push('link')
<!-- <link href="{{ asset('/bootstrap/css/bootstrap.css.map') }}" rel="stylesheet" /> -->
@endpush

@section('title')
<h2>GRAFIK</h2>
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
<li class="active"><i class="material-icons">pie_chart</i> Grafik </li>
@endsection

@section('content')
<div>
    <div class="card">
        <div class="header">
            <h2>Berdasarkan UMUR</h2>
        </div>
        <div class="body">
            <div style="margin: 0 auto;">
                {{ $umur->container() }}
            </div>
        </div>
    </div>
</div>
<div>
    <div class="card">
        <div class="header">
            <h2>Berdasarkan RAS</h2>
        </div>
        <div class="body">
            <div style="margin: 0 auto;">
                {{ $ras->container() }}
            </div>
        </div>
    </div>
</div>
<div>
    <div class="card">
        <div class="header">
            <h2>Berdasarkan KELAHIRAN</h2>
            <ul class="header-dropdown m-r--5">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        @foreach($years as $year)
                            <li><a href="javascript:void(0);" id="g-lahir-{{ $year }}" class="g-lahir">{{ $year }}</a></li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div>
        <div class="body">
            <div style="margin: 0 auto;" id="glahir">
                {{ $lahir->container() }}
            </div>
        </div>
    </div>
</div>
<div>
    <div class="card">
        <div class="header">
            <h2>Berdasarkan KEMATIAN</h2>
            <ul class="header-dropdown m-r--5">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        @foreach($years as $year)
                            <li><a href="javascript:void(0);" id="g-mati-{{ $year }}" class="g-mati">{{ $year }}</a></li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div>
        <div class="body">
            <div style="margin: 0 auto;" id="g-mati">
                {{ $mati->container() }}
            </div>
        </div>
    </div>
</div>
<div>
    <div class="card">
        <div class="header">
            <h2>Berdasarkan PENJUALAN</h2>
            <ul class="header-dropdown m-r--5">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        @foreach($years as $year)
                            <li><a href="javascript:void(0);" id="g-jual-{{ $year }}" class="g-jual">{{ $year }}</a></li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div>
        <div class="body">
            <div style="margin: 0 auto;" id="g-jual">
                {{ $jual->container() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('/js/chart.min.js') }}"></script>

{{ $ras->script() }}
{{ $umur->script() }}
{{ $lahir->script() }}
{{ $mati->script() }}
{{ $jual->script() }}

<script type="text/javascript">
var segments = location.pathname.split('/');
var seg = segments[1];
var url_seg;

if(seg == 'admin'){
    url_seg = "/admin";
}
else if(seg == 'peternak'){
    url_seg = "/peternak";
}
else if(seg == 'ketua-grup'){
    url_seg = "/ketua-grup";
}

// lahir
$(document).on('click', '.g-lahir', function(){
    var id = $(this).attr('id');
    id = id.split('-');
    // 0:g, 1:lahir, 2:tahun

    $.ajax({
        url: url_seg+"/grafik/lahir",
        method: "GET",
        data: {
            tahun: id[2],
        },
        datatype: "json",
        success: function(data){
            var lahir_id = <?php echo $lahir->id; ?>;

            // console.log(lahir_id);

            lahir_id.options.title.text = "Grafik Ternak - Kelahiran ("+ id[2] +")";
            lahir_id.data.datasets[0].data = data.jantan;
            lahir_id.data.datasets[1].data = data.betina;
            lahir_id.data.datasets[2].data = data.data;

            lahir_id.update();
        },
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
    });
});    

// mati
$(document).on('click', '.g-mati', function(){
    var id = $(this).attr('id');
    id = id.split('-');
    // 0:g, 1:lahir, 2:tahun

    $.ajax({
        url: url_seg+"/grafik/mati",
        method: "GET",
        data: {
            tahun: id[2],
        },
        datatype: "json",
        success: function(data){
            var mati_id = <?php echo $mati->id; ?>;

            // console.log(lahir_id);

            mati_id.options.title.text = "Grafik Ternak - Kematian ("+ id[2] +")";
            mati_id.data.datasets[0].data = data.jantan;
            mati_id.data.datasets[1].data = data.betina;
            mati_id.data.datasets[2].data = data.data;

            mati_id.update();
        },
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
    });
});  

// jual
$(document).on('click', '.g-jual', function(){
    var id = $(this).attr('id');
    id = id.split('-');
    // 0:g, 1:lahir, 2:tahun

    $.ajax({
        url: url_seg+"/grafik/jual",
        method: "GET",
        data: {
            tahun: id[2],
        },
        datatype: "json",
        success: function(data){
            var jual_id = <?php echo $jual->id; ?>;

            // console.log(lahir_id);

            jual_id.options.title.text = "Grafik Ternak - Penjualan ("+ id[2] +")";
            jual_id.data.datasets[0].data = data.jantan;
            jual_id.data.datasets[1].data = data.betina;
            jual_id.data.datasets[2].data = data.data;

            jual_id.update();
        },
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
    });
});  
</script>
@endpush