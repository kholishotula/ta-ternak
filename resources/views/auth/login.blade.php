@extends('layouts.auth')

@push('styles')
<!-- Sweetalert Css -->
<link href="{{ asset('/adminbsb/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="login100-form-title" style="background-image: url(images/siternak-cover.png);">
    <span class="login100-form-title-1">
        Log In
    </span>
</div>

@if( session('failure') )
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">×</button> 
        {{ session('failure') }}
    </div>
@endif

<form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
    @csrf

    <div class="wrap-input100 validate-input m-b-26" data-validate="E-mail is required">
        <span class="label-input100">Email</span>
        <input class="input100  @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Masukkan E-mail">
        <span class="focus-input100"></span>

        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
        <span class="label-input100">Password</span>
        <input class="input100 @error('password') is-invalid @enderror" id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan Password">
        <span class="focus-input100"></span>

        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="flex-sb-m w-full p-b-30">
        <div class="contact100-form-checkbox">
            <input class="input-checkbox100" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="label-checkbox100" for="remember">
                {{ __('Ingat Saya') }}
            </label>
        </div>

        <div>
            @if (Route::has('password.request'))
                <a class="txt1" href="{{ route('password.request') }}">
                    {{ __('Lupa Password?') }}
                </a>
            @endif
        </div>
    </div>

    <div class="container-login100-form-btn">
        <button class="login100-form-btn">
            Login
        </button>
    </div>

    <div class="flex-sb-m w-full p-t-30">
        <div>
            <p class="txt1">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<!-- SweetAlert Plugin Js -->
<script src="{{ asset('/adminbsb/plugins/sweetalert/sweetalert.min.js') }}"></script>
@if(Session::has('success'))
<script>
    swal("Success!", "{{ Session::get('success') }}", "success");
</script>
@endif
@endpush