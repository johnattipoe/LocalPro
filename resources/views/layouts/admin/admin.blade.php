<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('logo/localpro-mark.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LocalPro administration')</title>
    @vite(['resources/css/app.css','resources/ts/app/app.ts'])
</head>
<body class="admin-shell">
    <aside class="admin-sidebar" id="adminSidebar">
        <a class="admin-brand" href="{{ route('admin.dashboard') }}" aria-label="LocalPro admin dashboard">
            <img class="admin-brand-icon" src="{{ asset('logo/localpro-mark.png') }}" alt="">
            <span><strong>LocalPro</strong><small>Trusted Local Services</small></span>
        </a>
        <nav class="admin-side-nav" aria-label="Administrator navigation">
            <span class="admin-nav-label">{{ __('ui.workspace') }}</span>
            <a @class(['is-active' => request()->routeIs('admin.dashboard')]) href="{{ route('admin.dashboard') }}"><span><i data-lucide="home" aria-hidden="true"></i></span>{{ __('ui.dashboard') }}</a>
            <a @class(['is-active' => request()->routeIs('admin.users.*')]) href="{{ route('admin.users.index') }}"><span><i data-lucide="users-round" aria-hidden="true"></i></span>{{ __('ui.users') }}</a>
            <a @class(['is-active' => request()->routeIs('admin.providers.index') && request('status') !== 'pending']) href="{{ route('admin.providers.index', ['status' => 'all']) }}"><span><i data-lucide="briefcase-business" aria-hidden="true"></i></span>{{ __('ui.providers') }}</a>
            <a @class(['is-active' => request()->routeIs('admin.providers.*') && request('status') === 'pending']) href="{{ route('admin.providers.index', ['status' => 'pending']) }}"><span><i data-lucide="shield-check" aria-hidden="true"></i></span>{{ __('ui.verification') }} @if(($adminPendingCount ?? 0) > 0)<b class="admin-nav-count">{{ $adminPendingCount }}</b>@endif</a>
            <a @class(['is-active' => request()->routeIs('admin.categories.*')]) href="{{ route('admin.categories.index') }}"><span><i data-lucide="tag" aria-hidden="true"></i></span>{{ __('ui.service_categories') }}</a>
            <a @class(['is-active' => request()->routeIs('admin.bookings.*')]) href="{{ route('admin.bookings.index') }}"><span><i data-lucide="calendar-days" aria-hidden="true"></i></span>{{ __('ui.bookings') }}</a>
            <a @class(['is-active' => request()->routeIs('admin.payments.*')]) href="{{ route('admin.payments.index') }}"><span><i data-lucide="credit-card" aria-hidden="true"></i></span>{{ __('ui.payments') }}</a>
            <a @class(['is-active' => request()->routeIs('admin.payouts.*')]) href="{{ route('admin.payouts.index') }}"><span><i data-lucide="wallet" aria-hidden="true"></i></span>{{ __('ui.payouts') }}</a>
            <span class="admin-nav-label admin-nav-label-spaced">{{ __('ui.help_information') }}</span>
            <a @class(['is-active' => request()->routeIs('admin.reports.*')]) href="{{ route('admin.reports.index') }}"><span><i data-lucide="star" aria-hidden="true"></i></span>{{ __('ui.reviews') }}</a>
            <a @class(['is-active' => request()->routeIs('admin.analytics.*')]) href="{{ route('admin.analytics.index') }}"><span><i data-lucide="chart-no-axes-combined" aria-hidden="true"></i></span>{{ __('ui.reports') }}</a>
            <a @class(['is-active' => request()->routeIs('admin.support.*')]) href="{{ route('admin.support.index') }}"><span><i data-lucide="life-buoy" aria-hidden="true"></i></span>{{ __('ui.disputes') }} @if(($adminOpenTicketsCount ?? 0) > 0)<b class="admin-nav-count">{{ min(99, $adminOpenTicketsCount) }}</b>@endif</a>
            <span class="admin-nav-label admin-nav-label-spaced">{{ __('ui.settings') }}</span>
            <a @class(['is-active' => request()->routeIs('admin.settings.*')]) href="{{ route('admin.settings.index') }}"><span><i data-lucide="settings" aria-hidden="true"></i></span>{{ __('ui.settings') }}</a>
        </nav>
        <div class="admin-sidebar-bottom">
            <a class="admin-sidebar-help" href="{{ route('contact') }}"><span><i data-lucide="life-buoy" aria-hidden="true"></i></span><span><strong>Need help?</strong><small>Contact LocalPro support</small></span><i data-lucide="arrow-right" aria-hidden="true"></i></a>
            <div class="admin-location"><span><i data-lucide="map-pin" aria-hidden="true"></i></span><div><strong>Ho, Volta Region</strong><small>Ghana</small></div></div>
            <div class="admin-sidebar-account"><span class="admin-sidebar-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span><span><strong>{{ auth()->user()->name }}</strong><small>{{ __('ui.administrator') }}</small></span><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" aria-label="Log out"><i data-lucide="log-out" aria-hidden="true"></i></button></form></div>
            <small class="admin-copyright">© {{ now()->year }} LocalPro. All rights reserved.</small>
        </div>
    </aside>
    <div class="admin-main">
        <header class="admin-topbar">
            <button class="admin-mobile-menu portal-sidebar-toggle" type="button" aria-label="Open admin sidebar" aria-controls="adminSidebar" aria-expanded="false" data-sidebar-toggle data-sidebar-mode="drawer"><i data-lucide="menu" aria-hidden="true"></i></button>
            <form class="admin-global-search" action="{{ route('admin.users.index') }}" method="GET" role="search">
                <span aria-hidden="true"><i data-lucide="search"></i></span>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search users by name or email..." aria-label="Search users by name or email">
            </form>
            <div class="admin-topbar-actions">
                @include('components.language-picker.language-picker', ['id' => 'admin-language'])
                <a class="admin-bell" href="{{ route('notifications.index') }}" aria-label="Open notifications"><span><i data-lucide="bell" aria-hidden="true"></i></span>@if(($adminUnreadCount ?? 0) > 0)<i>{{ min(99, $adminUnreadCount) }}</i>@endif</a>
                <details class="admin-account-menu">
                    <summary class="admin-account-trigger" aria-label="Administrator account menu for {{ auth()->user()->name }}"><span class="admin-user-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span><span class="admin-user-name"><strong>{{ auth()->user()->name }}</strong><small>{{ __('ui.administrator') }}</small></span><i data-lucide="chevron-down" aria-hidden="true"></i></summary>
                    <div class="admin-account-dropdown"><strong>{{ auth()->user()->name }}</strong><small>Administrator account</small><a href="{{ route('admin.dashboard') }}">{{ __('ui.dashboard') }}</a><a href="{{ route('admin.settings.index') }}">{{ __('ui.settings') }}</a><a href="{{ route('admin.users.index') }}">Manage users</a><a href="{{ route('admin.support.index') }}">Support inbox</a><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit"><i data-lucide="log-out" aria-hidden="true"></i> {{ __('ui.log_out') }}</button></form></div>
                </details>
            </div>
        </header>
        <main class="admin-content-area">
            @include('components.breadcrumbs.breadcrumbs')
            @if(session('auth_success'))@include('components.success-toast.success-toast', ['title' => 'Welcome back', 'message' => session('auth_success')])@endif
            @yield('content')
        </main>
    </div>
</body>
</html>
