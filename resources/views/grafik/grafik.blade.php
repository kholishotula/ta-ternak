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
            @can('isAdmin')
            <ul class="header-dropdown m-r--5">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="javascript:void(0);" id="g-umur-all" class="g-umur" style="color: red">Reset</a></li>
                        @foreach($grups as $grup)
                            <li><a href="javascript:void(0);" id="g-umur-{{ $grup->id }}" class="g-umur">{{ $grup->nama_grup }}</a></li>
                        @endforeach
                    </ul>
                </li>
            </ul>
            @endcan
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
            @can('isAdmin')
            <ul class="header-dropdown m-r--5">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="javascript:void(0);" id="g-ras-all" class="g-ras" style="color: red">Reset</a></li>
                        @foreach($grups as $grup)
                            <li><a href="javascript:void(0);" id="g-ras-{{ $grup->id }}" class="g-ras">{{ $grup->nama_grup }}</a></li>
                        @endforeach
                    </ul>
                </li>
            </ul>
            @endcan
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
        <div class="header row">
            <div class="col-md-10">
                <h2>Berdasarkan KELAHIRAN</h2>
            </div>
            <div class="col-md-2 align-right">
                <button type="button" id="btn-filter-lahir" class="btn btn-success" >FILTER</button>
            </div>
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
        <div class="header row">
            <div class="col-md-10">
                <h2>Berdasarkan KEMATIAN</h2>
            </div>
            <div class="col-md-2 align-right">
                <button type="button" id="btn-filter-mati" class="btn btn-success" >FILTER</button>
            </div>
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
        <div class="header row">
            <div class="col-md-10">
                <h2>Berdasarkan PENJUALAN</h2>
            </div>
            <div class="col-md-2 align-right">
                <button type="button" id="btn-filter-jual" class="btn btn-success" >FILTER</button>
            </div>
        </div>
        <div class="body">
            <div style="margin: 0 auto;" id="g-jual">
                {{ $jual->container() }}
            </div>
        </div>
    </div>
