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
                    <div class="register-field register-field-full">
                        <label for="name">{{ __('ui.full_name') }}</label>
                        <div class="register-input register-input-name">
                            <svg class="field-icon name-field-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="12" cy="7" r="4.5" fill="#4285F4"/>
                                <path fill="#34A853" d="M12 13c-4.42 0-8 2.24-8 5v2c0 .55.45 1 1 1h14c.55 0 1-.45 1-1v-2c0-2.76-3.58-5-8-5z"/>
                            </svg>
                            <input id="name" name="name" value="{{ old('name') }}" required maxlength="120" autocomplete="name" placeholder="Your full name">
                        </div>
                        @error('name')<small class="register-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="register-field">
                        <label for="email">{{ __('ui.email_address') }}</label>
                        <div class="register-input register-input-gmail">
                            <svg class="gmail-field-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="#4285F4" d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v.2l10 6.3 10-6.3V6z"/>
                                <path fill="#34A853" d="M22 7.7l-10 6.3L2 7.7V18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V7.7z"/>
                                <path fill="#EA4335" d="M20 4H4C2.9 4 2 4.9 2 6v.4l10 6.3 10-6.3V6c0-1.1-.9-2-2-2z"/>
                                <path fill="#FBBC05" d="M2 7.7V18c0 .2.04.4.1.6l6.8-6.8L2 7.7z"/>
                                <path fill="#4285F4" d="M22 7.7l-6.9 4.1 6.8 6.8c.06-.2.1-.4.1-.6V7.7z"/>
                            </svg>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="180" autocomplete="email" placeholder="you@gmail.com">
                        </div>
                        @error('email')<small class="register-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="register-field">
                        <label for="phone">Phone Number</label>
                        <div class="register-input register-input-phone">
                            <svg class="field-icon phone-field-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="#34A853" d="M19.23 15.26l-2.54-.29a1.99 1.99 0 0 0-1.64.57l-1.84 1.84a15.045 15.045 0 0 1-6.59-6.59l1.85-1.85c.43-.43.64-1.03.57-1.64l-.29-2.52a2 2 0 0 0-1.99-1.78H4.21a2 2 0 0 0-2 2.02A17.98 17.98 0 0 0 20.19 21.8c1.1 0 2-.9 2-2v-2.55a2 2 0 0 0-1.96-1.99z"/>
                            </svg>
                            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" maxlength="32" autocomplete="tel" placeholder="024 000 0000">
                        </div>
                    </div>
                    <div class="register-field">
                        <label for="city">{{ __('ui.town') }}</label>
                        <div class="register-input register-select register-input-city">
                            <svg class="field-icon town-field-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="#EA4335" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                                <circle cx="12" cy="9" r="2.8" fill="#FFFFFF"/>
                                <circle cx="12" cy="9" r="1.5" fill="#4285F4"/>
                            </svg>
                            <select id="city" name="city" required>
                                <option value="Ho" @selected(old('city', 'Ho') === 'Ho')>Ho</option>
                                <option value="Hohoe" @selected(old('city') === 'Hohoe')>Hohoe</option>
                                <option value="Keta" @selected(old('city') === 'Keta')>Keta</option>
                                <option value="Aflao" @selected(old('city') === 'Aflao')>Aflao</option>
                            </select>
                        </div>
                        @error('city')<small class="register-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="register-field">
                        <label for="role">I Want To</label>
                        <div class="register-input register-select register-input-role">
                            <svg class="field-icon role-field-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="#FBBC05" d="M10 2h4c1.1 0 2 .9 2 2v2h4c1.1 0 2 .9 2 2v3H2V8c0-1.1.9-2 2-2h4V4c0-1.1.9-2 2-2zm2 2h-2v2h2V4z"/>
                                <path fill="#4285F4" d="M2 11h20v8c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2v-8z"/>
                                <rect x="9" y="10" width="6" height="4" rx="1" fill="#34A853"/>
                            </svg>
                            <select id="role" name="role" data-account-role required>
                                <option value="customer" @selected(old('role', request('role', 'customer')) === 'customer')>Find a professional</option>
                                <option value="provider" @selected(old('role', request('role')) === 'provider')>Offer my services</option>
                            </select>
                        </div>
                    </div>
                    @php
                        $defaultServices = [
                            'AC & Refrigeration Technician',
                            'Appliance Repair (Fridge, TV, Washing Machine)',
                            'Auto Electrician',
                            'Auto Mechanic',
                            'Barber & Men\'s Grooming',
                            'Borehole & Water Tank Installer',
                            'Carpenter & Woodworker',
                            'Caterer & Cooking Service',
                            'CCTV & Security Installer',
                            'Cleaning & Janitorial Service',
                            'Delivery & Dispatch Rider',
                            'Electrician',
                            'Event Planner & Decorator',
                            'Fumigation & Pest Control',
                            'Gardener & Landscaper',
                            'Generator Repair & Maintenance',
                            'Glazier & Aluminum Fabricator',
                            'Hairdresser & Braider',
                            'Laundry & Dry Cleaning',
                            'Makeup Artist & Beauty',
                            'Mason & Bricklayer',
                            'Moving & Relocation Service',
                            'Painter & Decorator',
                            'Phone & Laptop Technician',
                            'Photographer & Videographer',
                            'Plumber',
                            'POP Ceiling Installer',
                            'Roofer & Roofing Specialist',
                            'Solar & Inverter Installer',
                            'Tailor & Fashion Designer',
                            'Tiler & Tile Installer',
                            'Welder & Metal Fabricator',
                        ];
                        $categoryNames = isset($categories) && count($categories) ? $categories->pluck('name')->toArray() : [];
                        $allServices = array_values(array_unique(array_merge($categoryNames, $defaultServices)));
                        sort($allServices);
                    @endphp
                    <div class="register-field register-field-full" data-provider-fields>
                        <label for="profession">Service you offer</label>
                        <div class="register-input register-select register-input-service">
                            <svg class="field-icon service-field-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="#FBBC05" d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/>
                                <circle cx="17.5" cy="6.5" r="2" fill="#4285F4"/>
                            </svg>
                            <select id="profession" name="profession">
                                <option value="">Select the service you offer</option>
                                @foreach($allServices as $service)
                                    <option value="{{ $service }}" @selected(old('profession') === $service)>{{ $service }}</option>
                                @endforeach
                                <option value="Other" @selected(old('profession') === 'Other')>Other local service</option>
                            </select>
                        </div>
                        @error('profession')<small class="register-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="register-field">
                        <label for="password">{{ __('ui.password') }}</label>
                        <div class="register-input register-input-password">
                            <svg class="password-field-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="#5f6368" d="M12 2C9.24 2 7 4.24 7 7v3H6c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8c0-1.1-.9-2-2-2h-1V7c0-2.76-2.24-5-5-5zm-3 5c0-1.66 1.34-3 3-3s3 1.34 3 3v3H9V7z"/>
                                <rect x="4" y="10" width="16" height="12" rx="2.5" fill="#4285F4"/>
                                <circle cx="12" cy="15" r="1.5" fill="#FBBC05"/>
                                <path d="M12 16.5V19" stroke="#FBBC05" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                            <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" placeholder="Create a strong password">
                            <button type="button" class="password-toggle-btn" data-password-toggle="password" aria-label="Toggle password visibility" tabindex="-1">
                                <svg class="password-eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path class="eye-open" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                    <circle class="eye-open" cx="12" cy="12" r="3"/>
                                    <path class="eye-closed" d="M9.88 9.88a3 3 0 1 0 4.24 4.24M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61M2 2l20 20"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')<small class="register-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="register-field">
                        <label for="password_confirmation">{{ __('ui.confirm_password') }}</label>
                        <div class="register-input register-input-password">
                            <svg class="password-field-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="#5f6368" d="M12 2C9.24 2 7 4.24 7 7v3H6c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8c0-1.1-.9-2-2-2h-1V7c0-2.76-2.24-5-5-5zm-3 5c0-1.66 1.34-3 3-3s3 1.34 3 3v3H9V7z"/>
                                <rect x="4" y="10" width="16" height="12" rx="2.5" fill="#4285F4"/>
                                <circle cx="12" cy="15" r="1.5" fill="#FBBC05"/>
                                <path d="M12 16.5V19" stroke="#FBBC05" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                            <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" placeholder="Confirm your password">
                            <button type="button" class="password-toggle-btn" data-password-toggle="password_confirmation" aria-label="Toggle password visibility" tabindex="-1">
                                <svg class="password-eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path class="eye-open" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                    <circle class="eye-open" cx="12" cy="12" r="3"/>
                                    <path class="eye-closed" d="M9.88 9.88a3 3 0 1 0 4.24 4.24M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61M2 2l20 20"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <button class="register-submit" type="submit">{{ __('ui.sign_up') }} <i data-lucide="arrow-right" aria-hidden="true"></i></button>
            </form>

            @error('google')<div class="register-error register-google-error" role="alert">{{ $message }}</div>@enderror
            @error('facebook')<div class="register-error register-google-error" role="alert">{{ $message }}</div>@enderror
            <div class="register-divider"><span>or continue with</span></div>
            <div class="auth-social-row">
                <a class="auth-social-btn auth-social-google" data-google-signin href="{{ route('google.redirect', ['role' => old('role', request('role', 'customer'))]) }}" aria-label="Continue with Google" title="Continue with Google">
                    <svg viewBox="0 0 48 48" aria-hidden="true"><path fill="#4285F4" d="M43.6 24.5c0-1.4-.1-2.8-.4-4.1H24v7.8h11c-.5 2.5-1.9 4.6-4 6v5.1h6.5c3.8-3.5 6.1-8.7 6.1-14.8Z"/><path fill="#34A853" d="M24 44c5.5 0 10.1-1.8 13.5-4.8L31 34.1c-1.8 1.2-4.1 2-7 2-5.3 0-9.7-3.6-11.3-8.5H6v5.3C9.4 39.1 16.2 44 24 44Z"/><path fill="#FBBC05" d="M12.7 27.6a12 12 0 0 1 0-7.2v-5.3H6a20 20 0 0 0 0 17.8l6.7-5.3Z"/><path fill="#EA4335" d="M24 11.9c3 0 5.7 1 7.8 3.1l5.8-5.8C34.1 6 29.5 4 24 4 16.2 4 9.4 8.9 6 15.1l6.7 5.3c1.6-4.9 6-8.5 11.3-8.5Z"/></svg>
                </a>
                <a class="auth-social-btn auth-social-facebook" data-facebook-signin href="{{ route('facebook.redirect', ['role' => old('role', request('role', 'customer'))]) }}" aria-label="Continue with Facebook" title="Continue with Facebook">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="#1877F2" d="M24 12.07C24 5.41 18.63 0 12 0S0 5.41 0 12.07C0 18.1 4.39 23.1 10.13 24v-8.44H7.08v-3.49h3.04V9.41c0-3.02 1.8-4.7 4.54-4.7 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.95.93-1.95 1.88v2.27h3.32l-.53 3.49h-2.79V24C19.61 23.1 24 18.1 24 12.07Z"/></svg>
                </a>
            </div>
            <p class="register-signin-prompt">Already have an account? <a href="{{ route('login') }}">{{ __('ui.sign_in') }}</a></p>
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

(function() {
    var roleSelect = document.getElementById('role');
    var providerField = document.querySelector('[data-provider-fields]');
    var professionSelect = document.getElementById('profession');
    if (!roleSelect || !providerField) return;

    function syncProviderFields() {
        var isProvider = roleSelect.value === 'provider';
        providerField.hidden = !isProvider;
        if (professionSelect) {
            professionSelect.required = isProvider;
            professionSelect.disabled = !isProvider;
        }
    }

    roleSelect.addEventListener('change', syncProviderFields);
    syncProviderFields();
})();
</script>
</body>
</html>
