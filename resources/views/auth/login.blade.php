@extends('layouts.auth')

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
        <input class="input100  @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="E-mail">
        <span class="focus-input100"></span>

        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
        <span class="label-input100">Password</span>
        <input class="input100 @error('password') is-invalid @enderror" id="password" type="password" name="password" required autocomplete="current-password" placeholder="Password">
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
</form>


<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-teal">{{ __('Login') }}</div>

                <div class="card-body">
                    @if( session('failure') )
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert">×</button> 
                            {{ session('failure') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="input-group">
                                <span class="input-group-addon col-md-4 col-form-label text-md-right">
                                    <i class="material-icons">email</i>
                                </span>
                                <div class="form-line col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="E-mail">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="input-group">
                                <span class="input-group-addon col-md-4 col-form-label text-md-right">
                                    <i class="material-icons">lock</i>
                                </span>
                                <div class="form-line col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Ingat Saya') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Lupa Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection
