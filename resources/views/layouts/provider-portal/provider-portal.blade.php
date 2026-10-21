<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('logo/localpro-mark.png') }}">
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Provider dashboard') | LocalPro</title>
    @vite(['resources/css/app.css', 'resources/ts/app/app.ts'])
</head>
<body class="provider-portal">
    @php($providerUser = auth()->user())
    @php($providerProfile = $providerUser->providerProfile)
    @php($providerAvatarPhoto = $providerProfile && $providerProfile->profile_photo_path ? \Illuminate\Support\Facades\Storage::disk($providerProfile->profile_photo_disk ?: 'public')->url($providerProfile->profile_photo_path) : null)
    <header class="pp-topbar">
        <a class="pp-brand" href="{{ route('provider.dashboard') }}"><img class="pp-brand-mark" src="{{ asset('logo/localpro-mark.png') }}" alt=""><span>Local<span>Pro</span><small>Trusted Local Services</small></span></a>
        <button class="portal-sidebar-toggle" type="button" aria-label="Hide provider sidebar" aria-controls="providerSidebar" aria-expanded="true" data-sidebar-toggle data-sidebar-mode="collapse"><i data-lucide="panel-left-close" aria-hidden="true"></i></button>
        <form class="pp-search" action="{{ route('provider.bookings.index') }}" method="GET"><span aria-hidden="true"><i data-lucide="search"></i></span><input name="q" value="{{ request('q') }}" aria-label="Search bookings" placeholder="Search bookings, customers, or services..."><button aria-label="Search"><i data-lucide="arrow-right" aria-hidden="true"></i></button></form>
        <span class="pp-location"><i data-lucide="map-pin" aria-hidden="true"></i> &nbsp;{{ $providerProfile->city ?? $providerUser->city ?? 'Ho' }}, Volta Region</span>
        <a class="pp-notification" href="{{ route('notifications.index') }}" aria-label="Notifications"><i data-lucide="bell" aria-hidden="true"></i>@if(isset($stats) && ($stats['unread_notifications'] ?? 0) > 0)<b>{{ min($stats['unread_notifications'], 9) }}</b>@endif</a>
        @include('components.language-picker.language-picker', ['id' => 'provider-language'])
        <details class="pp-account-menu">
            <summary class="pp-user" aria-label="Account menu for {{ $providerUser->name }}"><span class="pp-avatar">@if($providerAvatarPhoto)<img src="{{ $providerAvatarPhoto }}" alt="">@else{{ collect(explode(' ', $providerUser->name))->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->join('') }}@endif</span><span><strong>{{ $providerUser->name }}</strong><small>Service Provider</small></span><span class="pp-account-chevron"><i data-lucide="chevron-down" aria-hidden="true"></i></span></summary>
            <div class="pp-account-dropdown"><strong>{{ $providerUser->name }}</strong><small>{{ __('ui.service_provider') }} {{ __('ui.account') }}</small><a href="{{ route('provider.dashboard') }}">{{ __('ui.dashboard') }}</a><a href="{{ route('provider.profile.edit') }}">{{ __('ui.my_profile') }}</a><a href="{{ route('provider.settings.edit') }}">{{ __('ui.settings') }}</a><a href="{{ route('provider.earnings.index') }}">{{ __('ui.earnings') }} and payouts</a><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit"><i data-lucide="log-out" aria-hidden="true"></i> {{ __('ui.log_out') }}</button></form></div>
        </details>
    </header>
    <div class="pp-frame">
        <aside class="pp-sidebar" id="providerSidebar">
            <nav class="pp-nav" aria-label="Provider navigation">
                <a title="{{ __('ui.dashboard') }}" class="{{ request()->routeIs('provider.dashboard') ? 'is-active' : '' }}" @if(request()->routeIs('provider.dashboard')) aria-current="page" @endif href="{{ route('provider.dashboard') }}"><span><i data-lucide="home" aria-hidden="true"></i></span>{{ __('ui.dashboard') }}</a>
                <a title="{{ __('ui.my_profile') }}" class="{{ request()->routeIs('provider.profile.edit') ? 'is-active' : '' }}" @if(request()->routeIs('provider.profile.edit')) aria-current="page" @endif href="{{ route('provider.profile.edit') }}"><span><i data-lucide="user-round" aria-hidden="true"></i></span>{{ __('ui.my_profile') }}</a>
                <a title="{{ __('ui.my_services') }}" class="{{ request()->routeIs('provider.services.*') ? 'is-active' : '' }}" @if(request()->routeIs('provider.services.*')) aria-current="page" @endif href="{{ route('provider.services.index') }}"><span><i data-lucide="wrench" aria-hidden="true"></i></span>{{ __('ui.my_services') }}</a>
                <a title="{{ __('ui.bookings') }}" class="{{ request()->routeIs('provider.bookings.*') ? 'is-active' : '' }}" @if(request()->routeIs('provider.bookings.*')) aria-current="page" @endif href="{{ route('provider.bookings.index') }}"><span><i data-lucide="calendar-days" aria-hidden="true"></i></span>{{ __('ui.bookings') }} @if(isset($stats) && $stats['pending'] > 0)<i>{{ $stats['pending'] }}</i>@endif</a>
                <a title="{{ __('ui.messages') }}" class="{{ request()->routeIs('messages.*') ? 'is-active' : '' }}" @if(request()->routeIs('messages.*')) aria-current="page" @endif href="{{ route('messages.index') }}"><span><i data-lucide="message-circle" aria-hidden="true"></i></span>{{ __('ui.messages') }} @if(isset($stats) && $stats['unread_messages'] > 0)<i>{{ $stats['unread_messages'] }}</i>@endif</a>
                <a title="{{ __('ui.earnings') }}" class="{{ request()->routeIs('provider.earnings.*') || request()->routeIs('provider.payouts.*') ? 'is-active' : '' }}" @if(request()->routeIs('provider.earnings.*', 'provider.payouts.*')) aria-current="page" @endif href="{{ route('provider.earnings.index') }}"><span><i data-lucide="wallet" aria-hidden="true"></i></span>{{ __('ui.earnings') }}</a>
                <a title="{{ __('ui.reviews') }}" class="{{ request()->routeIs('provider.reviews.*') ? 'is-active' : '' }}" @if(request()->routeIs('provider.reviews.*')) aria-current="page" @endif href="{{ route('provider.reviews.index') }}"><span><i data-lucide="star" aria-hidden="true"></i></span>{{ __('ui.reviews') }}</a>
                <a title="{{ __('ui.availability') }}" class="{{ request()->routeIs('provider.availability.*') ? 'is-active' : '' }}" @if(request()->routeIs('provider.availability.*')) aria-current="page" @endif href="{{ route('provider.availability.edit') }}"><span><i data-lucide="clock-3" aria-hidden="true"></i></span>{{ __('ui.availability') }}</a>
                <a title="{{ __('ui.verification') }}" class="{{ request()->routeIs('provider.verification.*') ? 'is-active' : '' }}" @if(request()->routeIs('provider.verification.*')) aria-current="page" @endif href="{{ route('provider.verification.index') }}"><span><i data-lucide="shield-check" aria-hidden="true"></i></span>{{ __('ui.verification') }}</a>
                <a title="{{ __('ui.settings') }}" class="{{ request()->routeIs('provider.settings.*') ? 'is-active' : '' }}" @if(request()->routeIs('provider.settings.*')) aria-current="page" @endif href="{{ route('provider.settings.edit') }}"><span><i data-lucide="settings" aria-hidden="true"></i></span>{{ __('ui.settings') }}</a>
            </nav>
            <a class="pp-growth-card" href="{{ route('provider.services.create') }}"><span class="pp-growth-icon"><i data-lucide="briefcase-business" aria-hidden="true"></i></span><strong>Add a service</strong><i data-lucide="arrow-right" aria-hidden="true"></i></a>
            <div class="pp-sidebar-footer"><span>LocalPro &middot; Volta Region</span><form method="POST" action="{{ route('logout') }}">@csrf<button><i data-lucide="log-out" aria-hidden="true"></i> {{ __('ui.log_out') }}</button></form></div>
        </aside>
        <main class="pp-main">@include('components.breadcrumbs.breadcrumbs') @if(session('auth_success'))@include('components.success-toast.success-toast', ['title' => 'Welcome to LocalPro', 'message' => session('auth_success')])@endif @if(session('status'))<div class="pp-flash" role="status"><span class="flash-check" aria-hidden="true">&#10003;</span>{{ session('status') }}</div>@endif @yield('content')</main>
    </div>
</body>
</html>
