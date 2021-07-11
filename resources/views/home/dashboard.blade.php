@extends('layouts.part')

@push('link')
<link href="{{ asset('/css/search.css') }}" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet" />
<link href="{{ asset('/adminbsb/plugins/waitme/waitMe.css') }}" rel="stylesheet" />
<style>
    .tooltipBox {
    background-color: #666666;
    color: white;
    border-radius: 4px;
    padding: 4px;
    }
    .my-legend .legend-title {
    text-align: left;
    margin-bottom: 5px;
    font-weight: bold;
    font-size: 90%;
    }
  .my-legend .legend-scale ul {
    margin: 0;
    margin-bottom: 5px;
    padding: 0;
    float: left;
    list-style: none;
    }
  .my-legend .legend-scale ul li {
    font-size: 80%;
    list-style: none;
    margin-left: 0;
    line-height: 18px;
    margin-bottom: 2px;
    }
  .my-legend ul.legend-labels li span {
    display: block;
    float: left;
    height: 16px;
    width: 30px;
    margin-right: 5px;
    margin-left: 0;
    border: 1px solid #999;
    }
</style>
@endpush

@section('title')
<h2>DASHBOARD</h2>
@endsection

@section('breadcrumb')
<li class="active"><i class="material-icons">home</i> Home</li>
@endsection

@section('content')
<!-- top tiles -->
<div class="row clearfix">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box-3 bg-orange hover-expand-effect">
            <div class="icon">
                <i class="material-icons">all_inbox</i>
            </div>
            <div class="content">
                <div class="text">TOTAL DATA TERNAK</div>
                <div class="number count-to" data-from="0" data-to="{{ $total_ternak }}" data-speed="100" data-fresh-interval="20"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box-3 bg-green hover-expand-effect">
            <div class="icon">
                <i class="material-icons">person_add</i>
            </div>
            <div class="content">
                <div class="text">KELAHIRAN BARU</div>
                <div class="number count-to" data-from="0" data-to="{{ $kelahiran_baru->count }}" data-speed="20" data-fresh-interval="20"></div>
                <div class="text2"><small>*30 hari terakhir</small></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box-3 bg-cyan hover-expand-effect">
            <div class="icon">
                <i class="material-icons">supervisor_account</i>
            </div>
            <div class="content">
                <div class="text">PERKAWINAN BARU</div>
                <div class="number count-to" data-from="0" data-to="{{ $perkawinan_baru->count }}" data-speed="20" data-fresh-interval="20"></div>
                <div class="text2"><small>*30 hari terakhir</small></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box-3 bg-indigo hover-expand-effect">
            <div class="icon">
                <i class="material-icons">person_add_disabled</i>
            </div>
            <div class="content">
                <div class="text">KEMATIAN BARU</div>
                <div class="number count-to" data-from="0" data-to="{{ $kematian_baru->count }}" data-speed="20" data-fresh-interval="20"></div>
                <div class="text2"><small>*30 hari terakhir</small></div>
            </div>
        </div>
    </div>
</div>
<!-- /top tiles -->

<!-- search -->
<div class="s130">
    <form id="search_form">
        <div class="inner-form">
            <div class="input-field first-wrap">
                <div class="svg-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path>
                    </svg>
                </div>
                <input id="necktag" name="necktag" type="text" placeholder="scan necktag barcode atau ketikkan necktag" />
            </div>
            <div class="input-field second-wrap">
                <input type="submit" class="btn-search" name="action_button" id="action_button" value="SEARCH">
            </div>
        </div>
    </form>
</div>
<!-- /search -->

<!-- result -->
<div id="exist">
    <div class="block-header">
        <h2 id="title"></h2>
    </div>
    <div class="row">
        <div class="col-lg-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <!-- inst -->
                    <div align="center">
                        <table class="table table-bordered" style="width: 60%;">
                            <tr align="center">
                                <td style="width: 50%;"><b>Jenis Kelamin</b></td>
                                <td id="jk" style="width: 50%;"></td>
                            </tr>
                            <tr align="center">
                                <td style="width: 50%;"><b>Ras</b></td>
                                <td id="ras" style="width: 50%;"></td>
                            </tr>
                            <tr align="center">
                                <td style="width: 50%;"><b>Tanggal Lahir</b></td>
                                <td id="tgl_lahir" style="width: 50%;"></td>
                            </tr>
                            <tr align="center">
                                <td style="width: 50%;"><b>Pemilik</b></td>
                                <td id="pemilik" style="width: 50%;"></td>
                            </tr>
                            <tr align="center">
                                <td style="width: 50%;"><b>Peternak</b></td>
                                <td id="peternak" style="width: 50%;"></td>
                            </tr>
                        </table>
                    </div>

                    <!-- family chart -->
                    <div class="row">
                        <div class="col-lg-10 col-xs-10">
                            <div id="chartDiv" style="max-width: 740px; height: 400px; margin: 0px auto"></div>
                        </div>
                        <div class="col-lg-2 col-xs-2" style="height: 400px; padding: 0px">
                            <div class='my-legend'>
                                <div class='legend-title'>Keterangan</div>
                                <div class='legend-scale'>
                                    <ul class='legend-labels'>
                                        <li><span style='background: red;'></span>Betina</li>
                                        <li><span style='background: blue;'></span>Jantan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="text-center" id="not-exist"></div>
