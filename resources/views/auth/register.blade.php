@extends('layouts.auth')

@section('content')
<div class="login100-form-title" style="background-image: url(images/siternak-cover.png);">
    <span class="login100-form-title-1">
        Register
    </span>
</div>

@if( session('failure') )
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">×</button> 
        {{ session('failure') }}
    </div>
@endif

<form class="login100-form validate-form" method="POST" action="{{ route('register') }}">
    @csrf

    <div class="wrap-input100 validate-input m-b-26" data-validate="KTP is required">
        <span class="label-input100">{{ __('No KTP') }}<span class="text-danger">*</span></span>
        <input class="input100 @error('ktp') is-invalid @enderror" id="ktp" type="text" name="ktp" value="{{ old('ktp') }}" required autocomplete="ktp" autofocus placeholder="masukkan no KTP">
        <span class="focus-input100"></span>

        @error('ktp')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="wrap-input100 validate-input m-b-26" data-validate="Name is required">
        <span class="label-input100">{{ __('Nama') }}<span class="text-danger">*</span></span>
        <input class="input100 @error('name') is-invalid @enderror" id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="masukkan nama">
        <span class="focus-input100"></span>

        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
        <span class="label-input100">{{ __('Username') }}<span class="text-danger">*</span></span>
        <input id="username" type="text" class="input100 @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="masukkan username">
        <span class="focus-input100"></span>

        @error('username')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="wrap-input100 validate-input m-b-26" data-validate="E-mail is required">
        <span class="label-input100">{{ __('Alamat Email') }}<span class="text-danger">*</span></span>
        <input class="input100  @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="masukkan email">
        <span class="focus-input100"></span>

        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
        <span class="label-input100">{{ __('Password') }}<span class="text-danger">*</span></span>
        <input class="input100 @error('password') is-invalid @enderror" id="password" type="password" name="password" required autocomplete="new-password" placeholder="minimal 8 karakter">
        <span class="focus-input100"></span>

        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="wrap-input100 validate-input m-b-18" data-validate = "Password Confirmation is required">
        <span class="label-input100">{{ __('Konfirmasi Password') }}<span class="text-danger">*</span></span>
        <input class="input100" id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="konfirmasi password">
        <span class="focus-input100"></span>
    </div>

    <div class="wrap-input100 validate-input m-b-18" data-validate = "Grup Peternak is required">
        <span class="label-input100">{{ __('Grup Peternak') }}<span class="text-danger">*</span></span>
        <select class="input100 form-control js-select-search @error('grup_peternak') is-invalid @enderror" id="grup_peternak" name="grup_peternak" required>
            <option></option>
            @forelse($grupPeternak as $grup)
                <option value="{{ $grup->id }}">{{ $grup->nama_grup }} - {{ $grup->kecamatan }}, {{ $grup->kab_kota }}, {{ $grup->provinsi }}</option>
            @empty
                Tidak ada Grup Peternak
            @endforelse
        </select>
        <span class="focus-input100"></span>

        @error('grup_peternak')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="container-login100-form-btn">
        <button class="login100-form-btn">
            {{ __('Register') }}
        </button>
    </div>
    <div class="flex-sb-m w-full p-t-30">
        <div>
            <p class="txt1">Sudah punya akun? <a href="{{ route('login') }}">LOGIN</a></p>
        </div>
    </div>
</form>

@endsection
