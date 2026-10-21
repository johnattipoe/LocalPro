<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('logo/localpro-mark.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#f5f8fc">
    <title>Reset Password | LocalPro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/ts/app/app.ts'])
</head>
<body class="auth-page reset-password-page">
<main class="reset-password-stage">
    <div class="reset-password-card">
<aside class="reset-password-aside" aria-label="Password security tips">
            <img class="auth-reference-image" src="{{ asset('images/auth/reset-security.jpg') }}" alt="Service professional helping a customer with secure LocalPro booking">
            <a class="reset-password-brand" href="{{ route('home') }}" aria-label="LocalPro homepage">
                <img src="{{ asset('logo/localpro-mark.png') }}" alt="">
                <span>LocalPro</span>
            </a>
            <div class="reset-password-aside-copy">
                <span class="reset-password-eyebrow">ACCOUNT SECURITY</span>
                <h2>Set a New Password</h2>
                <p>Your new password must be different from your previous password.</p>
            </div>
            <div class="reset-security-illustration" aria-hidden="true">
                <span class="reset-spark reset-spark-one">✳</span>
                <span class="reset-spark reset-spark-two">✳</span>
                <span class="reset-lock-halo"><i data-lucide="lock-keyhole"></i></span>
                <span class="reset-toolbox"><i></i><i></i><i></i><b></b></span>
            </div>
            <p class="reset-password-aside-note">Keep your account secure<br>with a strong password.</p>
            <span class="reset-password-region">Local services. Trusted connections.</span>
        </aside>

<section class="reset-password-form-panel" aria-labelledby="reset-password-title">
            @include('components.language-picker.language-picker', ['id' => 'auth-language', 'class' => 'auth-language-picker'])
            <a class="auth-home-arrow" href="{{ route('home') }}" aria-label="{{ __('ui.back_to_website') }}" title="{{ __('ui.back_to_website') }}"><i data-lucide="arrow-left" aria-hidden="true"></i><span>{{ __('ui.back_to_website') }}</span></a>
            <div class="reset-form-content">
                <span class="reset-form-icon" aria-hidden="true"><i data-lucide="shield-check"></i></span>
                <div class="reset-form-heading">
                    <span class="reset-password-eyebrow">ACCOUNT RECOVERY</span>
                    <h1 id="reset-password-title">{{ __('ui.reset_password') }}</h1>
                    <p>Choose a strong password you have not used before.</p>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="reset-password-form">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ old('email', $email) }}">

                    @if($errors->any())
                        <div class="reset-form-error" role="alert"><i data-lucide="circle-alert" aria-hidden="true"></i><span>{{ $errors->first() }}</span></div>
                    @endif

                    <div class="reset-field">
                        <label for="password">{{ __('ui.password') }}</label>
                        <div class="reset-input-wrap">
                            <i data-lucide="lock-keyhole" aria-hidden="true"></i>
                            <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password" placeholder="Enter your new password" aria-describedby="password-hint">
                            <button class="reset-password-toggle" type="button" data-password-toggle="password" aria-label="Show new password" aria-pressed="false"><i data-lucide="eye" aria-hidden="true"></i></button>
                        </div>
                        <small id="password-hint" class="reset-field-hint">Use at least 8 characters.</small>
                    </div>

                    <div class="reset-field">
                        <label for="password_confirmation">{{ __('ui.confirm_password') }}</label>
                        <div class="reset-input-wrap">
                            <i data-lucide="lock-keyhole" aria-hidden="true"></i>
                            <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" placeholder="Confirm your new password">
                            <button class="reset-password-toggle" type="button" data-password-toggle="password_confirmation" aria-label="Show confirmed password" aria-pressed="false"><i data-lucide="eye" aria-hidden="true"></i></button>
                        </div>
                    </div>

                    <button class="reset-password-submit" type="submit">{{ __('ui.reset_password') }} <i data-lucide="arrow-right" aria-hidden="true"></i></button>
                </form>

                <a class="reset-back-to-login" href="{{ route('login') }}"><i data-lucide="arrow-left" aria-hidden="true"></i> Back to login</a>
            </div>
        </section>
    </div>
</main>
@include('components.cookie-consent.cookie-consent')
</body>
</html>
