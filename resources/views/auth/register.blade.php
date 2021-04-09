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

    <div class="wrap-input100 validate-input m-b-26" data-validate="Name is required">
        <span class="label-input100">{{ __('Nama') }}</span>
        <input class="input100 @error('name') is-invalid @enderror" id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="nama">
        <span class="focus-input100"></span>

        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
        <span class="label-input100">{{ __('Username') }}</span>
        <input id="username" type="text" class="input100 @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="username">
        <span class="focus-input100"></span>

        @error('username')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="wrap-input100 validate-input m-b-26" data-validate="E-mail is required">
        <span class="label-input100">{{ __('Alamat Email') }}</span>
        <input class="input100  @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="email">
        <span class="focus-input100"></span>

        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
        <span class="label-input100">{{ __('Password') }}</span>
        <input class="input100 @error('password') is-invalid @enderror" id="password" type="password" name="password" required autocomplete="new-password" placeholder="minimal 8 karakter">
        <span class="focus-input100"></span>

        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="wrap-input100 validate-input m-b-18" data-validate = "Password Confirmation is required">
        <span class="label-input100">{{ __('Konfirmasi Password') }}</span>
        <input class="input100" id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="konfirmasi password">
        <span class="focus-input100"></span>
    </div>

    <div class="container-login100-form-btn">
        <button class="login100-form-btn">
            {{ __('Register') }}
        </button>
    </div>
</form>


<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-teal">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nama') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Alamat E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Konfirmasi Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection
