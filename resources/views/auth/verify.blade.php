@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('تأیید نشانی رایانامه') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('پیوند تازهٔ تأیید به رایانامهٔ شما ارسال شد.') }}
                        </div>
                    @endif

                    {{ __('پیش از ادامه، رایانامهٔ خود را برای پیوند تأیید بررسی کنید.') }}
                    {{ __('اگر رایانامه را دریافت نکرده‌اید') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('برای دریافت پیوند تازه اینجا کلیک کنید') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
