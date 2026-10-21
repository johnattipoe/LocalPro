@extends('layouts.admin.admin')

@section('title', 'Platform settings | LocalPro')

@section('content')
<section class="admin-content admin-settings-page">
    <div class="admin-page-heading"><div><div class="eyebrow muted">PLATFORM CONFIGURATION</div><h1>{{ __('ui.settings') }}</h1><p class="request-intro">Review the current LocalPro setup. Connection secrets stay private in the application environment and are never shown on this page.</p></div></div>

    <div class="admin-settings-grid">
        <article class="admin-settings-card">
            <span class="admin-settings-icon">⌖</span>
            <div><h2>Service area</h2><p>LocalPro is currently focused on serving customers and professionals in Ho and the Volta Region.</p><strong>Ho, Volta Region, Ghana</strong></div>
            <span class="admin-settings-badge is-active">{{ __('ui.active') }}</span>
        </article>
        <article class="admin-settings-card">
            <span class="admin-settings-icon settings-blue">₵</span>
            <div><h2>Currency</h2><p>Customer prices and marketplace revenue are displayed in Ghana cedis.</p><strong>Ghana cedi (GH₵)</strong></div>
            <span class="admin-settings-badge is-active">Configured</span>
        </article>
        <article class="admin-settings-card">
            <span class="admin-settings-icon settings-purple">▰</span>
            <div><h2>Online payments</h2><p>Payment processing status, based on the private Paystack keys configured for this app.</p><strong>{{ $paymentConfigured ? 'Paystack credentials configured' : 'Not configured' }}</strong></div>
            <span class="admin-settings-badge {{ $paymentConfigured ? 'is-active' : 'is-pending' }}">{{ $paymentConfigured ? 'Ready' : 'Setup needed' }}</span>
        </article>
        <article class="admin-settings-card">
            <span class="admin-settings-icon settings-amber">G</span>
            <div><h2>Google sign-in</h2><p>Google OAuth status, based on whether both client credentials are present.</p><strong>{{ $googleConfigured ? 'Google OAuth configured' : 'Not configured' }}</strong></div>
            <span class="admin-settings-badge {{ $googleConfigured ? 'is-active' : 'is-pending' }}">{{ $googleConfigured ? 'Ready' : 'Setup needed' }}</span>
        </article>
        <article class="admin-settings-card settings-full-card">
            <span class="admin-settings-icon settings-gray">⚙</span>
            <div><h2>Platform identity</h2><p>The application name used across the marketplace and admin tools.</p><strong>{{ $appName }}</strong></div>
            <span class="admin-settings-badge is-active">Live</span>
        </article>
    </div>
    <div class="admin-settings-note"><strong>Need to change a setting?</strong><p>Update environment values in your local <code>.env</code> file during development, or in your hosting provider’s private environment settings after deployment. Never place API secrets in the page or in public code.</p></div>
</section>
@endsection
