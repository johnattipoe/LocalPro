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
                        <div class="reset-input-wrap reset-input-password">
                            <svg class="password-field-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="#5f6368" d="M12 2C9.24 2 7 4.24 7 7v3H6c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8c0-1.1-.9-2-2-2h-1V7c0-2.76-2.24-5-5-5zm-3 5c0-1.66 1.34-3 3-3s3 1.34 3 3v3H9V7z"/>
                                <rect x="4" y="10" width="16" height="12" rx="2.5" fill="#4285F4"/>
                                <circle cx="12" cy="15" r="1.5" fill="#FBBC05"/>
                                <path d="M12 16.5V19" stroke="#FBBC05" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                            <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password" placeholder="Enter your new password" aria-describedby="password-hint">
                            <button class="reset-password-toggle password-toggle-btn" type="button" data-password-toggle="password" aria-label="Show new password" aria-pressed="false">
                                <svg class="password-eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path class="eye-open" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                    <circle class="eye-open" cx="12" cy="12" r="3"/>
                                    <path class="eye-closed" d="M9.88 9.88a3 3 0 1 0 4.24 4.24M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61M2 2l20 20"/>
                                </svg>
                            </button>
                        </div>
                        <small id="password-hint" class="reset-field-hint">Use at least 8 characters.</small>
                    </div>

                    <div class="reset-field">
                        <label for="password_confirmation">{{ __('ui.confirm_password') }}</label>
                        <div class="reset-input-wrap reset-input-password">
                            <svg class="password-field-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="#5f6368" d="M12 2C9.24 2 7 4.24 7 7v3H6c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8c0-1.1-.9-2-2-2h-1V7c0-2.76-2.24-5-5-5zm-3 5c0-1.66 1.34-3 3-3s3 1.34 3 3v3H9V7z"/>
                                <rect x="4" y="10" width="16" height="12" rx="2.5" fill="#4285F4"/>
                                <circle cx="12" cy="15" r="1.5" fill="#FBBC05"/>
                                <path d="M12 16.5V19" stroke="#FBBC05" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                            <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" placeholder="Confirm your new password">
                            <button class="reset-password-toggle password-toggle-btn" type="button" data-password-toggle="password_confirmation" aria-label="Show confirmed password" aria-pressed="false">
                                <svg class="password-eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path class="eye-open" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                    <circle class="eye-open" cx="12" cy="12" r="3"/>
                                    <path class="eye-closed" d="M9.88 9.88a3 3 0 1 0 4.24 4.24M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61M2 2l20 20"/>
                                </svg>
                            </button>
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
