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
                    <div class="login-input login-input-gmail">
                        <svg class="gmail-field-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="#4285F4" d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v.2l10 6.3 10-6.3V6z"/>
                            <path fill="#34A853" d="M22 7.7l-10 6.3L2 7.7V18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V7.7z"/>
                            <path fill="#EA4335" d="M20 4H4C2.9 4 2 4.9 2 6v.4l10 6.3 10-6.3V6c0-1.1-.9-2-2-2z"/>
                            <path fill="#FBBC05" d="M2 7.7V18c0 .2.04.4.1.6l6.8-6.8L2 7.7z"/>
                            <path fill="#4285F4" d="M22 7.7l-6.9 4.1 6.8 6.8c.06-.2.1-.4.1-.6V7.7z"/>
                        </svg>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@gmail.com" autofocus>
                    </div>
                    @error('email')<small class="login-error">{{ $message }}</small>@enderror
                </div>
                <div class="login-field">
                    <label for="password">{{ __('ui.password') }}</label>
                    <div class="login-input login-input-password">
                        <svg class="password-field-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="#5f6368" d="M12 2C9.24 2 7 4.24 7 7v3H6c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8c0-1.1-.9-2-2-2h-1V7c0-2.76-2.24-5-5-5zm-3 5c0-1.66 1.34-3 3-3s3 1.34 3 3v3H9V7z"/>
                            <rect x="4" y="10" width="16" height="12" rx="2.5" fill="#4285F4"/>
                            <circle cx="12" cy="15" r="1.5" fill="#FBBC05"/>
                            <path d="M12 16.5V19" stroke="#FBBC05" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                        <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Enter your password">
                        <button type="button" class="password-toggle-btn" data-password-toggle="password" aria-label="Toggle password visibility" tabindex="-1">
                            <svg class="password-eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path class="eye-open" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle class="eye-open" cx="12" cy="12" r="3"/>
                                <path class="eye-closed" d="M9.88 9.88a3 3 0 1 0 4.24 4.24M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61M2 2l20 20"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')<small class="login-error">{{ $message }}</small>@enderror
                </div>
                <div class="login-options">
                    <label><input type="checkbox" name="remember" value="1" @checked(old('remember'))> {{ __('ui.remember_me') }}</label>
                    <a href="{{ route('password.request') }}">{{ __('ui.forgot_password') }}</a>
                </div>
                <button class="login-submit" type="submit">{{ __('ui.sign_in') }} <i data-lucide="arrow-right" aria-hidden="true"></i></button>
            </form>

            @error('google')<div class="login-error login-google-error" role="alert">{{ $message }}</div>@enderror
            @error('facebook')<div class="login-error login-google-error" role="alert">{{ $message }}</div>@enderror
            <div class="login-divider"><span>or continue with</span></div>
            <div class="auth-social-row">
                <a class="auth-social-btn auth-social-google" href="{{ route('google.redirect') }}" aria-label="Continue with Google" title="Continue with Google">
                    <svg viewBox="0 0 48 48" aria-hidden="true"><path fill="#4285F4" d="M43.6 24.5c0-1.4-.1-2.8-.4-4.1H24v7.8h11c-.5 2.5-1.9 4.6-4 6v5.1h6.5c3.8-3.5 6.1-8.7 6.1-14.8Z"/><path fill="#34A853" d="M24 44c5.5 0 10.1-1.8 13.5-4.8L31 34.1c-1.8 1.2-4.1 2-7 2-5.3 0-9.7-3.6-11.3-8.5H6v5.3C9.4 39.1 16.2 44 24 44Z"/><path fill="#FBBC05" d="M12.7 27.6a12 12 0 0 1 0-7.2v-5.3H6a20 20 0 0 0 0 17.8l6.7-5.3Z"/><path fill="#EA4335" d="M24 11.9c3 0 5.7 1 7.8 3.1l5.8-5.8C34.1 6 29.5 4 24 4 16.2 4 9.4 8.9 6 15.1l6.7 5.3c1.6-4.9 6-8.5 11.3-8.5Z"/></svg>
                </a>
                <a class="auth-social-btn auth-social-facebook" href="{{ route('facebook.redirect') }}" aria-label="Continue with Facebook" title="Continue with Facebook">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="#1877F2" d="M24 12.07C24 5.41 18.63 0 12 0S0 5.41 0 12.07C0 18.1 4.39 23.1 10.13 24v-8.44H7.08v-3.49h3.04V9.41c0-3.02 1.8-4.7 4.54-4.7 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.95.93-1.95 1.88v2.27h3.32l-.53 3.49h-2.79V24C19.61 23.1 24 18.1 24 12.07Z"/></svg>
                </a>
            </div>
            <p class="login-signup-prompt">Don’t have an account? <a href="{{ route('register') }}">{{ __('ui.sign_up') }}</a></p>
        </section>
    </div>
</main>
@if(session('status'))
    @include('components.success-toast.success-toast', ['title' => 'Success', 'message' => session('status')])
@endif
@include('components.cookie-consent.cookie-consent')
<script>
document.addEventListener('click', function(e) {
    var btn = e.target.closest('[data-password-toggle]');
    if (!btn) return;
    e.preventDefault();
    var wrap = btn.closest('.login-input, .register-input, .reset-input-wrap, .nav-auth-input-wrap, div, label');
    var input = wrap ? wrap.querySelector('input') : null;
    if (!input) return;
    var isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    btn.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
    btn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
    btn.classList.toggle('is-visible', isPassword);
});
</script>
</body>
</html>
