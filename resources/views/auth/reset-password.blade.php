<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Choose a new password | LocalPro</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/ts/app.ts'])
</head>
<body class="auth-page">
<main class="auth-stage">
    <div class="auth-layout auth-recovery-layout">
        @include('components.auth-welcome', [
            'kicker' => 'ACCOUNT RECOVERY',
            'headline' => 'Choose a new password.',
            'description' => 'Set a strong password to secure your LocalPro account.',
        ])
        <section class="auth-form-panel" aria-labelledby="auth-title">
            <a class="auth-back-link" href="{{ route('login') }}">&#8592; Back to sign in</a>
            <div class="auth-form-heading">
                <span class="auth-kicker">SECURE ACCOUNT RECOVERY</span>
                <h1 id="auth-title">Choose a new password</h1>
                <p>Use at least eight characters for your new password.</p>
            </div>
            <form class="auth-form" method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="auth-form-grid auth-recovery-fields">
                    <div class="form-row">
                        <label for="email">Email address</label>
                        <input id="email" type="email" name="email" value="{{ old('email',$email) }}" required autocomplete="email" placeholder="you@example.com">
                        @error('email')<small class="error-message">{{ $message }}</small>@enderror
                    </div>
                    <div class="form-row">
                        <label for="password">New password</label>
                        <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password" placeholder="At least 8 characters">
                        @error('password')<small class="error-message">{{ $message }}</small>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <label for="password_confirmation">Confirm new password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" placeholder="Enter it again">
                </div>
                @if($errors->any())<small class="error-message">{{ $errors->first() }}</small>@endif
                <button class="button form-submit auth-submit" type="submit">Update password <span>&#8594;</span></button>
            </form>
            <div class="auth-signup-prompt"><span>Need a different reset link?</span><a href="{{ route('password.request') }}">Request another</a></div>
        </section>
    </div>
</main>
</body>
</html>
