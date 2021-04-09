@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-teal">{{ __('Verifikasi Alamat E-mail Anda') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Tautan verifikasi baru telah dikirimkan ke email Anda.') }}
                        </div>
                    @endif
 
                    {{ __('Sebelum melanjutkan, silakan periksa email Anda untuk tautan verifikasi.') }}
                    {{ __('Jika Anda tidak menerima email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('klik disini untuk request kembali') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
