<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('logo/localpro-mark.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#f3f7fb">
    <title>Forgot Password | LocalPro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/ts/app/app.ts'])
</head>
<body class="auth-page reset-password-page forgot-password-page">
<main class="reset-password-stage">
    <div class="reset-password-card">
<aside class="reset-password-aside" aria-label="Account recovery information">
            <img class="auth-reference-image" src="{{ asset('images/auth/forgot-home-mail.jpg') }}" alt="Local home and mailbox in the Volta Region">
            <a class="reset-password-brand" href="{{ route('home') }}" aria-label="LocalPro homepage">
                <img src="{{ asset('logo/localpro-mark.png') }}" alt="">
                <span>LocalPro</span>
            </a>
            <div class="reset-password-aside-copy">
                <span class="reset-password-eyebrow">ACCOUNT RECOVERY</span>
                <h2>Forgot Your Password?</h2>
                <p>No worries! Enter your email address and we’ll send a link to reset your password.</p>
            </div>
            <div class="reset-security-illustration" aria-hidden="true">
                <span class="reset-spark reset-spark-one">✳</span>
                <span class="reset-spark reset-spark-two">✳</span>
                <span class="reset-lock-halo"><i data-lucide="lock-keyhole"></i></span>
                <span class="reset-toolbox"><i></i><i></i><i></i><b></b></span>
            </div>
            <span class="forgot-house-scene" aria-hidden="true"><i></i><b></b><em></em></span>
            <p class="reset-password-aside-note">We’ll help you get back<br>into your account.</p>
            <span class="reset-password-region">Local services. Trusted connections.</span>
        </aside>

<section class="reset-password-form-panel" aria-labelledby="forgot-password-title">
            @include('components.language-picker.language-picker', ['id' => 'auth-language', 'class' => 'auth-language-picker'])
            <a class="auth-home-arrow" href="{{ route('home') }}" aria-label="{{ __('ui.back_to_website') }}" title="{{ __('ui.back_to_website') }}"><i data-lucide="arrow-left" aria-hidden="true"></i><span>{{ __('ui.back_to_website') }}</span></a>
            <div class="reset-form-content">
                <span class="reset-form-icon" aria-hidden="true"><i data-lucide="mail"></i></span>
                <div class="reset-form-heading">
                    <span class="reset-password-eyebrow">SECURE ACCOUNT RECOVERY</span>
                    <h1 id="forgot-password-title">{{ __('ui.forgot_password') }}</h1>
                    <p>Enter the email address linked to your LocalPro account.</p>
                </div>

                @if(session('status'))
                    <div class="forgot-status-message" role="status" aria-live="polite">
                        <i data-lucide="check" aria-hidden="true"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form class="reset-password-form" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="reset-field">
                        <label for="email">{{ __('ui.email_address') }}</label>
                        <div class="reset-input-wrap">
                            <i data-lucide="mail" aria-hidden="true"></i>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="180" autocomplete="email" placeholder="you@example.com" autofocus>
                        </div>
                        @error('email')<small class="reset-email-error" role="alert">{{ $message }}</small>@enderror
                    </div>
                    <button class="reset-password-submit" type="submit">{{ __('ui.send_reset_link') }} <i data-lucide="arrow-right" aria-hidden="true"></i></button>
                </form>

                <p class="forgot-signin-prompt">Remember your password? <a href="{{ route('login') }}">{{ __('ui.sign_in') }}</a></p>
            </div>
        </section>
    </div>
</main>
@if(session('status'))
    @include('components.success-toast.success-toast', [
        'title' => config('mail.default') === 'log' ? 'Reset link saved locally' : 'Check your inbox',
        'message' => session('status'),
    ])
@endif
@include('components.cookie-consent.cookie-consent')
</body>
</html>
