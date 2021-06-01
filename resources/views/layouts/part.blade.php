<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
	<title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('/images/siternak.png') }}"/>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css"/>
    <!-- Favicon-->
    <!-- <link rel="icon" href="{{ asset('/adminbsb/favicon.ico') }}" type="image/x-icon"> -->
    <!-- Bootstrap Core Css -->
    <!-- <link href="{{ asset('/adminbsb/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet"/> -->
    <link href="{{ asset('/adminbsb/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet"/>
    
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- <link href="{{ asset('/bootstrap/css/bootstrap.css') }}" rel="stylesheet"/> -->
    <!-- <link href="{{ asset('/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"> -->
    <!-- Waves Effect Css -->
    <link href="{{ asset('/adminbsb/plugins/node-waves/waves.css') }}" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="{{ asset('/adminbsb/plugins/animate-css/animate.css') }}" rel="stylesheet" />
    <!-- bootstrap-progressbar -->
    <link href="{{ asset('/adminbsb/plugins/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <!-- <link href="{{ asset('/adminbsb/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet"/> -->
    <!-- Custom Css -->
    <link href="{{ asset('/adminbsb/css/style.css') }}" rel="stylesheet"/>
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{ asset('/adminbsb/css/themes/all-themes.css') }}" rel="stylesheet" />

    @stack('link')

</head>
<body class="theme-teal">
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->

	<!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                @can('isAdmin')
                <a class="navbar-brand" href="{{ route('admin') }}"> {{ config('app.name', 'Laravel') }} - Sistem Informasi Ternak </a>
                @elsecan('isPeternak')
                <a class="navbar-brand" href="{{ route('peternak') }}"> {{ config('app.name', 'Laravel') }} - Sistem Informasi Ternak </a>
                @elsecan('isKetua')
                <a class="navbar-brand" href="{{ route('ketua-grup') }}"> {{ config('app.name', 'Laravel') }} - Sistem Informasi Ternak </a>
                @endcan
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- profile -->
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">account_circle</i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">{{ Auth::user()->name }}</li>
                            <li class="body" style="height: 100px;">
                                <ul class="menu" style="list-style-type:none;">
                                    <li>
                                        @can('isAdmin')
                                        <a href="{{ route('admin.profile') }}">
                                        @elsecan('isPeternak')
                                        <a href="{{ route('peternak.profile') }}">
                                        @elsecan('isKetua')
                                        <a href="{{ route('ketua-grup.profile') }}">
                                        @endcan
                                            <div class="icon-circle bg-cyan">
                                                <i class="material-icons">person</i>
                                            </div>
                                            <div class="menu-info" style="top: -3px;">
                                                <h4>Profil</h4>
                                            </div>
                                        </a>
                                    </li>
                                    <!-- <li>
                                        <a href="{{ route('home') }}">
                                            <div class="icon-circle bg-cyan">
                                                <i class="material-icons">dashboard</i>
                                            </div>
                                            <div class="menu-info" style="top: -3px;">
                                                <h4>Dashboard Utama</h4>
                                            </div>
                                        </a>
                                    </li> -->
                                    <li>
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <div class="icon-circle bg-cyan">
                                                <i class="material-icons">input</i>
                                            </div>
                                            <div class="menu-info" style="top: -3px;">
                                                <h4>{{ __('Logout') }}</h4>
                                                
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!-- /profile -->
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->

    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="{{ asset('adminbsb/images/user.png') }}" width="36" height="36" alt="User" />
                </div>
                <div class="info-container">
                    <div class="email">Selamat datang, {{ Auth::user()->role }}</div>
                    <div class="name">{{ Auth::user()->name }}</div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li>
                        @can('isAdmin')
                        <a href="{{ route('admin') }}">
                        @elsecan('isPeternak')
                        <a href="{{ route('peternak') }}">
                        @elsecan('isKetua')
                        <a href="{{ route('ketua-grup') }}">
                        @endcan 
                            <i class="material-icons">home</i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    @can('isAdmin')
                    <li>
                        <a href="{{ route('admin.peternak.index') }}">
                            <i class="material-icons">people</i>
                            <span>Peternak</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.verifikasi') }}">
                            <!-- <i class="material-icons">how_to_reg</i> -->
                            <i class="material-icons">verified_user</i>
                            <span>Peternak perlu verifikasi</span>
                        </a>
                    </li>
                    @elsecan('isKetua')
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">groups</i>
                            <span>Grup Peternak Saya</span>
                        </a>
                        <ul class="ml-menu">
                            <li><a href="{{ route('ketua-grup.grup-saya.peternak') }}">Peternak</a></li>
                            <li><a href="">Ternak</a></li>
                            <li><a href="">Riwayat Penyakit</a></li>
                            <li><a href="">Perkembangan</a></li>
                        </ul>
                    </li>
                    @endcan
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">widgets</i>
                            <span>Data</span>
                        </a>
                        <ul class="ml-menu">
                            @can('isAdmin')
                            <li><a href="{{ route('admin.grup-peternak.index') }}">Grup Peternak</a></li>
                            <li><a href="{{ route('admin.kematian.index') }}">Kematian</a></li>
                            <li><a href="{{ route('admin.pemilik.index') }}">Pemilik</a></li>
                            <li><a href="{{ route('admin.penjualan.index') }}">Penjualan</a></li>
                            <li><a href="{{ route('admin.perkawinan.index') }}">Perkawinan</a></li>
                            <li><a href="{{ route('admin.perkembangan.index') }}">Perkembangan</a></li>
                            <li><a href="{{ route('admin.ras.index') }}">Ras</a></li>
                            <li><a href="{{ route('admin.riwayat.index') }}">Riwayat Penyakit</a></li>
                            <li><a href="{{ route('admin.ternak.index') }}">Ternak</a></li>
                            @elsecan('isPeternak')
                            <li><a href="{{ route('peternak.kematian.index') }}">Kematian</a></li>
                            <li><a href="{{ route('peternak.pemilik.index') }}">Pemilik</a></li>
                            <li><a href="{{ route('peternak.penjualan.index') }}">Penjualan</a></li>
                            <li><a href="{{ route('peternak.perkawinan.index') }}">Perkawinan</a></li>
                            <li><a href="{{ route('peternak.perkembangan.index') }}">Perkembangan</a></li>
                            <li><a href="{{ route('peternak.ras.index') }}">Ras</a></li>
                            <li><a href="{{ route('peternak.riwayat.index') }}">Riwayat Penyakit</a></li>
                            <li><a href="{{ route('peternak.ternak.index') }}">Ternak</a></li>
                            @elsecan('isKetua')
                            <li><a href="{{ route('ketua-grup.kematian.index') }}">Kematian</a></li>
                            <li><a href="{{ route('ketua-grup.pemilik.index') }}">Pemilik</a></li>
                            <li><a href="{{ route('ketua-grup.penjualan.index') }}">Penjualan</a></li>
                            <li><a href="{{ route('ketua-grup.perkawinan.index') }}">Perkawinan</a></li>
                            <li><a href="{{ route('ketua-grup.perkembangan.index') }}">Perkembangan</a></li>
                            <li><a href="{{ route('ketua-grup.ras.index') }}">Ras</a></li>
                            <li><a href="{{ route('ketua-grup.riwayat.index') }}">Riwayat Penyakit</a></li>
                            <li><a href="{{ route('ketua-grup.ternak.index') }}">Ternak</a></li>
                            @endcan
                            
                        </ul>
                    </li>
                    <li>
                        @can('isAdmin')
                        <a href="{{ route('admin.barcode') }}">
                        @elsecan('isPeternak')
                        <a href="{{ route('peternak.barcode') }}">
                        @elsecan('isKetua')
                        <a href="{{ route('ketua-grup.barcode') }}">
                        @endcan
                            <i class="material-icons">view_week</i>
                            <span>Barcode</span>
                        </a>
                    </li>
                    <li>
                        @can('isAdmin')
                        <a href="{{ route('admin.match') }}">
                        @elsecan('isPeternak')
                        <a href="{{ route('peternak.match') }}">
                        @elsecan('isKetua')
                        <a href="{{ route('ketua-grup.match') }}">
                        @endcan
                            <i class="material-icons">compare</i>
                            <span>Perkawinan</span>
                        </a>
                    </li>
                    <li>
                        @can('isAdmin')
                        <a href="{{ route('admin.grafik') }}">
                        @elsecan('isPeternak')
                        <a href="{{ route('peternak.grafik') }}">
                        @elsecan('isKetua')
                        <a href="{{ route('ketua-grup.grafik') }}">
                        @endcan
                            <i class="material-icons">pie_chart</i>
                            <span>Grafik</span>
                        </a>
                    </li>
                    <li>
                        @can('isAdmin')
                        <a href="{{ route('admin.laporan') }}">
                        @elsecan('isPeternak')
                        <a href="{{ route('peternak.laporan') }}">
                        @elsecan('isKetua')
                        <a href="{{ route('ketua-grup.laporan') }}">
                        @endcan
                            <i class="material-icons">archive</i>
                            <span>Laporan</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2020 <a href="javascript:void(0);"> Sistem Informasi Ternak - SITERNAK </a>.
                </div>
                <div class="version">
                    <b>Version: </b> 1.0
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>


    <!-- content -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                @yield('title')
            </div>
            <!-- breadcrumb -->
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-teal align-right">
                    @yield('breadcrumb')
                </ol>
            </div>
            <!-- /breadcrumb -->

            @yield('content')
        
        </div>
    </section>
    <!-- /content -->


    <!-- Jquery Core Js -->
    <script src="{{ asset('/adminbsb/plugins/jquery/jquery.min.js') }}"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
    <!-- Slimscroll Plugin Js -->
    <script src="{{ asset('/adminbsb/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="{{ asset('/adminbsb/plugins/node-waves/waves.js') }}"></script>
    <!-- Jquery CountTo Plugin Js -->
    <script src="{{ asset('/adminbsb/plugins/jquery-countto/jquery.countTo.js') }}"></script>
    <!-- Bootstrap Core Js -->
    <script src="{{ asset('/adminbsb/plugins/bootstrap/js/bootstrap.js') }}"></script>
    <!-- <script src="{{ asset('/bootstrap/js/bootstrap.js') }}"></script> -->
    <!-- Select Plugin Js -->
    <script src="{{ asset('/adminbsb/plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>

    <script src="{{ asset('/adminbsb/plugins/momentjs/moment.js') }}"></script>
    
    <!-- Custom Js -->
    <script src="{{ asset('/adminbsb/js/admin.js') }}"></script>
    <script src="{{ asset('/adminbsb/js/pages/index.js') }}"></script>
    <!-- Demo Js -->
    <script src="{{ asset('/adminbsb/js/demo.js') }}"></script>

    @stack('script')

</body>
</html>