</div>
<div>
    <div class="card">
        <div class="header row">
            <div class="col-md-10">
                <h2>Berdasarkan PERKAWINAN</h2>
            </div>
            <div class="col-md-2 align-right">
                <button type="button" id="btn-filter-kawin" class="btn btn-success" >FILTER</button>
            </div>
        </div>
        <div class="body">
            <div style="margin: 0 auto;" id="g-kawin">
                {{ $kawin->container() }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="filter-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">FILTER GRAFIK</h5>
            </div>
            <div class="modal-body">
                <form id="filter-form">
                    <div class="row clearfix">
                        <div class="col-md-12">
                            @can('isAdmin')
                            <div class="form-group">
                                <label class="control-label">Grup Peternak</label>
                                <div class="form-line">
                                    <select class="form-control js-select-search" name="grup" id="grup">
                                        <option value=""></option>
                                        @foreach ($grups as $grup)
                                        <option value="{{ $grup->id }}">{{ $grup->nama_grup }}</option>
                                        @endforeach  
                                    </select>
                                </div>
                            </div>
                            @elsecan('isKetua')
                            <select class="form-control js-select-search hidden" name="grup" id="grup">
                                <option value="{{ $grup_id }}" type="hidden"></option> 
                            </select>
                            @elsecan('isPeternak')
                            <select class="form-control js-select-search hidden" name="grup" id="grup">
                                <option value="" type="hidden"></option> 
                            </select>
                            @endcan
                            <div class="form-group">
                                <label class="control-label">Tahun</label>
                                <div class="form-line">
                                    <select class="form-control js-select-search" name="tahun" id="tahun">
                                        @foreach($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach  
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn-success" name="filter_button" id="filter_button" value="FILTER">FILTER</button>
                        </div>
                    </div>
                </form>
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
{{ $kawin->script() }}

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

// umur
$(document).on('click', '.g-umur', function(){
    var id = $(this).attr('id');
    id = id.split('-');
    // 0:g, 1:umur, 2:grup_id
    $.ajax({
        url: url_seg+"/grafik/umur",
        method: "GET",
        data: {
            grup_id: id[2],
        },
        datatype: "json",
        success: function(data){
            var umur_id = <?php echo $umur->id; ?>;

            if(data.nama_grup != null){
                umur_id.options.title.text = "Grafik Ternak - Umur (bulan) - Grup Peternak '" + data.nama_grup + "'";
            }
            else{
                umur_id.options.title.text = "Grafik Ternak - Umur (bulan)";
            }
            umur_id.data.datasets[0].data = data.jantan;
            umur_id.data.datasets[1].data = data.betina;
            umur_id.data.datasets[2].data = data.data;
            umur_id.update();
        },
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
    });
});  

// ras
$(document).on('click', '.g-ras', function(){
    var id = $(this).attr('id');
    id = id.split('-');
    // 0:g, 1:ras, 2:grup_id
    $.ajax({
        url: url_seg+"/grafik/ras",
        method: "GET",
        data: {
            grup_id: id[2],
        },
        datatype: "json",
        success: function(data){
            var ras_id = <?php echo $ras->id; ?>;

            if(data.nama_grup != null){
                ras_id.options.title.text = "Grafik Ternak - Ras - Grup Peternak '" + data.nama_grup + "'";
            }
            else{
                ras_id.options.title.text = "Grafik Ternak - Ras";
            }
            ras_id.data.datasets[0].data = data.jantan;
            ras_id.data.datasets[1].data = data.betina;
            ras_id.data.datasets[2].data = data.data;
            ras_id.update();
        },
        error: function (jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR); 
        }
    });
});  

// lahir-mati-jual-kawin

$('#btn-filter-lahir').click(function(){
    $('#filter-modal').modal('show');
    $('#filter_button').val('Lahir')
})
$('#btn-filter-mati').click(function(){
    $('#filter-modal').modal('show');
    $('#filter_button').val('Mati')
})
$('#btn-filter-jual').click(function(){
    $('#filter-modal').modal('show');
    $('#filter_button').val('Jual')
})
$('#btn-filter-kawin').click(function(){
    $('#filter-modal').modal('show');
    $('#filter_button').val('Kawin')
})

$('#filter-form').on('submit', function(event){
	event.preventDefault();
    var grafik = $('#filter_button').val();
    
    switch (grafik) {
        case "Lahir":
            $.ajax({
                url: url_seg+"/grafik/lahir",
                method: "GET", 
                data: {
                    grup_id: $(this).serialize().split('&')[0].split('=')[1],
                    tahun : $(this).serialize().split('&')[1].split('=')[1],
                },
                dataType: "json",
                success:function(data) {
                    var lahir_id = <?php echo $lahir->id; ?>;

                    if(data.nama_grup == null){
                        lahir_id.options.title.text = "Grafik Ternak - Kelahiran ("+ data.tahun +")";
                    }
                    else{
                        lahir_id.options.title.text = "Grafik Ternak - Kelahiran ("+ data.tahun +") - Grup Peternak '" + data.nama_grup + "'";
                    }
                    lahir_id.data.datasets[0].data = data.jantan;
                    lahir_id.data.datasets[1].data = data.betina;
                    lahir_id.data.datasets[2].data = data.data;

                    lahir_id.update();
                    $('#filter-modal').modal('hide');
                },
                error: function (jqXHR, textStatus, errorThrown) { 
                    console.log(jqXHR); 
                }
            });
            break;
        
        case "Mati":
            $.ajax({
                url: url_seg+"/grafik/mati",
                method: "GET", 
                data: {
                    grup_id: $(this).serialize().split('&')[0].split('=')[1],
                    tahun : $(this).serialize().split('&')[1].split('=')[1],
                },
                dataType: "json",
                success:function(data) {
                    var mati_id = <?php echo $mati->id; ?>;

                    if(data.nama_grup == null){
                        mati_id.options.title.text = "Grafik Ternak - Kematian ("+ data.tahun +")";
                    }
                    else{
                        mati_id.options.title.text = "Grafik Ternak - Kematian ("+ data.tahun +") - Grup Peternak '" + data.nama_grup + "'";
                    }
                    mati_id.data.datasets[0].data = data.jantan;
                    mati_id.data.datasets[1].data = data.betina;
                    mati_id.data.datasets[2].data = data.data;

                    mati_id.update();
                    $('#filter-modal').modal('hide');
                },
                error: function (jqXHR, textStatus, errorThrown) { 
                    console.log(jqXHR); 
                }
            });
            break;
    
        case "Jual":
            $.ajax({
                url: url_seg+"/grafik/jual",
                method: "GET", 
                data: {
                    grup_id: $(this).serialize().split('&')[0].split('=')[1],
                    tahun : $(this).serialize().split('&')[1].split('=')[1],
                },
                dataType: "json",
                success:function(data) {
                    var jual_id = <?php echo $jual->id; ?>;

                    if(data.nama_grup == null){
                        jual_id.options.title.text = "Grafik Ternak - Penjualan ("+ data.tahun +")";
                    }
                    else{
                        jual_id.options.title.text = "Grafik Ternak - Penjualan ("+ data.tahun +") - Grup Peternak '" + data.nama_grup + "'";
                    }
                    jual_id.data.datasets[0].data = data.jantan;
                    jual_id.data.datasets[1].data = data.betina;
                    jual_id.data.datasets[2].data = data.data;

                    jual_id.update();
                    $('#filter-modal').modal('hide');
                },
                error: function (jqXHR, textStatus, errorThrown) { 
                    console.log(jqXHR); 
                }
            });
            break;

        case "Kawin":
            $.ajax({
                url: url_seg+"/grafik/kawin",
                method: "GET", 
                data: {
                    grup_id: $(this).serialize().split('&')[0].split('=')[1],
                    tahun : $(this).serialize().split('&')[1].split('=')[1],
                },
                dataType: "json",
                success:function(data) {
                    var kawin_id = <?php echo $kawin->id; ?>;

                    if(data.nama_grup == null){
                        kawin_id.options.title.text = "Grafik Ternak - Perkawinan ("+ data.tahun +")";
                    }
                    else{
                        kawin_id.options.title.text = "Grafik Ternak - Perkawinan ("+ data.tahun +") - Grup Peternak '" + data.nama_grup + "'";
                    }
                    kawin_id.data.datasets[0].data = data.data;

                    kawin_id.update();
                    $('#filter-modal').modal('hide');
                },
                error: function (jqXHR, textStatus, errorThrown) { 
                    console.log(jqXHR); 
                }
            });
            break;

        default:
            break;
    }
}); 
</script>
@endpush