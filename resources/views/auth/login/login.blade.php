<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('logo/localpro-mark.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#f3f7fb">
    <title>Sign In | LocalPro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/ts/app/app.ts'])
</head>
<body class="auth-page login-screen">
<main class="auth-stage login-stage">
    <div class="login-card">
<aside class="login-welcome" aria-label="LocalPro benefits">
            <img class="auth-reference-image" src="{{ asset('images/auth/login-professional.jpg') }}" alt="Verified LocalPro service professional">
            <a class="login-brand" href="{{ route('home') }}" aria-label="LocalPro homepage">
                <img src="{{ asset('logo/localpro-mark.png') }}" alt="">
                <span>LocalPro</span>
            </a>
            <div class="login-welcome-copy">
                <span class="login-eyebrow">{{ __('ui.welcome') }}</span>
                <h2>{{ __('ui.trusted_professionals') }}</h2>
                <ul>
                    <li><i data-lucide="check" aria-hidden="true"></i> {{ __('ui.verified_professionals') }}</li>
                    <li><i data-lucide="check" aria-hidden="true"></i> {{ __('ui.secure_payments') }}</li>
                    <li><i data-lucide="check" aria-hidden="true"></i> {{ __('ui.reliable_service') }}</li>
                </ul>
            </div>
            <div class="login-scene" aria-hidden="true">
                <span class="login-van"><i></i><b></b><em></em></span>
                <span class="login-worker"><i></i><b></b><em></em><strong></strong></span>
                <span class="login-plant"><i></i><b></b></span>
            </div>
            <span class="login-region">Ho and the Volta Region, Ghana</span>
        </aside>

<section class="login-form-panel" aria-labelledby="login-title">
            @include('components.language-picker.language-picker', ['id' => 'auth-language', 'class' => 'auth-language-picker'])
            <a class="auth-home-arrow" href="{{ route('home') }}" aria-label="{{ __('ui.back_to_website') }}" title="{{ __('ui.back_to_website') }}"><i data-lucide="arrow-left" aria-hidden="true"></i><span>{{ __('ui.back_to_website') }}</span></a>
            <header class="login-heading">
                <span class="login-eyebrow">YOUR LOCAL MARKETPLACE</span>
                <h1 id="login-title">Welcome Back</h1>
                <p>{{ __('ui.sign_in') }}</p>
            </header>

            <form method="POST" action="{{ route('login.store') }}" class="login-form">
                @csrf
                <div class="login-field">
                    <label for="email">{{ __('ui.email_address') }}</label>
                    <div class="login-input"><i data-lucide="mail" aria-hidden="true"></i><input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com" autofocus></div>
                    @error('email')<small class="login-error">{{ $message }}</small>@enderror
                </div>
                <div class="login-field">
                    <label for="password">{{ __('ui.password') }}</label>
                    <div class="login-input"><i data-lucide="lock-keyhole" aria-hidden="true"></i><input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Enter your password"></div>
                    @error('password')<small class="login-error">{{ $message }}</small>@enderror
                </div>
                <div class="login-options">
                    <label><input type="checkbox" name="remember" value="1" @checked(old('remember'))> {{ __('ui.remember_me') }}</label>
                    <a href="{{ route('password.request') }}">{{ __('ui.forgot_password') }}</a>
                </div>
                <button class="login-submit" type="submit">{{ __('ui.sign_in') }} <i data-lucide="arrow-right" aria-hidden="true"></i></button>
            </form>

            @error('google')<div class="login-error login-google-error" role="alert">{{ $message }}</div>@enderror
            <div class="login-divider"><span>or continue with</span></div>
            <a class="login-google" href="{{ route('google.redirect') }}">
                <svg viewBox="0 0 48 48" aria-hidden="true"><path fill="#4285F4" d="M43.6 24.5c0-1.4-.1-2.8-.4-4.1H24v7.8h11c-.5 2.5-1.9 4.6-4 6v5.1h6.5c3.8-3.5 6.1-8.7 6.1-14.8Z"/><path fill="#34A853" d="M24 44c5.5 0 10.1-1.8 13.5-4.8L31 34.1c-1.8 1.2-4.1 2-7 2-5.3 0-9.7-3.6-11.3-8.5H6v5.3C9.4 39.1 16.2 44 24 44Z"/><path fill="#FBBC05" d="M12.7 27.6a12 12 0 0 1 0-7.2v-5.3H6a20 20 0 0 0 0 17.8l6.7-5.3Z"/><path fill="#EA4335" d="M24 11.9c3 0 5.7 1 7.8 3.1l5.8-5.8C34.1 6 29.5 4 24 4 16.2 4 9.4 8.9 6 15.1l6.7 5.3c1.6-4.9 6-8.5 11.3-8.5Z"/></svg>
                <span>Continue with Google</span>
            </a>
            <p class="login-signup-prompt">Don’t have an account? <a href="{{ route('register') }}">{{ __('ui.sign_up') }}</a></p>
        </section>
    </div>
</main>
@if(session('status'))
    @include('components.success-toast.success-toast', ['title' => 'Success', 'message' => session('status')])
@endif
@include('components.cookie-consent.cookie-consent')
</body>
</html>
