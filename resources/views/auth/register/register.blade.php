<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('logo/localpro-mark.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#f4f8fc">
    <title>Create Your Account | LocalPro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/ts/app/app.ts'])
</head>
<body class="auth-page register-screen">
<main class="auth-stage register-stage">
    <div class="register-card">
<aside class="register-welcome" aria-label="Why join LocalPro">
            <img class="auth-reference-image" src="{{ asset('images/auth/register-community.jpg') }}" alt="LocalPro community customers and service providers">
            <a class="register-brand" href="{{ route('home') }}" aria-label="LocalPro homepage">
                <img src="{{ asset('logo/localpro-mark.png') }}" alt="">
                <span>LocalPro</span>
            </a>
            <div class="register-welcome-copy">
                <span class="register-eyebrow">GOOD PEOPLE. GOOD WORK. RIGHT HERE.</span>
                <h2>Join LocalPro Today</h2>
                <p>Create your account and connect with trusted professionals in your area.</p>
                <ul>
                    <li><i data-lucide="check" aria-hidden="true"></i> Access verified professionals</li>
                    <li><i data-lucide="check" aria-hidden="true"></i> Book services with confidence</li>
                    <li><i data-lucide="check" aria-hidden="true"></i> Manage your appointments</li>
                </ul>
            </div>
            <div class="register-scene" aria-hidden="true">
                <span class="register-plant"><i></i><i></i><i></i><b></b></span>
                <span class="register-sofa"></span>
                <span class="register-person"><i></i><b></b><em></em></span>
                <span class="register-laptop"><i></i><b></b></span>
            </div>
            <span class="register-region">Made for Ho and the Volta Region</span>
        </aside>

<section class="register-form-panel" aria-labelledby="register-title">
            @include('components.language-picker.language-picker', ['id' => 'auth-language', 'class' => 'auth-language-picker'])
            <a class="auth-home-arrow" href="{{ route('home') }}" aria-label="{{ __('ui.back_to_website') }}" title="{{ __('ui.back_to_website') }}"><i data-lucide="arrow-left" aria-hidden="true"></i><span>{{ __('ui.back_to_website') }}</span></a>
            <header class="register-heading">
                <span class="register-eyebrow">CREATE YOUR LOCALPRO ACCOUNT</span>
                <h1 id="register-title">Create Your Account</h1>
                <p>Get started in just a few steps.</p>
            </header>

            @if(session('status'))<div class="register-flash" role="status">{{ session('status') }}</div>@endif

            <form method="POST" action="{{ route('register.store') }}" class="register-form">
                @csrf
                <div class="register-field-grid">
                    <div class="register-field">
                        <label for="name">{{ __('ui.full_name') }}</label>
                        <div class="register-input"><i data-lucide="user-round" aria-hidden="true"></i><input id="name" name="name" value="{{ old('name') }}" required maxlength="120" autocomplete="name" placeholder="Your full name"></div>
                        @error('name')<small class="register-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="register-field">
                        <label for="email">{{ __('ui.email_address') }}</label>
                        <div class="register-input"><i data-lucide="mail" aria-hidden="true"></i><input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="180" autocomplete="email" placeholder="you@example.com"></div>
                        @error('email')<small class="register-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="register-field">
                        <label for="phone">Phone Number <span>(optional)</span></label>
                        <div class="register-input"><i data-lucide="phone" aria-hidden="true"></i><input id="phone" name="phone" type="tel" value="{{ old('phone') }}" maxlength="32" autocomplete="tel" placeholder="024 000 0000"></div>
                    </div>
                    <div class="register-field">
                        <label for="city">{{ __('ui.town') }}</label>
                        <div class="register-input register-select"><i data-lucide="map-pin" aria-hidden="true"></i><select id="city" name="city" required><option value="Ho" @selected(old('city', 'Ho') === 'Ho')>Ho</option><option value="Hohoe" @selected(old('city') === 'Hohoe')>Hohoe</option><option value="Keta" @selected(old('city') === 'Keta')>Keta</option><option value="Aflao" @selected(old('city') === 'Aflao')>Aflao</option></select></div>
                        @error('city')<small class="register-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="register-field">
                        <label for="role">I Want To</label>
                        <div class="register-input register-select"><i data-lucide="briefcase-business" aria-hidden="true"></i><select id="role" name="role" data-account-role required><option value="customer" @selected(old('role', request('role', 'customer')) === 'customer')>Find a professional</option><option value="provider" @selected(old('role', request('role')) === 'provider')>Offer my services</option></select></div>
                    </div>
                    <div class="register-field" data-provider-fields>
                        <label for="profession">Main Service</label>
                        <div class="register-input register-select"><i data-lucide="wrench" aria-hidden="true"></i><select id="profession" name="profession"><option value="">Choose your service</option>@foreach($categories as $category)<option value="{{ $category->name }}" @selected(old('profession') === $category->name)>{{ $category->name }}</option>@endforeach<option value="Other" @selected(old('profession') === 'Other')>Other local service</option></select></div>
                        @error('profession')<small class="register-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="register-field">
                        <label for="password">{{ __('ui.password') }}</label>
                        <div class="register-input"><i data-lucide="lock-keyhole" aria-hidden="true"></i><input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" placeholder="Create a strong password"></div>
                        @error('password')<small class="register-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="register-field">
                        <label for="password_confirmation">{{ __('ui.confirm_password') }}</label>
                        <div class="register-input"><i data-lucide="lock-keyhole" aria-hidden="true"></i><input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" placeholder="Confirm your password"></div>
                    </div>
                </div>
                <button class="register-submit" type="submit">{{ __('ui.sign_up') }} <i data-lucide="arrow-right" aria-hidden="true"></i></button>
            </form>

            @error('google')<div class="register-error register-google-error" role="alert">{{ $message }}</div>@enderror
            <div class="register-divider"><span>or continue with</span></div>
            <a class="register-google" data-google-signin href="{{ route('google.redirect', ['role' => old('role', request('role', 'customer'))]) }}">
                <svg viewBox="0 0 48 48" aria-hidden="true"><path fill="#4285F4" d="M43.6 24.5c0-1.4-.1-2.8-.4-4.1H24v7.8h11c-.5 2.5-1.9 4.6-4 6v5.1h6.5c3.8-3.5 6.1-8.7 6.1-14.8Z"/><path fill="#34A853" d="M24 44c5.5 0 10.1-1.8 13.5-4.8L31 34.1c-1.8 1.2-4.1 2-7 2-5.3 0-9.7-3.6-11.3-8.5H6v5.3C9.4 39.1 16.2 44 24 44Z"/><path fill="#FBBC05" d="M12.7 27.6a12 12 0 0 1 0-7.2v-5.3H6a20 20 0 0 0 0 17.8l6.7-5.3Z"/><path fill="#EA4335" d="M24 11.9c3 0 5.7 1 7.8 3.1l5.8-5.8C34.1 6 29.5 4 24 4 16.2 4 9.4 8.9 6 15.1l6.7 5.3c1.6-4.9 6-8.5 11.3-8.5Z"/></svg>
                <span>Continue with Google</span>
            </a>
            <p class="register-signin-prompt">Already have an account? <a href="{{ route('login') }}">{{ __('ui.sign_in') }}</a></p>
        </section>
    </div>
</main>
@include('components.cookie-consent.cookie-consent')
</body>
</html>
