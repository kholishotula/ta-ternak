<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Aplikasi Web SITERNAK. Abdi masyarakat LAZ AL-Azhar.">

	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('/images/siternak.png') }}"/>

	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/auth/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/auth/font-awesome-4.7.0/css/font-awesome.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/auth/Linearicons-Free-v1.0.0/icon-font.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/auth/animate/animate.css') }}">

	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/auth/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/auth/main.css') }}">

	<script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet"/>

	@stack('styles')
	
</head>
<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				@yield('content')
			</div>
		</div>
	</div>


	<script src="{{ asset('vendor/auth/jquery/jquery-3.2.1.min.js') }}"></script>

	<script src="{{ asset('vendor/auth/bootstrap/js/popper.js') }}"></script>
	<script src="{{ asset('vendor/auth/bootstrap/js/bootstrap.min.js') }}"></script>

	<script src="{{ asset('vendor/auth/main.js') }}"></script>

</body>

@stack('scripts')

</html>
