<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sign in | LocalPro</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/ts/app.ts'])
</head>
<body class="auth-page">
<main class="auth-stage">
    <div class="auth-layout auth-login-layout">
        @include('components.auth-welcome', [
            'kicker' => 'WELCOME BACK',
            'headline' => 'Good work starts with trusted local help.',
            'description' => 'Find skilled professionals around Ho and the Volta Region, or return to manage the work you have already booked.',
        ])
        <section class="auth-form-panel" aria-labelledby="auth-title">
            <a class="auth-back-link" href="{{ route('home') }}">&#8592; Back to LocalPro</a>
            <div class="auth-form-heading">
                <span class="auth-kicker">YOUR LOCAL MARKETPLACE</span>
                <h1 id="auth-title">Sign in</h1>
                <p>Use the email address connected to your LocalPro account.</p>
            </div>
            @if(session('status'))<div class="flash-note">{{ session('status') }}</div>@endif
            <a class="google-signin-button" href="{{ route('google.redirect') }}">
                <svg viewBox="0 0 48 48" aria-hidden="true"><path fill="#4285F4" d="M43.6 24.5c0-1.4-.1-2.8-.4-4.1H24v7.8h11c-.5 2.5-1.9 4.6-4 6v5.1h6.5c3.8-3.5 6.1-8.7 6.1-14.8Z"/><path fill="#34A853" d="M24 44c5.5 0 10.1-1.8 13.5-4.8L31 34.1c-1.8 1.2-4.1 2-7 2-5.3 0-9.7-3.6-11.3-8.5H6v5.3C9.4 39.1 16.2 44 24 44Z"/><path fill="#FBBC05" d="M12.7 27.6a12 12 0 0 1 0-7.2v-5.3H6a20 20 0 0 0 0 17.8l6.7-5.3Z"/><path fill="#EA4335" d="M24 11.9c3 0 5.7 1 7.8 3.1l5.8-5.8C34.1 6 29.5 4 24 4 16.2 4 9.4 8.9 6 15.1l6.7 5.3c1.6-4.9 6-8.5 11.3-8.5Z"/></svg>
                <span>Continue with Google</span>
            </a>
            @error('google')<div class="error-message auth-google-error">{{ $message }}</div>@enderror
            <div class="auth-divider"><span>Or sign in with email</span></div>
            <form method="POST" action="{{ route('login.store') }}" class="auth-form">
                @csrf
                <div class="form-row">
                    <label for="email">Email address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com" autofocus>
                    @error('email')<small class="error-message">{{ $message }}</small>@enderror
                </div>
                <div class="form-row">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Enter your password">
                    @error('password')<small class="error-message">{{ $message }}</small>@enderror
                </div>
                <div class="auth-form-options">
                    <label class="auth-remember"><input type="checkbox" name="remember" value="1" @checked(old('remember'))> Remember me</label>
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                </div>
                <button class="button form-submit auth-submit" type="submit">Sign in <span>&#8594;</span></button>
            </form>
            <div class="auth-signup-prompt"><span>New to LocalPro?</span><a href="{{ route('register') }}">Create an account</a></div>
        </section>
    </div>
</main>
</body>
</html>