<br>
<!-- old version -->
<!--
<div id="search_result" style="display: none;">
    <div class="block-header">
        <h2>
            Detail Kambing
        </h2>
    </div>
    <div class="row">
        <div class="col-lg-12 col-xs-12">
            <div class="card">
                <div class="body">
                    
                    <div id="not-exist"></div>

                    <div id="exist">
                        // inst
                        <div align="center">
                            <table class="table table-bordered" style="width: 60%;">
                                <tr align="center">
                                    <td style="width: 50%;"><b>Jenis Kelamin</b></td>
                                    <td id="inst1" style="width: 50%;"></td>
                                </tr>
                                <tr align="center">
                                    <td style="width: 50%;"><b>Ras</b></td>
                                    <td id="inst2" style="width: 50%;"></td>
                                </tr>
                                <tr align="center">
                                    <td style="width: 50%;"><b>Tanggal Lahir</b></td>
                                    <td id="inst3" style="width: 50%;"></td>
                                </tr>
                                <tr align="center">
                                    <td style="width: 50%;"><b>Blood</b></td>
                                    <td id="inst4" style="width: 50%;"></td>
                                </tr>
                                <tr align="center">
                                    <td style="width: 50%;"><b>Peternakan</b></td>
                                    <td id="inst5" style="width: 50%;"></td>
                                </tr>
                            </table>
                        </div>

                        <br>

                        // parents
                        <div>
                            <p align="center" style="font-weight: bold; font-size: 20px;">Orang Tua</p>
                            <span id="span-parent" align="center"></span>
                            <table class="table" id="t-parent">
                                <tr>
                                    <th>Necktag</th> 
                                    <th>Jenis Kelamin</th>
                                    <th>Ras</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Blood</th>
                                    <th>Peternakan</th>
                                    <th>Ayah</th>
                                    <th>Ibu</th>
                                </tr>
                                <tr>
                                    <td id="pr01"></td>
                                    <td id="pr02"></td>
                                    <td id="pr03"></td>
                                    <td id="pr04"></td>
                                    <td id="pr05"></td>
                                    <td id="pr06"></td>
                                    <td id="pr07"></td>
                                    <td id="pr08"></td>
                                </tr>
                                <tr>
                                    <td id="pr11"></td>
                                    <td id="pr12"></td>
                                    <td id="pr13"></td>
                                    <td id="pr14"></td>
                                    <td id="pr15"></td>
                                    <td id="pr16"></td>
                                    <td id="pr17"></td>
                                    <td id="pr18"></td>
                                </tr>
                            </table>
                        </div>
                        <br>
                        // siblings
                        <div>
                            <p align="center" style="font-weight: bold; font-size: 20px;">Saudara</p>
                            <span id="span-sibling" align="center"></span>
                            <table class="table" id="t-sibling"></table>
                        </div>
                        <br>
                        // children
                        <div>
                            <p align="center" style="font-weight: bold; font-size: 20px;">Anak</p>
                            <span id="span-child" align="center"></span>
                            <table class="table" id="t-child"></table>
                        </div>
                        <br>
                        // grandparents
                        <div>
                            <p align="center" style="font-weight: bold; font-size: 20px;">Kakek - Nenek</p>
                            <span id="span-gp" align="center"></span>
                            <table class="table" id="t-gp"></table>
                        </div>
                        <br>
                        // grandchildren
                        <div>
                            <p align="center" style="font-weight: bold; font-size: 20px;">Cucu</p>
                            <span id="span-gc" align="center"></span>
                            <table class="table" id="t-gc"></table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
-->
<!-- /old version -->
<!-- /result -->
@endsection

@push('script')
<script src="https://code.jscharting.com/latest/jscharting.js"></script>
<script type="text/javascript" src="https://code.jscharting.com/latest/modules/types.js"></script>
<script type="text/javascript" src="https://code.jscharting.com/latest/modules/toolbar.js"></script>
<script src="{{ asset('/js/dashboard.js') }}"></script>
<script src="{{ asset('/adminbsb/plugins/waitme/waitMe.js') }}"></script>
<script src="{{ asset('/adminbsb/js/pages/cards/colored.js') }}"></script>
@endpush