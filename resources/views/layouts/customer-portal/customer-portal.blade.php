<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('logo/localpro-mark.png') }}">
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Customer dashboard') | LocalPro</title>
    @vite(['resources/css/app.css', 'resources/ts/app/app.ts'])
</head>
<body class="customer-portal">
    @php($customer = auth()->user())
    <header class="cp-topbar">
        <a class="cp-brand" href="{{ route('customer.dashboard') }}"><img class="cp-brand-mark" src="{{ asset('logo/localpro-mark.png') }}" alt=""><span>Local<span>Pro</span><small>Trusted Local Services</small></span></a>
        <button class="portal-sidebar-toggle" type="button" aria-label="Hide customer sidebar" aria-controls="customerSidebar" aria-expanded="true" data-sidebar-toggle data-sidebar-mode="collapse"><i data-lucide="panel-left-close" aria-hidden="true"></i></button>
        <form class="cp-search" action="{{ route('professionals.index') }}" method="GET"><span aria-hidden="true"><i data-lucide="search"></i></span><input name="q" value="{{ request('q') }}" aria-label="Search professionals" placeholder="Search for services or professionals..."><button aria-label="Search"><i data-lucide="arrow-right" aria-hidden="true"></i></button></form>
        <a class="cp-location" href="{{ route('professionals.index', ['city' => $customer->city ?: 'Ho']) }}"><span><i data-lucide="map-pin" aria-hidden="true"></i></span> {{ $customer->city ?: 'Ho' }}, Volta Region <span class="cp-chevron"><i data-lucide="chevron-down" aria-hidden="true"></i></span></a>
        <a class="cp-icon-link" href="{{ route('notifications.index') }}" aria-label="Notifications"><i data-lucide="bell" aria-hidden="true"></i>@if(isset($stats) && $stats['unread_notifications'] > 0)<b>{{ min($stats['unread_notifications'], 9) }}</b>@endif</a>
        <a class="cp-icon-link" href="{{ route('messages.index') }}" aria-label="Messages"><i data-lucide="message-circle" aria-hidden="true"></i>@if(isset($stats) && $stats['unread_messages'] > 0)<b>{{ min($stats['unread_messages'], 9) }}</b>@endif</a>
        @include('components.language-picker.language-picker', ['id' => 'customer-language'])
        <details class="cp-account-menu">
            <summary class="cp-user" aria-label="Account menu for {{ $customer->name }}"><span class="cp-avatar">{{ collect(explode(' ', $customer->name))->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->join('') }}</span><span><strong>{{ $customer->name }}</strong><small>{{ __('ui.customer') }}</small></span><span class="cp-account-chevron"><i data-lucide="chevron-down" aria-hidden="true"></i></span></summary>
            <div class="cp-account-dropdown"><strong>{{ $customer->name }}</strong><small>{{ __('ui.customer') }} {{ __('ui.account') }}</small><a href="{{ route('customer.dashboard') }}">{{ __('ui.dashboard') }}</a><a href="{{ route('customer.profile.edit') }}">{{ __('ui.my_profile') }}</a><a href="{{ route('customer.settings') }}">{{ __('ui.settings') }}</a><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit"><i data-lucide="log-out" aria-hidden="true"></i> {{ __('ui.log_out') }}</button></form></div>
        </details>
    </header>
    <div class="cp-frame">
        <aside class="cp-sidebar" id="customerSidebar">
            <nav class="cp-nav" aria-label="Customer navigation">
                <a title="{{ __('ui.dashboard') }}" @if(request()->routeIs('customer.dashboard')) aria-current="page" @endif class="{{ request()->routeIs('customer.dashboard') ? 'is-active' : '' }}" href="{{ route('customer.dashboard') }}"><span><i data-lucide="home" aria-hidden="true"></i></span>{{ __('ui.dashboard') }}</a>
                <a title="{{ __('ui.find_services') }}" @if(request()->routeIs('professionals.*','services.*')) aria-current="page" @endif class="{{ request()->routeIs('professionals.*','services.*') ? 'is-active' : '' }}" href="{{ route('professionals.index') }}"><span><i data-lucide="search" aria-hidden="true"></i></span>{{ __('ui.find_services') }}</a>
                <a title="{{ __('ui.my_requests') }}" @if(request()->routeIs('customer.requests.*')) aria-current="page" @endif class="{{ request()->routeIs('customer.requests.*') ? 'is-active' : '' }}" href="{{ route('customer.requests.index') }}"><span><i data-lucide="clipboard-list" aria-hidden="true"></i></span>{{ __('ui.my_requests') }}</a>
                <a title="{{ __('ui.bookings') }}" @if(request()->routeIs('customer.bookings.*')) aria-current="page" @endif class="{{ request()->routeIs('customer.bookings.*') ? 'is-active' : '' }}" href="{{ route('customer.bookings.index') }}"><span><i data-lucide="calendar-days" aria-hidden="true"></i></span>{{ __('ui.bookings') }}</a>
                <a title="{{ __('ui.messages') }}" @if(request()->routeIs('messages.*')) aria-current="page" @endif class="{{ request()->routeIs('messages.*') ? 'is-active' : '' }}" href="{{ route('messages.index') }}"><span><i data-lucide="message-circle" aria-hidden="true"></i></span>{{ __('ui.messages') }} @if(isset($stats) && $stats['unread_messages'] > 0)<i>{{ $stats['unread_messages'] }}</i>@endif</a>
                <a title="{{ __('ui.reviews') }}" @if(request()->routeIs('customer.reviews.*')) aria-current="page" @endif class="{{ request()->routeIs('customer.reviews.*') ? 'is-active' : '' }}" href="{{ route('customer.reviews.index') }}"><span><i data-lucide="star" aria-hidden="true"></i></span>{{ __('ui.reviews') }}</a>
                <a title="{{ __('ui.payments') }}" @if(request()->routeIs('customer.payments.*')) aria-current="page" @endif class="{{ request()->routeIs('customer.payments.*') ? 'is-active' : '' }}" href="{{ route('customer.payments.index') }}"><span><i data-lucide="credit-card" aria-hidden="true"></i></span>{{ __('ui.payments') }}</a>
                <a title="{{ __('ui.job_history') }}" @if(request()->routeIs('customer.history')) aria-current="page" @endif class="{{ request()->routeIs('customer.history') ? 'is-active' : '' }}" href="{{ route('customer.history') }}"><span><i data-lucide="history" aria-hidden="true"></i></span>{{ __('ui.job_history') }}</a>
                <a title="{{ __('ui.profile') }}" @if(request()->routeIs('customer.profile.*')) aria-current="page" @endif class="{{ request()->routeIs('customer.profile.*') ? 'is-active' : '' }}" href="{{ route('customer.profile.edit') }}"><span><i data-lucide="user-round" aria-hidden="true"></i></span>{{ __('ui.profile') }}</a>
                <a title="{{ __('ui.settings') }}" @if(request()->routeIs('customer.settings')) aria-current="page" @endif class="{{ request()->routeIs('customer.settings') ? 'is-active' : '' }}" href="{{ route('customer.settings') }}"><span><i data-lucide="settings" aria-hidden="true"></i></span>{{ __('ui.settings') }}</a>
            </nav>
            <a class="cp-local-card" href="{{ route('services.index') }}"><span class="cp-local-pin"><i data-lucide="map-pin" aria-hidden="true"></i></span><strong>Explore local services</strong><i class="cp-local-arrow" data-lucide="arrow-right" aria-hidden="true"></i></a>
            <div class="cp-sidebar-footer"><span>LocalPro &middot; Ho, Volta Region</span><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" title="{{ __('ui.log_out') }}" aria-label="{{ __('ui.log_out') }}"><i data-lucide="log-out" aria-hidden="true"></i><span>{{ __('ui.log_out') }}</span></button></form></div>
        </aside>
        <main class="cp-main">
            @include('components.breadcrumbs.breadcrumbs')
            @if(session('auth_success'))@include('components.success-toast.success-toast', ['title' => 'Welcome to LocalPro', 'message' => session('auth_success')])@endif
            @if(session('status'))<div class="cp-flash"><span class="flash-check" aria-hidden="true">&#10003;</span>{{ session('status') }}</div>@endif
            @yield('content')
        </main>
    </div>
</body>
</html>
