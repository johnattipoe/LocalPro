<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Reset your password | LocalPro</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/ts/app.ts'])
</head>
<body class="auth-page">
<main class="auth-stage">
    <div class="auth-layout auth-recovery-layout">
        @include('components.auth-welcome', [
            'kicker' => 'ACCOUNT RECOVERY',
            'headline' => 'Get back to LocalPro.',
            'description' => 'Enter your account email and we’ll send a secure reset link if it matches an account.',
        ])
        <section class="auth-form-panel" aria-labelledby="auth-title">
            <a class="auth-back-link" href="{{ route('login') }}">&#8592; Back to sign in</a>
            <div class="auth-form-heading">
                <span class="auth-kicker">SECURE ACCOUNT RECOVERY</span>
                <h1 id="auth-title">Forgot your password?</h1>
                <p>We’ll email a password reset link if an account exists for that address.</p>
            </div>
            @if(session('status'))<div class="flash-note">{{ session('status') }}</div>@endif
            <form class="auth-form" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-row">
                    <label for="email">Email address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com" autofocus>
                    @error('email')<small class="error-message">{{ $message }}</small>@enderror
                </div>
                <button class="button form-submit auth-submit" type="submit">Send reset link <span>&#8594;</span></button>
            </form>
            <div class="auth-signup-prompt"><span>Remember your password?</span><a href="{{ route('login') }}">Sign in</a></div>
        </section>
    </div>
</main>
</body>
</html>
