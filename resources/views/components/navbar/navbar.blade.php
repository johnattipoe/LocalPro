<header class="topbar">
    <a class="brand" href="{{ route('home') }}"><span class="brand-mark"><img class="brand-logo-mark" src="{{ asset('logo/localpro-mark.png') }}" alt=""></span><span>local<span>pro</span><small>VOLTA REGION</small></span></a>
    <nav class="main-nav" aria-label="Main navigation">
        <a href="{{ route('services.index') }}" @class(['active' => request()->routeIs('services.*')]) @if(request()->routeIs('services.*')) aria-current="page" @endif><i data-lucide="briefcase-business" aria-hidden="true"></i> {{ __('ui.services') }}</a>
        <a href="{{ route('professionals.index') }}" @class(['active' => request()->routeIs('professionals.*', 'providers.show')]) @if(request()->routeIs('professionals.*', 'providers.show')) aria-current="page" @endif><i data-lucide="search" aria-hidden="true"></i> {{ __('ui.find_professional') }}</a>
        <a href="{{ route('how-it-works') }}" @class(['active' => request()->routeIs('how-it-works')]) @if(request()->routeIs('how-it-works')) aria-current="page" @endif><i data-lucide="circle-help" aria-hidden="true"></i> {{ __('ui.how_it_works') }}</a>
        <a href="{{ route('contact') }}" @class(['active' => request()->routeIs('contact*')]) @if(request()->routeIs('contact*')) aria-current="page" @endif><i data-lucide="life-buoy" aria-hidden="true"></i> {{ __('ui.support') }}</a>
    </nav>
    <div class="top-actions">
        @include('components.language-picker.language-picker')
        @auth
            @include('components.navbar.account-menu')
        @else
            <a class="login-link" href="{{ route('login') }}">{{ __('ui.log_in') }}</a>
            <a class="button button-small" href="{{ route('register') }}">{{ __('ui.create_account') }}</a>
        @endauth
    </div>
</header>
@include('components.breadcrumbs.breadcrumbs')
