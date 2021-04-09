@extends('layouts.part')

@push('link')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="{{ asset('/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('/datatable/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush

@section('title')
<h2>LAPORAN</h2>
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
<li class="active"><i class="material-icons">archive</i> Laporan </li>
@endsection

@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Rentang Waktu</h2>
            </div>
            <div class="body">
                <!-- <form> -->
                    <!-- @csrf -->

                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">date_range</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="text" name="reportrange" id="reportrange" class="form-control" placeholder="Pilih tanggal...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <!-- </form> -->
            </div>
        </div>
    </div>
</div>

<!-- data laporan -->
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header row">
                <h2 class="col-md-9">
                    LAPORAN - DATA TERNAK 
                    <small>Laporan data ternak berdasarkan range waktu 
                        <span id="date-span">{{ $start }} sampai {{ $end }}</span>
                    </small>
                </h2>
                <div class="col-md-3" align="right">
                    <button id="dwd-btn" class="btn">
                        <i class="material-icons">file_download</i>
                        <span class="icon-name">Download Laporan</span>
                    </button>
                </div>
            </div>
            <div class="body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs tab-nav-right" role="tablist">
                    <li role="presentation" class="active"><a href="#lahir" data-toggle="tab">LAHIR</a></li>
                    <li role="presentation"><a href="#mati" data-toggle="tab">MATI</a></li>
                    <li role="presentation"><a href="#kawin" data-toggle="tab">KAWIN</a></li>
                    <li role="presentation"><a href="#sakit" data-toggle="tab">SAKIT</a></li>
                    <li role="presentation"><a href="#ada" data-toggle="tab">TOTAL ADA</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="lahir">
                        <h4 align="center"><b>Data Ternak Lahir</b></h4>
                        <div class="table-responsive">
                            <table id="lahir-table" class="table table-bordered table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Necktag</th>
                                        <th>ID Pemilik</th>
                                        <th>ID Peternakan</th>
                                        <th>ID Ras</th>
                                        <th>ID Kematian</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Bobot Lahir</th>
                                        <th>Pukul Lahir</th>
                                        <th>Lama di Kandungan</th>
                                        <th>Lama Laktasi</th>
                                        <th>Tanggal Lepas Sapih</th>
                                        <th>Blood</th>
                                        <th>Ayah</th>
                                        <th>Ibu</th>
                                        <th>Bobot Tubuh</th>
                                        <th>Panjang Tubuh</th>
                                        <th>Tinggi Tubuh</th>
                                        <th>Cacat Fisik</th>
                                        <th>Ciri Lain</th>
                                        <th>Status Ada</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="mati">
                        <h4 align="center"><b>Data Ternak Mati</b></h4>
                        <div class="table-responsive">
                            <table id="mati-table" class="table table-bordered table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Necktag</th>
                                        <th>ID Kematian</th>
                                        <th>Tanggal Mati</th>
                                        <th>Waktu Mati</th>
                                        <th>Penyebab</th>
                                        <th>Kondisi</th>
                                        <th>ID Pemilik</th>
                                        <th>ID Peternakan</th>
                                        <th>ID Ras</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Bobot Lahir</th>
                                        <th>Pukul Lahir</th>
                                        <th>Lama di Kandungan</th>
                                        <th>Lama Laktasi</th>
                                        <th>Tanggal Lepas Sapih</th>
                                        <th>Blood</th>
                                        <th>Ayah</th>
                                        <th>Ibu</th>
                                        <th>Bobot Tubuh</th>
                                        <th>Panjang Tubuh</th>
                                        <th>Tinggi Tubuh</th>
                                        <th>Cacat Fisik</th>
                                        <th>Ciri Lain</th>
                                        <th>Status Ada</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="kawin">
                        <h4 align="center"><b>Data Ternak Kawin</b></h4>
                        <div class="table-responsive">
                            <table id="kawin-table" class="table table-bordered table-condensed table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Necktag</th>
                                        <th>Necktag Pasangan</th>
                                        <th>Tanggal</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="sakit">
                        <h4 align="center"><b>Data Ternak Sakit</b></h4>
                        <div class="table-responsive">
                            <table id="sakit-table" class="table table-bordered table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Penyakit</th>
                                        <th>Necktag</th>
                                        <th>Tanggal Sakit</th>
                                        <th>Obat</th>
                                        <th>Lama Sakit</th>
                                        <th>Keterangan</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="ada">
                        <h4 align="center"><b>Data Ternak Ada</b></h4>
                        <div class="table-responsive">
                            <table id="ada-table" class="table table-bordered table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Necktag</th>
                                        <th>ID Pemilik</th>
                                        <th>ID Peternakan</th>
                                        <th>ID Ras</th>
                                        <th>ID Kematian</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Bobot Lahir</th>
                                        <th>Pukul Lahir</th>
                                        <th>Lama di Kandungan</th>
                                        <th>Lama Laktasi</th>
                                        <th>Tanggal Lepas Sapih</th>
                                        <th>Blood</th>
                                        <th>Ayah</th>
                                        <th>Ibu</th>
                                        <th>Bobot Tubuh</th>
                                        <th>Panjang Tubuh</th>
                                        <th>Tinggi Tubuh</th>
                                        <th>Cacat Fisik</th>
                                        <th>Ciri Lain</th>
                                        <th>Status Ada</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                    </tr>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{ asset('/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('/js/laporan.js') }}"></script>
@endpush